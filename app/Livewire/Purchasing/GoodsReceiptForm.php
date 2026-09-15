<?php

namespace App\Livewire\Purchasing;

use App\Actions\Purchasing\ReceiveGoodsAction;
use App\Livewire\Concerns\WithLineItems;
use App\Models\GoodsReceipt;
use App\Models\Item;
use App\Models\Vendor;
use App\Support\DocumentNumbers;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Receive Inventory')]
class GoodsReceiptForm extends Component
{
    use WithLineItems;

    public string $number = '';

    public string $vendor_id = '';

    public string $receipt_date = '';

    public string $memo = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('purchase.create'), 403);
        $this->number = DocumentNumbers::next(GoodsReceipt::class, 'number', 'GR-');
        $this->receipt_date = now()->toDateString();
        $this->addLine();
    }

    protected function lineRateForItem(Item $item): float|string
    {
        return $item->purchase_cost ?: $item->average_cost ?: $item->sales_price;
    }

    public function save(ReceiveGoodsAction $action): mixed
    {
        abort_unless(auth()->user()?->hasPermission('purchase.create'), 403);

        $this->validate([
            'number' => ['required', 'string', 'max:50', 'unique:goods_receipts,number'],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'receipt_date' => ['required', 'date'],
            'memo' => ['nullable', 'string', 'max:255'],
        ]);

        $lines = $this->validatedLinePayload();

        try {
            $receipt = $action->handle([
                'number' => $this->number,
                'vendor_id' => (int) $this->vendor_id,
                'receipt_date' => $this->receipt_date,
                'memo' => $this->memo ?: null,
                'created_by' => auth()->id(),
            ], array_map(fn (array $line) => [
                'item_id' => $line['item_id'],
                'quantity' => $line['quantity'],
                'unit_cost' => $line['rate'],
            ], $lines));
        } catch (\Throwable $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return null;
        }

        $this->dispatch('be-toast', message: 'Receipt '.$receipt->number.' posted.');

        return $this->redirect(route('goods-receipts.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.sales.document-form', [
            'pageTitle' => 'Receive Inventory',
            'cancelRoute' => 'goods-receipts.index',
            'partyLabel' => 'Vendor',
            'partyOptions' => Vendor::query()->active()->orderBy('display_name')->pluck('display_name', 'id')->all(),
            'partyField' => 'vendor_id',
            'dateField' => 'receipt_date',
            'dateLabel' => 'Receipt Date',
            'numberField' => 'number',
            'numberLabel' => 'Receipt #',
            'rateLabel' => 'Unit Cost',
            'itemOptions' => Item::query()->active()->orderBy('sku')->get()
                ->mapWithKeys(fn (Item $i) => [$i->id => $i->sku.' — '.($i->purchase_description ?: $i->name)])
                ->all(),
        ])->layoutData([
            'title' => 'Receive Inventory',
            'windowTitle' => 'Receive Inventory',
        ]);
    }
}
