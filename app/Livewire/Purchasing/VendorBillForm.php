<?php

namespace App\Livewire\Purchasing;

use App\Livewire\Concerns\WithDocumentRibbon;
use App\Livewire\Concerns\WithLineItems;
use App\Mail\DocumentMail;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\Vendor;
use App\Models\VendorBill;
use App\Models\VendorBillLine;
use App\Services\DocumentPdfService;
use App\Services\InventoryService;
use App\Support\DocumentNumbers;
use App\Support\ItemCatalog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
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

    public bool $isRtv = false;

    public string $ribbonTab = 'main';

    public string $inspectorTab = 'name';

    public bool $inspectorOpen = true;

    public string $saveMode = 'close';

    /** @var array<int, array{account: string, description: string, amount: string, customer_job: string, billable: bool, class: string}> */
    public array $expenseLines = [];

    public function mount(?VendorBill $vendorBill = null): void
    {
        abort_unless(auth()->user()?->hasPermission('purchase.create'), 403);

        $this->isRtv = request()->routeIs('vendor-returns.create')
            || request()->routeIs('vendor-returns.edit')
            || request()->boolean('rtv')
            || request('type') === 'rtv';

        if ($vendorBill?->exists) {
            $this->loadDocumentIntoForm($vendorBill);
            $this->navigatorId = (int) $vendorBill->id;
            $memo = (string) ($vendorBill->memo ?? '');
            $this->isRtv = $this->isRtv || str_starts_with((string) $vendorBill->bill_number, 'RTV-') || str_contains($memo, 'CREDIT');
            if ($this->isRtv) {
                $this->docType = 'credit';
            }

            return;
        }

        if ($this->isRtv) {
            $this->docType = 'credit';
            $this->bill_number = DocumentNumbers::next(VendorBill::class, 'bill_number', 'RTV-');
        } else {
            $this->bill_number = DocumentNumbers::next(VendorBill::class, 'bill_number', 'BILL-');
        }

        $this->bill_date = now()->toDateString();
        $this->due_date = now()->addDays(30)->toDateString();
        $this->ensureLineCapacity(8);
        $this->addExpenseLine();
        $this->restoreMemorizedIfEmpty();

        if ($this->isRtv) {
            $this->docType = 'credit';
            $this->lineTab = 'items';
        }
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

        if ($this->isRtv || $this->docType === 'credit') {
            $this->lines = [];
            $this->ensureLineCapacity(1);
            $this->lineTab = 'items';
        }
    }

    public function updatedPurchaseOrderId(?string $value = null): void
    {
        if ($this->purchase_order_id === '') {
            return;
        }

        $this->selectPurchaseOrder();
    }

    public function selectPurchaseOrder(): void
    {
        if ($this->purchase_order_id === '') {
            $this->addError('purchase_order_id', 'Select a purchase order first.');
            $this->dispatch('be-toast', message: 'Select a purchase order first.');

            return;
        }

        $poId = $this->purchase_order_id;

        $po = PurchaseOrder::query()
            ->with('lines.item')
            ->whereKey($poId)
            ->when($this->vendor_id !== '', fn ($q) => $q->where('vendor_id', $this->vendor_id))
            ->firstOrFail();

        $this->vendor_id = (string) $po->vendor_id;
        $vendor = Vendor::query()->find($this->vendor_id);
        $this->address = $vendor ? implode("\n", $vendor->billFromLines()) : '';
        if ($vendor?->terms) {
            $this->terms = (string) $vendor->terms;
        }
        $this->purchase_order_id = $poId;
        $this->inspectorOpen = true;
        $this->inspectorTab = 'name';
        $this->lines = [];
        $this->lineTab = 'items';

        foreach ($po->lines as $line) {
            $ordered = (string) $line->quantity;
            $received = (string) $line->qty_received;
            // Credit/RTV: load received qty. Bill: load remaining to receive.
            $qty = ($this->isRtv || $this->docType === 'credit')
                ? $received
                : bcsub($ordered, $received, 4);

            if (bccomp($qty, '0', 4) <= 0) {
                continue;
            }

            $rate = number_format((float) $line->rate, 2, '.', '');
            $this->lines[] = [
                'item_id' => (string) ($line->item_id ?? ''),
                'item_code' => $line->item?->sku ?? '',
                'description' => $line->description ?? ($line->item?->purchase_description ?: $line->item?->name),
                'quantity' => number_format((float) $qty, 2, '.', ''),
                'rate' => $rate,
                'amount' => number_format((float) bcmul($qty, $rate, 4), 2, '.', ''),
                'customer_job' => '',
                'billable' => false,
                'class' => '',
                'purchase_order_line_id' => (string) $line->id,
            ];
        }

        if ($this->isRtv || $this->docType === 'credit') {
            if ($this->lines === []) {
                $this->ensureLineCapacity(1);
                $this->dispatch('be-toast', message: 'PO '.$po->number.' has no received qty to return.');

                return;
            }
        } else {
            $this->ensureLineCapacity(8);
        }

        $hint = ($this->isRtv || $this->docType === 'credit')
            ? 'Loaded '.count($this->lines).' received item(s) from PO '.$po->number.'. Adjust qty or remove lines, then save RTV.'
            : 'Loaded open qty from PO '.$po->number;
        $this->dispatch('be-toast', message: $hint);
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

    protected function itemSearchUsesPurchaseCatalog(): bool
    {
        return true;
    }

    protected function lineDescriptionForItem(Item $item): string
    {
        return (string) ($item->purchase_description ?: $item->name);
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
            'quantity' => number_format($qty, 2, '.', ''),
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
            'quantity' => '1.00',
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
            'bill_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('vendor_bills', 'bill_number')->ignore($this->navigatorId),
            ],
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
                'quantity' => '1.00',
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

        try {
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
                $memoValue = $memoParts !== [] ? implode(' · ', $memoParts) : null;

                if ($this->navigatorId) {
                    $bill = $this->updateExistingBill(
                        (int) $this->navigatorId,
                        $itemLines,
                        $expensePayload,
                        $subtotal,
                        $isCredit,
                        $memoValue
                    );

                    return;
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
                    'memo' => $memoValue,
                ]);

                $this->writeBillLinesAndStock($bill, $itemLines, $expensePayload, $isCredit, wasPosted: ! $this->is_pending);

                if (! $this->is_pending) {
                    $vendor = Vendor::query()->lockForUpdate()->findOrFail($bill->vendor_id);
                    $vendor->balance = $isCredit
                        ? bcsub((string) $vendor->balance, $subtotal, 2)
                        : bcadd((string) $vendor->balance, $subtotal, 2);
                    $vendor->save();
                }

                if ($isCredit && ! $this->is_pending && $this->purchase_order_id !== '') {
                    $this->refreshPurchaseOrderStatus((int) $this->purchase_order_id);
                }
            });
        } catch (\Throwable $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return null;
        }

        $this->navigatorId = (int) $bill->id;
        $stored = $this->storePendingAttachmentsFor('attachments/vendor-bills/'.$bill->bill_number);
        $label = $isCredit ? ($this->isRtv ? 'RTV' : 'Vendor credit') : 'Bill';
        $this->dispatch('be-toast', message: $label.' '.$this->bill_number.' saved.'
            .($stored ? ' '.$stored.' attachment(s) stored.' : ''));

        return $bill;
    }

    /**
     * @param  array<int, array<string, mixed>>  $itemLines
     * @param  array<int, array<string, mixed>>  $expensePayload
     */
    protected function updateExistingBill(
        int $billId,
        array $itemLines,
        array $expensePayload,
        string $subtotal,
        bool $isCredit,
        ?string $memoValue,
    ): VendorBill {
        $bill = VendorBill::query()->whereKey($billId)->lockForUpdate()->with('lines.item')->firstOrFail();
        $wasPending = $bill->status === 'pending' || str_contains((string) $bill->memo, 'PENDING');
        $wasCredit = str_contains((string) $bill->memo, 'CREDIT');
        $wasReceived = str_contains((string) $bill->memo, 'BILL RECEIVED');
        $oldBalanceDue = (string) $bill->balance_due;
        $amountPaid = (string) $bill->amount_paid;

        if (! $wasPending) {
            $this->reverseBillStock($bill, $wasCredit, $wasReceived);
        }

        $bill->lines()->delete();

        $balanceDue = $this->is_pending ? '0.00' : bcsub($subtotal, $amountPaid, 2);
        if (bccomp($balanceDue, '0', 2) < 0) {
            $amountPaid = $subtotal;
            $balanceDue = '0.00';
        }
        $status = $this->is_pending
            ? 'pending'
            : (bccomp($balanceDue, '0', 2) === 0
                ? 'paid'
                : (bccomp($amountPaid, '0', 2) > 0 ? 'partial' : 'open'));

        $bill->update([
            'bill_number' => $this->bill_number,
            'ref_no' => $this->ref_no ?: null,
            'vendor_id' => (int) $this->vendor_id,
            'bill_date' => $this->bill_date,
            'due_date' => $this->due_date ?: null,
            'status' => $status,
            'subtotal' => $subtotal,
            'total' => $subtotal,
            'amount_paid' => $amountPaid,
            'balance_due' => $balanceDue,
            'memo' => $memoValue,
        ]);

        $this->writeBillLinesAndStock($bill, $itemLines, $expensePayload, $isCredit, wasPosted: ! $this->is_pending);

        $arDelta = bcsub($balanceDue, $wasPending ? '0.00' : $oldBalanceDue, 2);
        if (bccomp($arDelta, '0', 2) !== 0) {
            $vendor = Vendor::query()->lockForUpdate()->findOrFail($bill->vendor_id);
            $vendor->balance = $isCredit || $wasCredit
                ? bcsub((string) $vendor->balance, $arDelta, 2)
                : bcadd((string) $vendor->balance, $arDelta, 2);
            $vendor->save();
        }

        if ($isCredit && ! $this->is_pending && $this->purchase_order_id !== '') {
            $this->refreshPurchaseOrderStatus((int) $this->purchase_order_id);
        }

        return $bill->fresh('lines');
    }

    /**
     * @param  array<int, array<string, mixed>>  $itemLines
     * @param  array<int, array<string, mixed>>  $expensePayload
     */
    protected function writeBillLinesAndStock(
        VendorBill $bill,
        array $itemLines,
        array $expensePayload,
        bool $isCredit,
        bool $wasPosted,
    ): void {
        foreach ($itemLines as $line) {
            VendorBillLine::query()->create([
                'vendor_bill_id' => $bill->id,
                'item_id' => $line['item_id'],
                'description' => $line['description'] ?? null,
                'quantity' => $line['quantity'],
                'rate' => $line['rate'],
                'amount' => number_format((float) $line['quantity'] * (float) $line['rate'], 2, '.', ''),
            ]);

            if (! $wasPosted || $this->is_pending) {
                continue;
            }

            $item = Item::query()->find($line['item_id']);
            if (! $item?->tracksInventory()) {
                continue;
            }

            $inventory = app(InventoryService::class);

            if ($isCredit) {
                $inventory->post($item, [
                    'type' => 'vendor_credit',
                    'qty_out' => $line['quantity'],
                    'unit_cost' => $line['rate'] ?: $item->average_cost,
                    'reference_type' => VendorBill::class,
                    'reference_id' => $bill->id,
                    'occurred_at' => $this->bill_date,
                    'created_by' => auth()->id(),
                    'memo' => 'Vendor return '.$bill->bill_number,
                ]);

                $this->reversePurchaseOrderReceive(
                    (int) ($line['purchase_order_line_id'] ?? 0),
                    (int) $line['item_id'],
                    (string) $line['quantity']
                );
            } elseif ($this->bill_received) {
                $inventory->post($item, [
                    'type' => 'purchase',
                    'qty_in' => $line['quantity'],
                    'unit_cost' => $line['rate'] ?: $item->average_cost ?: $item->purchase_cost,
                    'reference_type' => VendorBill::class,
                    'reference_id' => $bill->id,
                    'occurred_at' => $this->bill_date,
                    'created_by' => auth()->id(),
                    'memo' => 'Bill received '.$bill->bill_number,
                ]);
            }
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
    }

    protected function reverseBillStock(VendorBill $bill, bool $wasCredit, bool $wasReceived): void
    {
        $inventory = app(InventoryService::class);

        foreach ($bill->lines as $line) {
            if (! $line->item_id || ! $line->item?->tracksInventory()) {
                continue;
            }

            if ($wasCredit) {
                $inventory->post($line->item, [
                    'type' => 'vendor_credit_edit',
                    'qty_in' => $line->quantity,
                    'unit_cost' => $line->rate ?: $line->item->average_cost,
                    'reference_type' => VendorBill::class,
                    'reference_id' => $bill->id,
                    'occurred_at' => $this->bill_date,
                    'created_by' => auth()->id(),
                    'memo' => 'Reverse RTV/credit '.$bill->bill_number,
                ]);
            } elseif ($wasReceived) {
                $inventory->post($line->item, [
                    'type' => 'bill_edit',
                    'qty_out' => $line->quantity,
                    'unit_cost' => $line->rate ?: $line->item->average_cost,
                    'reference_type' => VendorBill::class,
                    'reference_id' => $bill->id,
                    'occurred_at' => $this->bill_date,
                    'created_by' => auth()->id(),
                    'allow_negative' => true,
                    'memo' => 'Reverse bill received '.$bill->bill_number,
                ]);
            }
        }
    }

    protected function reversePurchaseOrderReceive(int $poLineId, int $itemId, string $qty): void
    {
        if (bccomp($qty, '0', 4) <= 0) {
            return;
        }

        $poLine = null;
        if ($poLineId > 0) {
            $poLine = PurchaseOrderLine::query()->lockForUpdate()->find($poLineId);
        } elseif ($this->purchase_order_id !== '' && $itemId > 0) {
            $poLine = PurchaseOrderLine::query()
                ->where('purchase_order_id', (int) $this->purchase_order_id)
                ->where('item_id', $itemId)
                ->where('qty_received', '>', 0)
                ->lockForUpdate()
                ->orderByDesc('qty_received')
                ->first();
        }

        if (! $poLine) {
            return;
        }

        $reverse = $qty;
        if (bccomp((string) $poLine->qty_received, $reverse, 4) < 0) {
            $reverse = (string) $poLine->qty_received;
        }

        if (bccomp($reverse, '0', 4) <= 0) {
            return;
        }

        $poLine->qty_received = bcsub((string) $poLine->qty_received, $reverse, 4);
        $poLine->save();

        $item = Item::query()->lockForUpdate()->find($itemId ?: $poLine->item_id);
        if ($item?->tracksInventory()) {
            $item->on_po_qty = bcadd((string) $item->on_po_qty, $reverse, 4);
            $item->save();
        }
    }

    protected function refreshPurchaseOrderStatus(int $purchaseOrderId): void
    {
        $po = PurchaseOrder::query()->with('lines')->find($purchaseOrderId);
        if (! $po) {
            return;
        }

        $allReceived = $po->lines->every(fn ($l) => bccomp((string) $l->qty_received, (string) $l->quantity, 4) >= 0);
        $anyReceived = $po->lines->contains(fn ($l) => bccomp((string) $l->qty_received, '0', 4) > 0);
        $po->status = $allReceived ? 'received' : ($anyReceived ? 'partial' : 'open');
        $po->save();
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
                'amount' => $this->lineAmount($line['quantity'] ?? 0, $line['rate'] ?? 0),
                'purchase_order_line_id' => (int) ($line['purchase_order_line_id'] ?? 0),
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
        $this->bill_number = (string) $document->bill_number;

        $this->lines = [];
        $this->expenseLines = [];
        foreach ($document->lines as $line) {
            if ($line->item_id) {
                $this->lines[] = [
                    'item_id' => (string) $line->item_id,
                    'item_code' => $line->item?->barcode ?: $line->item?->sku ?: '',
                    'description' => (string) $line->description,
                    'quantity' => number_format((float) $line->quantity, 2, '.', ''),
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
        $isReturn = $this->isRtv || $this->docType === 'credit';
        $poStatuses = $isReturn
            ? ['open', 'partial', 'ordered', 'sent', 'received']
            : ['open', 'partial', 'ordered', 'sent'];

        $poQuery = PurchaseOrder::query()
            ->whereIn('status', $poStatuses)
            ->when($this->vendor_id !== '', fn ($q) => $q->where('vendor_id', $this->vendor_id))
            ->when($isReturn, fn ($q) => $q->whereHas(
                'lines',
                fn ($line) => $line->where('qty_received', '>', 0)
            ))
            ->orderByDesc('order_date');

        $openPos = $this->vendor_id !== ''
            ? $poQuery->get()
            : $poQuery->limit(25)->get();

        $poPlaceholder = $isReturn ? 'Select received PO…' : 'Select PO…';
        $pageTitle = $this->isRtv || $this->docType === 'credit'
            ? ($this->isRtv ? 'Return to Vendor (RTV)' : 'Vendor Credit')
            : 'Enter Bills';

        if ($this->navigatorId) {
            $pageTitle = 'Edit '.$pageTitle;
        }
        $vendor = $this->vendor_id !== ''
            ? Vendor::query()
                ->with(['vendorBills' => fn ($q) => $q->latest('bill_date')->limit(5)])
                ->find($this->vendor_id)
            : null;

        return view('livewire.purchasing.vendor-bill-form', [
            'vendors' => Vendor::query()->active()->orderBy('display_name')->pluck('display_name', 'id')->all(),
            'itemOptions' => ItemCatalog::selectOptions(500, purchase: true),
            'poOptions' => ['' => $poPlaceholder] + $openPos->mapWithKeys(
                fn (PurchaseOrder $po) => [$po->id => $po->number.' — '.number_format((float) $po->total, 2).($po->status === 'received' ? ' (received)' : ' ('.$po->status.')')]
            )->all(),
            'amountDue' => $this->amountDue(),
            'selectedVendor' => $vendor,
            'recentBills' => $vendor?->vendorBills ?? collect(),
            'pageTitle' => $pageTitle,
        ])->layoutData([
            'title' => $pageTitle,
            'windowTitle' => $pageTitle,
        ]);
    }
}
