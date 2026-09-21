<?php

namespace App\Livewire\Purchasing;

use App\Actions\Purchasing\ReceiveGoodsAction;
use App\Actions\Purchasing\UpdateGoodsReceiptAction;
use App\Livewire\Concerns\WithLineItems;
use App\Models\GoodsReceipt;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\Vendor;
use App\Support\DocumentNumbers;
use App\Support\ItemCatalog;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Receive Inventory')]
class GoodsReceiptForm extends Component
{
    use WithLineItems;

    public ?int $editingId = null;

    public string $number = '';

    public string $vendor_id = '';

    public string $purchase_order_id = '';

    public string $receipt_date = '';

    public string $memo = '';

    public function mount(?GoodsReceipt $goodsReceipt = null): void
    {
        abort_unless(auth()->user()?->hasPermission('purchase.create'), 403);

        if ($goodsReceipt?->exists) {
            $this->loadReceipt($goodsReceipt);

            return;
        }

        $this->number = DocumentNumbers::next(GoodsReceipt::class, 'number', 'GR-');
        $this->receipt_date = now()->toDateString();
        $this->addLine();
    }

    public function loadReceipt(GoodsReceipt $receipt): void
    {
        $receipt->loadMissing('lines.item');
        $this->editingId = (int) $receipt->id;
        $this->number = (string) $receipt->number;
        $this->vendor_id = (string) $receipt->vendor_id;
        $this->purchase_order_id = (string) ($receipt->purchase_order_id ?? '');
        $this->receipt_date = $receipt->receipt_date?->toDateString() ?: now()->toDateString();
        $this->memo = (string) ($receipt->memo ?? '');
        $this->lines = [];
        foreach ($receipt->lines as $line) {
            $this->lines[] = [
                'item_id' => (string) $line->item_id,
                'item_code' => $line->item?->barcode ?: $line->item?->sku ?: '',
                'description' => $line->item?->purchase_description ?: $line->item?->name ?: '',
                'quantity' => number_format((float) $line->quantity, 2, '.', ''),
                'ordered_qty' => '',
                'previously_received' => '',
                'rate' => number_format((float) $line->unit_cost, 2, '.', ''),
                'amount' => number_format((float) $line->quantity * (float) $line->unit_cost, 2, '.', ''),
                'taxable' => false,
                'class' => '',
                'purchase_order_line_id' => (string) ($line->purchase_order_line_id ?? ''),
            ];
        }
        if ($this->lines === []) {
            $this->addLine();
        }
    }

    public function updatedVendorId(): void
    {
        $this->purchase_order_id = '';
        $this->lines = [];
        $this->addLine();
        $this->itemSearchResults = [];
        $this->scanCode = '';
    }

    public function updatedPurchaseOrderId(?string $value = null): void
    {
        if ($this->purchase_order_id === '') {
            $this->lines = [];
            $this->addLine();

            return;
        }

        $this->loadPurchaseOrderLines();
    }

    public function loadPurchaseOrderLines(): void
    {
        if ($this->vendor_id === '') {
            $this->addError('vendor_id', 'Select a vendor first.');
            $this->dispatch('be-toast', message: 'Select a vendor first.');
            $this->purchase_order_id = '';

            return;
        }

        if ($this->purchase_order_id === '') {
            $this->addError('purchase_order_id', 'Select a purchase order.');
            $this->dispatch('be-toast', message: 'Select a purchase order.');

            return;
        }

        $po = PurchaseOrder::query()
            ->with('lines.item')
            ->whereKey($this->purchase_order_id)
            ->where('vendor_id', $this->vendor_id)
            ->whereIn('status', ['open', 'partial', 'ordered', 'sent'])
            ->first();

        if (! $po) {
            $this->dispatch('be-toast', message: 'Purchase order not found for this vendor.');
            $this->purchase_order_id = '';
            $this->lines = [];
            $this->addLine();

            return;
        }

        $this->lines = [];

        foreach ($po->lines as $line) {
            $remaining = bcsub((string) $line->quantity, (string) $line->qty_received, 4);
            if (bccomp($remaining, '0', 4) <= 0) {
                continue;
            }

            $rate = number_format((float) $line->rate, 2, '.', '');
            $item = $line->item;

            $this->lines[] = [
                'item_id' => (string) ($line->item_id ?? ''),
                'item_code' => $item?->barcode ?: $item?->sku ?: '',
                'description' => $line->description ?? ($item?->purchase_description ?: $item?->name ?: ''),
                'quantity' => number_format((float) $remaining, 2, '.', ''),
                'ordered_qty' => number_format((float) $line->quantity, 2, '.', ''),
                'previously_received' => number_format((float) $line->qty_received, 2, '.', ''),
                'rate' => $rate,
                'amount' => number_format((float) bcmul($remaining, $rate, 4), 2, '.', ''),
                'taxable' => false,
                'class' => '',
                'purchase_order_line_id' => (string) $line->id,
            ];
        }

        if ($this->lines === []) {
            $this->addLine();
            $this->dispatch('be-toast', message: 'PO '.$po->number.' has nothing left to receive.');

            return;
        }

        $this->dispatch('be-toast', message: 'Loaded '.count($this->lines).' open line(s) from PO '.$po->number.'. Adjust qty then Save.');
    }

    protected function lineRateForItem(Item $item): float|string
    {
        return $item->purchase_cost ?: $item->average_cost ?: $item->sales_price;
    }

    protected function itemSearchUsesPurchaseCatalog(): bool
    {
        return true;
    }

    protected function lineDescriptionForItem(Item $item): string
    {
        return (string) ($item->purchase_description ?: $item->name);
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
            'ordered_qty' => '',
            'previously_received' => '',
            'rate' => '0.00',
            'amount' => '0.00',
            'taxable' => false,
            'class' => '',
            'purchase_order_line_id' => '',
        ];
    }

    public function save(ReceiveGoodsAction $create, UpdateGoodsReceiptAction $update): mixed
    {
        abort_unless(auth()->user()?->hasPermission('purchase.create'), 403);

        $this->validate([
            'number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('goods_receipts', 'number')->ignore($this->editingId),
            ],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'purchase_order_id' => ['required', 'exists:purchase_orders,id'],
            'receipt_date' => ['required', 'date'],
            'memo' => ['nullable', 'string', 'max:255'],
        ]);

        $po = PurchaseOrder::query()
            ->whereKey($this->purchase_order_id)
            ->where('vendor_id', $this->vendor_id)
            ->first();

        if (! $po) {
            $this->addError('purchase_order_id', 'Selected PO does not belong to this vendor.');
            $this->dispatch('be-toast', message: 'Selected PO does not belong to this vendor.');

            return null;
        }

        $filled = array_values(array_filter(
            $this->lines,
            fn (array $line) => filled($line['item_id'] ?? null)
                && filled($line['purchase_order_line_id'] ?? null)
                && (float) ($line['quantity'] ?? 0) > 0
        ));

        if ($filled === []) {
            $this->addError('lines', 'Add at least one PO line with receive qty.');
            $this->dispatch('be-toast', message: 'Add at least one PO line with receive qty.');

            return null;
        }

        $payload = array_map(fn (array $line) => [
            'item_id' => (int) $line['item_id'],
            'purchase_order_line_id' => (int) $line['purchase_order_line_id'],
            'quantity' => $line['quantity'],
            'unit_cost' => $line['rate'],
        ], $filled);

        try {
            if ($this->editingId) {
                $receipt = $update->handle(
                    GoodsReceipt::query()->findOrFail($this->editingId),
                    [
                        'number' => $this->number,
                        'vendor_id' => (int) $this->vendor_id,
                        'purchase_order_id' => (int) $this->purchase_order_id,
                        'receipt_date' => $this->receipt_date,
                        'memo' => $this->memo ?: null,
                        'updated_by' => auth()->id(),
                    ],
                    $payload
                );
            } else {
                $receipt = $create->handle([
                    'number' => $this->number,
                    'vendor_id' => (int) $this->vendor_id,
                    'purchase_order_id' => (int) $this->purchase_order_id,
                    'receipt_date' => $this->receipt_date,
                    'memo' => $this->memo ?: null,
                    'created_by' => auth()->id(),
                ], $payload);
            }
        } catch (\Throwable $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return null;
        }

        $this->dispatch('be-toast', message: 'Receipt '.$receipt->number.' saved against PO '.$po->number.'.');

        return $this->redirect(route('goods-receipts.index'), navigate: true);
    }

    public function render()
    {
        $poOptions = ['' => $this->vendor_id === '' ? 'Select vendor first…' : 'Select PO to receive…'];

        if ($this->vendor_id !== '') {
            $pos = PurchaseOrder::query()
                ->where('vendor_id', $this->vendor_id)
                ->when($this->editingId, function ($q) {
                    $q->where(function ($inner) {
                        $inner->whereIn('status', ['open', 'partial', 'ordered', 'sent', 'received'])
                            ->orWhere('id', $this->purchase_order_id);
                    });
                }, function ($q) {
                    $q->whereIn('status', ['open', 'partial', 'ordered', 'sent'])
                        ->whereHas('lines', function ($lq) {
                            $lq->whereColumn('qty_received', '<', 'quantity');
                        });
                })
                ->orderByDesc('order_date')
                ->orderByDesc('id')
                ->get();

            $poOptions += $pos->mapWithKeys(
                fn (PurchaseOrder $po) => [
                    $po->id => $po->number.' — '.number_format((float) $po->total, 2).' ('.$po->status.')',
                ]
            )->all();
        }

        return view('livewire.purchasing.goods-receipt-form', [
            'vendors' => Vendor::query()->active()->orderBy('display_name')->pluck('display_name', 'id')->all(),
            'poOptions' => $poOptions,
            'itemOptions' => ItemCatalog::optionsForLineItems($this->lines, purchase: true),
            'subtotal' => $this->linesSubtotal(),
        ])->layoutData([
            'title' => $this->editingId ? 'Edit Receive Inventory' : 'Receive Inventory',
            'windowTitle' => $this->editingId ? 'Edit Receive Inventory' : 'Receive Inventory',
        ]);
    }
}
