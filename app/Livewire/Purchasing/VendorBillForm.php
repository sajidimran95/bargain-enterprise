<?php

namespace App\Livewire\Purchasing;

use App\Livewire\Concerns\WithLineItems;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Models\VendorBill;
use App\Models\VendorBillLine;
use App\Support\DocumentNumbers;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Enter Bills')]
class VendorBillForm extends Component
{
    use WithLineItems;

    public string $docType = 'bill';

    public string $lineTab = 'items';

    public string $bill_number = '';

    public string $ref_no = '';

    public string $vendor_id = '';

    public string $bill_date = '';

    public string $due_date = '';

    public string $terms = 'Net 30';

    public string $status = 'open';

    public string $memo = '';

    public string $purchase_order_id = '';

    /** @var array<int, array{account: string, description: string, amount: string}> */
    public array $expenseLines = [];

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('purchase.create'), 403);
        $this->bill_number = DocumentNumbers::next(VendorBill::class, 'bill_number', 'BILL-');
        $this->bill_date = now()->toDateString();
        $this->due_date = now()->addDays(30)->toDateString();
        $this->addLine();
        $this->addExpenseLine();
    }

    public function addExpenseLine(): void
    {
        $this->expenseLines[] = [
            'account' => '6000',
            'description' => '',
            'amount' => '0.00',
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
    }

    public function selectPurchaseOrder(): void
    {
        if ($this->purchase_order_id === '') {
            $this->addError('purchase_order_id', 'Select a purchase order first.');

            return;
        }

        $po = PurchaseOrder::query()
            ->with('lines.item')
            ->whereKey($this->purchase_order_id)
            ->when($this->vendor_id !== '', fn ($q) => $q->where('vendor_id', $this->vendor_id))
            ->firstOrFail();

        $this->vendor_id = (string) $po->vendor_id;
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
            ];
        }

        if ($this->lines === []) {
            $this->addLine();
        }

        $this->dispatch('be-toast', message: 'Loaded lines from PO '.$po->number);
    }

    protected function lineRateForItem(Item $item): float|string
    {
        return $item->purchase_cost ?: $item->sales_price;
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

    public function save(): mixed
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

        DB::transaction(function () use ($itemLines, $expensePayload, $subtotal, $isCredit) {
            $bill = VendorBill::query()->create([
                'bill_number' => $this->bill_number,
                'ref_no' => $this->ref_no ?: null,
                'vendor_id' => (int) $this->vendor_id,
                'bill_date' => $this->bill_date,
                'due_date' => $this->due_date ?: null,
                'status' => 'open',
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'amount_paid' => 0,
                'balance_due' => $subtotal,
                'memo' => trim(($isCredit ? 'CREDIT · ' : '').($this->memo ?: '')),
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

            $vendor = Vendor::query()->lockForUpdate()->findOrFail($bill->vendor_id);
            $vendor->balance = $isCredit
                ? bcsub((string) $vendor->balance, $subtotal, 2)
                : bcadd((string) $vendor->balance, $subtotal, 2);
            $vendor->save();
        });

        $label = $isCredit ? 'Vendor credit' : 'Bill';
        $this->dispatch('be-toast', message: $label.' '.$this->bill_number.' saved.');

        return $this->redirect(route('vendor-bills.index'), navigate: true);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function validatedLinePayload(bool $allowEmpty = false): array
    {
        $payload = [];
        foreach ($this->lines as $index => $line) {
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

        return view('livewire.purchasing.vendor-bill-form', [
            'vendors' => Vendor::query()->active()->orderBy('display_name')->pluck('display_name', 'id')->all(),
            'itemOptions' => Item::query()->active()->orderBy('sku')->get()
                ->mapWithKeys(fn (Item $i) => [$i->id => $i->sku.' — '.($i->purchase_description ?: $i->name)])
                ->all(),
            'poOptions' => ['' => 'Select PO…'] + $openPos->mapWithKeys(
                fn (PurchaseOrder $po) => [$po->id => $po->number.' — '.number_format((float) $po->total, 2)]
            )->all(),
            'amountDue' => $this->amountDue(),
            'selectedVendor' => $this->vendor_id !== ''
                ? Vendor::query()->find($this->vendor_id)
                : null,
        ])->layoutData([
            'title' => 'Enter Bills',
            'windowTitle' => 'Enter Bills',
        ]);
    }
}
