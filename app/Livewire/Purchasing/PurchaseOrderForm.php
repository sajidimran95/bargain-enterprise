<?php

namespace App\Livewire\Purchasing;

use App\Livewire\Concerns\WithLineItems;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\Vendor;
use App\Services\InventoryService;
use App\Services\ItemHistoryService;
use App\Support\DocumentNumbers;
use App\Support\ItemCatalog;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Create Purchase Order')]
class PurchaseOrderForm extends Component
{
    use WithLineItems;

    public ?int $editingId = null;

    public string $number = '';

    public string $vendor_id = '';

    public string $order_date = '';

    public string $expected_date = '';

    public string $status = 'open';

    public string $memo = '';

    public function mount(?PurchaseOrder $purchaseOrder = null): void
    {
        abort_unless(auth()->user()?->hasPermission('purchase.create'), 403);

        if ($purchaseOrder?->exists) {
            $this->loadOrder($purchaseOrder);

            return;
        }

        $this->number = DocumentNumbers::next(PurchaseOrder::class, 'number', 'PO-');
        $this->order_date = now()->toDateString();
        $this->expected_date = now()->addDays(7)->toDateString();
        $this->addLine();
    }

    public function loadOrder(PurchaseOrder $order): void
    {
        $order->loadMissing('lines.item');
        if (in_array($order->status, ['received', 'cancelled'], true)) {
            abort(403, 'This purchase order cannot be edited.');
        }

        $this->editingId = (int) $order->id;
        $this->number = (string) $order->number;
        $this->vendor_id = (string) $order->vendor_id;
        $this->order_date = $order->order_date?->toDateString() ?: now()->toDateString();
        $this->expected_date = $order->expected_date?->toDateString() ?: '';
        $this->status = (string) $order->status;
        $this->memo = (string) ($order->memo ?? '');
        $this->lines = [];
        foreach ($order->lines as $line) {
            $this->lines[] = [
                'item_id' => (string) $line->item_id,
                'item_code' => $line->item?->barcode ?: $line->item?->sku ?: '',
                'description' => (string) ($line->description ?? ''),
                'quantity' => number_format((float) $line->quantity, 2, '.', ''),
                'rate' => number_format((float) $line->rate, 2, '.', ''),
                'amount' => number_format((float) $line->amount, 2, '.', ''),
                'taxable' => true,
                'class' => '',
            ];
        }
        if ($this->lines === []) {
            $this->addLine();
        }
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

    public function save(InventoryService $inventory): mixed
    {
        abort_unless(auth()->user()?->hasPermission('purchase.create'), 403);

        $this->validate([
            'number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('purchase_orders', 'number')->ignore($this->editingId),
            ],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'order_date' => ['required', 'date'],
            'expected_date' => ['nullable', 'date'],
            'memo' => ['nullable', 'string', 'max:255'],
        ]);

        $lines = $this->validatedLinePayload();
        $subtotal = $this->linesSubtotal();
        $costAlerts = [];

        try {
            DB::transaction(function () use ($lines, $subtotal, $inventory, &$costAlerts) {
                $history = app(ItemHistoryService::class);

                if ($this->editingId) {
                    $po = PurchaseOrder::query()->whereKey($this->editingId)->lockForUpdate()->with('lines.item')->firstOrFail();
                    if (in_array($po->status, ['received', 'cancelled'], true)) {
                        throw new \RuntimeException('This purchase order cannot be edited.');
                    }

                    $hasReceived = $po->lines->contains(fn (PurchaseOrderLine $l) => bccomp((string) $l->qty_received, '0', 4) > 0);
                    if ($hasReceived) {
                        throw new \RuntimeException('Cannot edit a PO that already has received quantities. Void/adjust via receive instead.');
                    }

                    $oldLines = $po->lines->map(fn (PurchaseOrderLine $line) => [
                        'item' => $line->item,
                        'quantity' => $line->quantity,
                    ])->all();
                    $newLines = collect($lines)->map(fn (array $line) => [
                        'item' => Item::query()->findOrFail($line['item_id']),
                        'quantity' => $line['quantity'],
                    ])->all();

                    $inventory->syncOnPoQty($oldLines, $newLines);

                    $po->update([
                        'number' => $this->number,
                        'vendor_id' => (int) $this->vendor_id,
                        'order_date' => $this->order_date,
                        'expected_date' => $this->expected_date ?: null,
                        'status' => $po->status === 'draft' ? 'open' : $po->status,
                        'subtotal' => $subtotal,
                        'total' => $subtotal,
                        'memo' => $this->memo ?: null,
                    ]);

                    $po->lines()->delete();
                    foreach ($lines as $line) {
                        PurchaseOrderLine::query()->create([
                            'purchase_order_id' => $po->id,
                            'item_id' => $line['item_id'],
                            'description' => $line['description'] ?? null,
                            'quantity' => $line['quantity'],
                            'qty_received' => 0,
                            'rate' => $line['rate'],
                            'amount' => number_format((float) $line['quantity'] * (float) $line['rate'], 2, '.', ''),
                        ]);

                        $item = Item::query()->find($line['item_id']);
                        if ($item) {
                            $result = $history->syncPurchaseCostFromPo($item, (string) $line['rate'], $po, (string) $line['quantity']);
                            if ($result && $result['changed']) {
                                $costAlerts[] = $this->costAlertPayload($item, $result);
                            }
                        }
                    }

                    return;
                }

                $po = PurchaseOrder::query()->create([
                    'number' => $this->number,
                    'vendor_id' => (int) $this->vendor_id,
                    'order_date' => $this->order_date,
                    'expected_date' => $this->expected_date ?: null,
                    'status' => 'open',
                    'subtotal' => $subtotal,
                    'total' => $subtotal,
                    'memo' => $this->memo ?: null,
                ]);

                $newLines = [];
                foreach ($lines as $line) {
                    PurchaseOrderLine::query()->create([
                        'purchase_order_id' => $po->id,
                        'item_id' => $line['item_id'],
                        'description' => $line['description'] ?? null,
                        'quantity' => $line['quantity'],
                        'qty_received' => 0,
                        'rate' => $line['rate'],
                        'amount' => number_format((float) $line['quantity'] * (float) $line['rate'], 2, '.', ''),
                    ]);

                    $item = Item::query()->findOrFail($line['item_id']);
                    $newLines[] = ['item' => $item, 'quantity' => $line['quantity']];

                    $result = $history->syncPurchaseCostFromPo($item, (string) $line['rate'], $po, (string) $line['quantity']);
                    if ($result && $result['changed']) {
                        $costAlerts[] = $this->costAlertPayload($item, $result);
                    }
                }

                $inventory->syncOnPoQty([], $newLines);
            });
        } catch (\Throwable $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return null;
        }

        if ($costAlerts !== []) {
            session()->flash('item_cost_alerts', $costAlerts);
            $summary = collect($costAlerts)
                ->map(fn (array $a) => $a['sku'].' cost '.$a['direction'].' '.$a['old_cost'].'→'.$a['new_cost'])
                ->implode('; ');
            $this->dispatch('be-toast', message: 'PO saved. Cost alert: '.$summary);
        } else {
            $this->dispatch('be-toast', message: 'Purchase order '.$this->number.' saved.');
        }

        return $this->redirect(route('purchase-orders.index'), navigate: true);
    }

    /**
     * @param  array{direction: string, old_cost: string, new_cost: string, suggested_sales_price: ?string}  $result
     * @return array<string, mixed>
     */
    protected function costAlertPayload(Item $item, array $result): array
    {
        return [
            'item_id' => $item->id,
            'sku' => $item->sku,
            'name' => $item->name,
            'direction' => $result['direction'],
            'old_cost' => $result['old_cost'],
            'new_cost' => $result['new_cost'],
            'sales_price' => number_format((float) $item->sales_price, 2, '.', ''),
            'suggested_sales_price' => $result['suggested_sales_price'],
        ];
    }

    public function render()
    {
        return view('livewire.sales.document-form', [
            'pageTitle' => $this->editingId ? 'Edit Purchase Order' : 'Create Purchase Order',
            'cancelRoute' => 'purchase-orders.index',
            'partyLabel' => 'Vendor',
            'partyOptions' => Vendor::query()->active()->orderBy('display_name')->pluck('display_name', 'id')->all(),
            'partyField' => 'vendor_id',
            'dateField' => 'order_date',
            'dateLabel' => 'Order Date',
            'numberField' => 'number',
            'numberLabel' => 'PO #',
            'showExpected' => true,
            'rateLabel' => 'Cost',
            'itemOptions' => ItemCatalog::optionsForLineItems($this->lines, purchase: true),
        ])->layoutData([
            'title' => $this->editingId ? 'Edit Purchase Order' : 'Create Purchase Order',
            'windowTitle' => $this->editingId ? 'Edit Purchase Order' : 'Create Purchase Order',
        ]);
    }
}
