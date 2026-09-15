<?php

namespace App\Livewire\Purchasing;

use App\Livewire\Concerns\WithDocumentRibbon;
use App\Livewire\Concerns\WithLineItems;
use App\Mail\DocumentMail;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\VendorBill;
use App\Models\VendorBillLine;
use App\Services\DocumentPdfService;
use App\Support\DocumentNumbers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Enter Bills')]
class VendorBillForm extends Component
{
    use WithDocumentRibbon;
    use WithLineItems;

    public string $docType = 'bill';

    public string $lineTab = 'items';

    public string $bill_number = '';

    public string $ref_no = '';

    public string $vendor_id = '';

    public string $address = '';

    public string $bill_date = '';

    public string $due_date = '';

    public string $terms = 'Net 30';

    public string $status = 'open';

    public string $memo = '';

    public string $purchase_order_id = '';

    public bool $bill_received = false;

    public bool $print_later = false;

    public bool $email_later = false;

    public bool $is_pending = false;

    public string $ribbonTab = 'main';

    public string $inspectorTab = 'name';

    public bool $inspectorOpen = true;

    public string $saveMode = 'close';

    /** @var array<int, array{account: string, description: string, amount: string, customer_job: string, billable: bool, class: string}> */
    public array $expenseLines = [];

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('purchase.create'), 403);
        $this->bill_number = DocumentNumbers::next(VendorBill::class, 'bill_number', 'BILL-');
        $this->bill_date = now()->toDateString();
        $this->due_date = now()->addDays(30)->toDateString();
        $this->ensureLineCapacity(8);
        $this->addExpenseLine();
        $this->restoreMemorizedIfEmpty();
    }

    public function toggleInspector(): void
    {
        $this->inspectorOpen = ! $this->inspectorOpen;
    }

    public function addExpenseLine(): void
    {
        $this->expenseLines[] = [
            'account' => '6000',
            'description' => '',
            'amount' => '0.00',
            'customer_job' => '',
            'billable' => false,
            'class' => '',
        ];
    }

    public function removeExpenseLine(int $index): void
    {
        unset($this->expenseLines[$index]);
        $this->expenseLines = array_values($this->expenseLines);
        if ($this->expenseLines === []) {
            $this->addExpenseLine();
        }
    }

    public function updatedVendorId(): void
    {
        $this->purchase_order_id = '';
        $vendor = Vendor::query()->find($this->vendor_id);
        $this->address = $vendor ? implode("\n", $vendor->billFromLines()) : '';
        if ($vendor?->terms) {
            $this->terms = (string) $vendor->terms;
        }
        if ($this->vendor_id) {
            $this->inspectorOpen = true;
            $this->inspectorTab = 'name';
        }
    }

    public function selectPurchaseOrder(): void
    {
        if ($this->purchase_order_id === '') {
            $this->addError('purchase_order_id', 'Select a purchase order first.');
            $this->dispatch('be-toast', message: 'Select a purchase order first.');

            return;
        }

        $po = PurchaseOrder::query()
            ->with('lines.item')
            ->whereKey($this->purchase_order_id)
            ->when($this->vendor_id !== '', fn ($q) => $q->where('vendor_id', $this->vendor_id))
            ->firstOrFail();

        $this->vendor_id = (string) $po->vendor_id;
        $this->updatedVendorId();
        $this->lines = [];
        $this->lineTab = 'items';

        foreach ($po->lines as $line) {
            $qty = number_format((float) $line->quantity, 4, '.', '');
            $rate = number_format((float) $line->rate, 2, '.', '');
            $this->lines[] = [
                'item_id' => (string) ($line->item_id ?? ''),
                'item_code' => $line->item?->sku ?? '',
                'description' => $line->description ?? ($line->item?->purchase_description ?: $line->item?->name),
                'quantity' => $qty,
                'rate' => $rate,
                'amount' => number_format((float) bcmul($qty, $rate, 4), 2, '.', ''),
                'customer_job' => '',
                'billable' => false,
                'class' => '',
            ];
        }

        $this->ensureLineCapacity(8);
        $this->dispatch('be-toast', message: 'Loaded lines from PO '.$po->number);
    }

    public function receiveAll(): void
    {
        $this->selectPurchaseOrder();
    }

    public function clearSplits(): void
    {
        $this->expenseLines = [];
        $this->addExpenseLine();
        $this->lines = [];
        $this->ensureLineCapacity(8);
        $this->dispatch('be-toast', message: 'Splits cleared.');
    }

    public function recalculate(): void
    {
        foreach ($this->lines as $index => $line) {
            $qty = (float) ($line['quantity'] ?? 0);
            $rate = (float) ($line['rate'] ?? 0);
            $this->lines[$index]['amount'] = number_format($qty * $rate, 2, '.', '');
        }
        $this->dispatch('be-toast', message: 'Recalculated. Amount Due: '.$this->amountDue());
    }

    protected function lineRateForItem(Item $item): float|string
    {
        return $item->purchase_cost ?: $item->sales_price;
    }

    protected function fillLineFromItem(int $index, Item $item): void
    {
        $rate = number_format((float) $this->lineRateForItem($item), 2, '.', '');
        $qty = (float) ($this->lines[$index]['quantity'] ?? 1);
        if ($qty <= 0) {
            $qty = 1;
        }

        $this->lines[$index] = [
            'item_id' => (string) $item->id,
            'item_code' => $item->barcode ?: $item->sku,
            'description' => $item->purchase_description ?: $item->name,
            'quantity' => number_format($qty, 4, '.', ''),
            'rate' => $rate,
            'amount' => number_format($qty * (float) $rate, 2, '.', ''),
            'customer_job' => $this->lines[$index]['customer_job'] ?? '',
            'billable' => (bool) ($this->lines[$index]['billable'] ?? false),
            'class' => $this->lines[$index]['class'] ?? '',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function emptyLine(): array
    {
        return [
            'item_id' => '',
            'item_code' => '',
            'description' => '',
            'quantity' => '1',
            'rate' => '0.00',
            'amount' => '0.00',
            'customer_job' => '',
            'billable' => false,
            'class' => '',
        ];
    }

    protected function ensureLineCapacity(int $min): void
    {
        while (count($this->lines) < $min) {
            $this->addLine();
        }
    }

    public function clearForm(): void
    {
        $this->navigatorId = null;
        $this->docType = 'bill';
        $this->lineTab = 'items';
        $this->vendor_id = '';
        $this->address = '';
        $this->ref_no = '';
        $this->memo = '';
        $this->terms = 'Net 30';
        $this->purchase_order_id = '';
        $this->bill_received = false;
        $this->bill_number = DocumentNumbers::next(VendorBill::class, 'bill_number', 'BILL-');
        $this->bill_date = now()->toDateString();
        $this->due_date = now()->addDays(30)->toDateString();
        $this->lines = [];
        $this->ensureLineCapacity(8);
        $this->expenseLines = [];
        $this->addExpenseLine();
        $this->scanCode = '';
        $this->print_later = false;
        $this->email_later = false;
        $this->is_pending = false;
        $this->pendingAttachments = [];
    }

    public function createCopy(): void
    {
        $this->navigatorId = null;
        $this->bill_number = DocumentNumbers::next(VendorBill::class, 'bill_number', 'BILL-');
        $this->bill_date = now()->toDateString();
        $this->due_date = now()->addDays(30)->toDateString();
        $this->print_later = false;
        $this->email_later = false;
        $this->is_pending = false;
        $this->dispatch('be-toast', message: 'Copied — new bill number assigned. Save when ready.');
    }

    public function memorize(): void
    {
        session()->put('memorized.vendor_bill', [
            'vendor_id' => $this->vendor_id,
            'terms' => $this->terms,
            'memo' => $this->memo,
            'docType' => $this->docType,
            'lines' => $this->lines,
            'expenseLines' => $this->expenseLines,
        ]);
        $this->dispatch('be-toast', message: 'Bill memorized for this session.');
    }

    public function togglePending(): void
    {
        $this->is_pending = ! $this->is_pending;
        $this->dispatch('be-toast', message: $this->is_pending
            ? 'Marked as pending.'
            : 'Pending cleared.');
    }

    public function attachFile(): void
    {
        $this->openAttachModal();
    }

    protected function restoreMemorizedIfEmpty(): void
    {
        $memorized = session('memorized.vendor_bill');
        if (! is_array($memorized) || filled($this->vendor_id)) {
            return;
        }

        $this->vendor_id = (string) ($memorized['vendor_id'] ?? '');
        $this->terms = (string) ($memorized['terms'] ?? 'Net 30');
        $this->memo = (string) ($memorized['memo'] ?? '');
        $this->docType = (string) ($memorized['docType'] ?? 'bill');
        if (is_array($memorized['lines'] ?? null) && $memorized['lines'] !== []) {
            $this->lines = $memorized['lines'];
        }
        if (is_array($memorized['expenseLines'] ?? null) && $memorized['expenseLines'] !== []) {
            $this->expenseLines = $memorized['expenseLines'];
        }
        if ($this->vendor_id !== '') {
            $this->updatedVendorId();
        }
    }

    public function expensesTotal(): string
    {
        $total = '0.00';
        foreach ($this->expenseLines as $line) {
            $total = bcadd($total, number_format((float) ($line['amount'] ?? 0), 2, '.', ''), 2);
        }

        return $total;
    }

    public function amountDue(): string
    {
        return bcadd($this->linesSubtotal(), $this->expensesTotal(), 2);
    }

    public function saveAndClose(): mixed
    {
        $this->saveMode = 'close';

        return $this->save();
    }

    public function saveAndNew(): mixed
    {
        $this->saveMode = 'new';

        return $this->save();
    }

    public function save(): mixed
    {
        $bill = $this->persistBill();
        if (! $bill) {
            return null;
        }

        if ($this->saveMode === 'stay') {
            return $bill;
        }

        if ($this->saveMode === 'new') {
            $this->clearForm();

            return null;
        }

        return $this->redirect(route('vendor-bills.index'), navigate: true);
    }

    protected function saveForRibbon(): ?Model
    {
        $this->saveMode = 'stay';

        return $this->persistBill();
    }

    protected function persistBill(): ?VendorBill
    {
        abort_unless(auth()->user()?->hasPermission('purchase.create'), 403);

        $this->validate([
            'bill_number' => ['required', 'string', 'max:50', 'unique:vendor_bills,bill_number'],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'bill_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'ref_no' => ['nullable', 'string', 'max:50'],
            'memo' => ['nullable', 'string', 'max:255'],
            'docType' => ['required', 'in:bill,credit'],
        ]);

        $itemLines = $this->validatedLinePayload(allowEmpty: true);
        $expensePayload = [];
        foreach ($this->expenseLines as $line) {
            $amount = number_format((float) ($line['amount'] ?? 0), 2, '.', '');
            if (bccomp($amount, '0', 2) <= 0 && blank($line['description'] ?? null)) {
                continue;
            }
            $expensePayload[] = [
                'description' => trim(($line['account'] ?? '').' '.($line['description'] ?? '')),
                'quantity' => '1.0000',
                'rate' => $amount,
                'amount' => $amount,
            ];
        }

        if ($itemLines === [] && $expensePayload === []) {
            $this->addError('lines', 'Add at least one item or expense line.');

            return null;
        }

        $subtotal = bcadd($this->linesSubtotal(), $this->expensesTotal(), 2);
        $isCredit = $this->docType === 'credit';
        $bill = null;

        DB::transaction(function () use ($itemLines, $expensePayload, $subtotal, $isCredit, &$bill) {
            $memoParts = [];
            if ($isCredit) {
                $memoParts[] = 'CREDIT';
            }
            if ($this->bill_received) {
                $memoParts[] = 'BILL RECEIVED';
            }
            if ($this->is_pending) {
                $memoParts[] = 'PENDING';
            }
            if ($this->memo) {
                $memoParts[] = $this->memo;
            }

            $bill = VendorBill::query()->create([
                'bill_number' => $this->bill_number,
                'ref_no' => $this->ref_no ?: null,
                'vendor_id' => (int) $this->vendor_id,
                'bill_date' => $this->bill_date,
                'due_date' => $this->due_date ?: null,
                'status' => $this->is_pending ? 'pending' : 'open',
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'amount_paid' => 0,
                'balance_due' => $subtotal,
                'memo' => $memoParts !== [] ? implode(' · ', $memoParts) : null,
            ]);

            foreach ($itemLines as $line) {
                VendorBillLine::query()->create([
                    'vendor_bill_id' => $bill->id,
                    'item_id' => $line['item_id'],
                    'description' => $line['description'] ?? null,
                    'quantity' => $line['quantity'],
                    'rate' => $line['rate'],
                    'amount' => number_format((float) $line['quantity'] * (float) $line['rate'], 2, '.', ''),
                ]);
            }

            foreach ($expensePayload as $line) {
                VendorBillLine::query()->create([
                    'vendor_bill_id' => $bill->id,
                    'item_id' => null,
                    'description' => $line['description'] ?: 'Expense',
                    'quantity' => $line['quantity'],
                    'rate' => $line['rate'],
                    'amount' => $line['amount'],
                ]);
            }

            if (! $this->is_pending) {
                $vendor = Vendor::query()->lockForUpdate()->findOrFail($bill->vendor_id);
                $vendor->balance = $isCredit
                    ? bcsub((string) $vendor->balance, $subtotal, 2)
                    : bcadd((string) $vendor->balance, $subtotal, 2);
                $vendor->save();
            }
        });

        $this->navigatorId = (int) $bill->id;
        $stored = $this->storePendingAttachmentsFor('attachments/vendor-bills/'.$bill->bill_number);
        $label = $isCredit ? 'Vendor credit' : 'Bill';
        $this->dispatch('be-toast', message: $label.' '.$this->bill_number.' saved.'
            .($stored ? ' '.$stored.' attachment(s) stored.' : ''));

        return $bill;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function validatedLinePayload(bool $allowEmpty = false): array
    {
        $payload = [];
        foreach ($this->lines as $line) {
            if (blank($line['item_id'] ?? null)) {
                continue;
            }
            $payload[] = [
                'item_id' => (int) $line['item_id'],
                'description' => $line['description'] ?? null,
                'quantity' => number_format((float) ($line['quantity'] ?? 0), 4, '.', ''),
                'rate' => number_format((float) ($line['rate'] ?? 0), 2, '.', ''),
            ];
        }

        if (! $allowEmpty && $payload === []) {
            $this->addError('lines', 'At least one item line is required.');
        }

        return $payload;
    }

    protected function documentModelClass(): string
    {
        return VendorBill::class;
    }

    protected function documentNumberColumn(): string
    {
        return 'bill_number';
    }

    protected function documentPdfRouteName(): string
    {
        return 'vendor-bills.pdf';
    }

    protected function documentBatchListRouteName(): string
    {
        return 'vendor-bills.index';
    }

    protected function documentPdfView(): string
    {
        return 'pdf.vendor-bill';
    }

    protected function documentPdfData(Model $document): array
    {
        return ['bill' => $document];
    }

    protected function resolveSavedDocument(): ?Model
    {
        if (! $this->navigatorId) {
            return null;
        }

        return VendorBill::query()->with('vendor')->find($this->navigatorId);
    }

    public function emailDocument(): void
    {
        $doc = $this->resolveSavedDocument() ?? $this->saveForRibbon();
        if (! $doc) {
            return;
        }

        $doc->loadMissing('vendor');
        $this->navigatorId = (int) $doc->getKey();
        $this->emailTo = (string) ($doc->vendor?->email ?: auth()->user()?->email ?: '');
        $this->emailSubject = $this->documentLabel($doc);
        $this->showEmailComposer = true;
    }

    public function sendRibbonEmail(DocumentPdfService $pdf): void
    {
        $this->validate([
            'emailTo' => ['required', 'email'],
            'emailSubject' => ['required', 'string', 'max:255'],
        ]);

        $doc = $this->resolveSavedDocument();
        if (! $doc) {
            $this->dispatch('be-toast', message: 'Save the document before emailing.');

            return;
        }

        $doc->loadMissing(['vendor', 'lines.item']);
        $content = $pdf->output($this->documentPdfView(), $this->documentPdfData($doc));
        $number = $this->documentLabel($doc);

        Mail::to($this->emailTo)->send(new DocumentMail(
            headline: $this->emailSubject,
            intro: 'Please find attached '.$number.' from '.config('bargain.company_name', config('app.name')).'.',
            pdf: [
                'filename' => str($number)->slug('-').'.pdf',
                'content' => $content,
            ],
        ));

        $this->showEmailComposer = false;
        $this->dispatch('be-toast', message: 'Emailed to '.$this->emailTo);
    }

    protected function loadDocumentIntoForm(Model $document): void
    {
        /** @var VendorBill $document */
        $document->loadMissing(['lines.item', 'vendor']);

        $this->vendor_id = (string) $document->vendor_id;
        $this->address = $document->vendor ? implode("\n", $document->vendor->billFromLines()) : '';
        $this->bill_date = $document->bill_date?->toDateString() ?: now()->toDateString();
        $this->due_date = $document->due_date?->toDateString() ?: now()->addDays(30)->toDateString();
        $this->ref_no = (string) ($document->ref_no ?? '');
        $memo = (string) ($document->memo ?? '');
        $this->docType = str_contains($memo, 'CREDIT') ? 'credit' : 'bill';
        $this->bill_received = str_contains($memo, 'BILL RECEIVED');
        $this->is_pending = $document->status === 'pending' || str_contains($memo, 'PENDING');
        $this->memo = trim(str_replace(['CREDIT · ', 'BILL RECEIVED · ', 'PENDING · ', 'CREDIT', 'BILL RECEIVED', 'PENDING'], '', $memo));
        $this->bill_number = DocumentNumbers::next(VendorBill::class, 'bill_number', 'BILL-');

        $this->lines = [];
        $this->expenseLines = [];
        foreach ($document->lines as $line) {
            if ($line->item_id) {
                $this->lines[] = [
                    'item_id' => (string) $line->item_id,
                    'item_code' => $line->item?->barcode ?: $line->item?->sku ?: '',
                    'description' => (string) $line->description,
                    'quantity' => number_format((float) $line->quantity, 4, '.', ''),
                    'rate' => number_format((float) $line->rate, 2, '.', ''),
                    'amount' => number_format((float) $line->amount, 2, '.', ''),
                    'customer_job' => '',
                    'billable' => false,
                    'class' => '',
                ];
            } else {
                $this->expenseLines[] = [
                    'account' => '6000',
                    'description' => (string) $line->description,
                    'amount' => number_format((float) $line->amount, 2, '.', ''),
                    'customer_job' => '',
                    'billable' => false,
                    'class' => '',
                ];
            }
        }
        $this->ensureLineCapacity(8);
        if ($this->expenseLines === []) {
            $this->addExpenseLine();
        }
        $this->lineTab = $this->lines !== [] && collect($this->lines)->contains(fn ($l) => filled($l['item_id'] ?? null))
            ? 'items'
            : 'expenses';
    }

    public function render()
    {
        $openPos = $this->vendor_id !== ''
            ? PurchaseOrder::query()
                ->where('vendor_id', $this->vendor_id)
                ->whereIn('status', ['open', 'partial', 'ordered', 'sent'])
                ->orderByDesc('order_date')
                ->get()
            : PurchaseOrder::query()
                ->whereIn('status', ['open', 'partial', 'ordered', 'sent'])
                ->orderByDesc('order_date')
                ->limit(25)
                ->get();

        $vendor = $this->vendor_id !== ''
            ? Vendor::query()
                ->with(['vendorBills' => fn ($q) => $q->latest('bill_date')->limit(5)])
                ->find($this->vendor_id)
            : null;

        return view('livewire.purchasing.vendor-bill-form', [
            'vendors' => Vendor::query()->active()->orderBy('display_name')->pluck('display_name', 'id')->all(),
            'itemOptions' => Item::query()->active()->orderBy('sku')->limit(500)->get()
                ->mapWithKeys(fn (Item $i) => [$i->id => ($i->barcode ?: $i->sku).' — '.($i->purchase_description ?: $i->name)])
                ->all(),
            'poOptions' => ['' => 'Select PO…'] + $openPos->mapWithKeys(
                fn (PurchaseOrder $po) => [$po->id => $po->number.' — '.number_format((float) $po->total, 2)]
            )->all(),
            'amountDue' => $this->amountDue(),
            'selectedVendor' => $vendor,
            'recentBills' => $vendor?->vendorBills ?? collect(),
        ])->layoutData([
            'title' => 'Enter Bills',
            'windowTitle' => 'Enter Bills',
        ]);
    }
}
