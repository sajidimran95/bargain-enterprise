<?php

namespace App\Livewire\Purchasing;

use App\Livewire\Concerns\WithLineItems;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Models\Vendor;
use App\Services\ItemHistoryService;
use App\Support\DocumentNumbers;
use App\Support\ItemCatalog;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Create Purchase Order')]
class PurchaseOrderForm extends Component
{
    use WithLineItems;

    public string $number = '';

    public string $vendor_id = '';

    public string $order_date = '';

    public string $expected_date = '';

    public string $status = 'open';

    public string $memo = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('purchase.create'), 403);
        $this->number = DocumentNumbers::next(PurchaseOrder::class, 'number', 'PO-');
        $this->order_date = now()->toDateString();
        $this->expected_date = now()->addDays(7)->toDateString();
        $this->addLine();
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

    public function save(): mixed
    {
        abort_unless(auth()->user()?->hasPermission('purchase.create'), 403);

        $this->validate([
            'number' => ['required', 'string', 'max:50', 'unique:purchase_orders,number'],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'order_date' => ['required', 'date'],
            'expected_date' => ['nullable', 'date'],
            'memo' => ['nullable', 'string', 'max:255'],
        ]);

        $lines = $this->validatedLinePayload();
        $subtotal = $this->linesSubtotal();
        $costAlerts = [];

        DB::transaction(function () use ($lines, $subtotal, &$costAlerts) {
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

            $history = app(ItemHistoryService::class);

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

                $item = Item::query()->lockForUpdate()->find($line['item_id']);
                if (! $item) {
                    continue;
                }

                if ($item->tracksInventory()) {
                    $item->on_po_qty = bcadd((string) $item->on_po_qty, (string) $line['quantity'], 4);
                    $item->save();
                }

                $result = $history->syncPurchaseCostFromPo(
                    $item,
                    (string) $line['rate'],
                    $po,
                    (string) $line['quantity']
                );

                if ($result && $result['changed']) {
                    $costAlerts[] = [
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
            }
        });

        if ($costAlerts !== []) {
            session()->flash('item_cost_alerts', $costAlerts);
            $summary = collect($costAlerts)
                ->map(fn (array $a) => $a['sku'].' cost '.$a['direction'].' '.$a['old_cost'].'→'.$a['new_cost']
                    .($a['suggested_sales_price'] ? ' (suggest sales '.$a['suggested_sales_price'].')' : ''))
                ->implode('; ');
            $this->dispatch('be-toast', message: 'PO saved. Cost alert: '.$summary);
        } else {
            $this->dispatch('be-toast', message: 'Purchase order '.$this->number.' saved.');
        }

        return $this->redirect(route('purchase-orders.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.sales.document-form', [
            'pageTitle' => 'Create Purchase Order',
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
            'itemOptions' => ItemCatalog::selectOptions(purchase: true),
        ])->layoutData([
            'title' => 'Create Purchase Order',
            'windowTitle' => 'Create Purchase Order',
        ]);
    }
}
