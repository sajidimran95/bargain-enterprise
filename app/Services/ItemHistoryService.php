<?php

namespace App\Services;

use App\Models\InventoryTransaction;
use App\Models\Item;
use App\Models\ItemHistory;
use App\Models\PurchaseOrder;

class ItemHistoryService
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function record(Item $item, string $event, array $attributes = []): ItemHistory
    {
        return ItemHistory::query()->create([
            'item_id' => $item->id,
            'event' => $event,
            'qty_in' => $attributes['qty_in'] ?? 0,
            'qty_out' => $attributes['qty_out'] ?? 0,
            'balance_after' => $attributes['balance_after'] ?? $item->on_hand,
            'old_value' => $attributes['old_value'] ?? null,
            'new_value' => $attributes['new_value'] ?? null,
            'unit_cost' => $attributes['unit_cost'] ?? null,
            'suggested_sales_price' => $attributes['suggested_sales_price'] ?? null,
            'reference_type' => $attributes['reference_type'] ?? null,
            'reference_id' => $attributes['reference_id'] ?? null,
            'created_by' => $attributes['created_by'] ?? auth()->id(),
            'memo' => $attributes['memo'] ?? null,
            'occurred_at' => $attributes['occurred_at'] ?? now(),
        ]);
    }

    public function recordFromInventoryTransaction(Item $item, InventoryTransaction $tx): ItemHistory
    {
        $qtyIn = (string) $tx->qty_in;
        $qtyOut = (string) $tx->qty_out;
        $event = bccomp($qtyIn, $qtyOut, 4) >= 0 && bccomp($qtyIn, '0', 4) > 0
            ? ItemHistory::EVENT_STOCK_IN
            : ItemHistory::EVENT_STOCK_OUT;

        return $this->record($item, $event, [
            'qty_in' => $qtyIn,
            'qty_out' => $qtyOut,
            'balance_after' => $tx->balance_after,
            'unit_cost' => $tx->unit_cost,
            'reference_type' => $tx->reference_type,
            'reference_id' => $tx->reference_id,
            'created_by' => $tx->created_by,
            'memo' => $tx->memo ?: ucfirst(str_replace('_', ' ', $tx->type)),
            'occurred_at' => $tx->occurred_at ?? now(),
        ]);
    }

    /**
     * Update item purchase cost when PO rate differs; log history and return alert payload.
     *
     * @return array{changed: bool, old_cost: string, new_cost: string, suggested_sales_price: ?string, direction: ?string, history: ?ItemHistory}|null
     */
    public function syncPurchaseCostFromPo(Item $item, string $poRate, PurchaseOrder $po, string $qty): ?array
    {
        $oldCost = number_format((float) $item->purchase_cost, 2, '.', '');
        $newCost = number_format((float) $poRate, 2, '.', '');

        $this->record($item, ItemHistory::EVENT_PO_ORDERED, [
            'qty_in' => 0,
            'qty_out' => 0,
            'balance_after' => $item->on_hand,
            'unit_cost' => $newCost,
            'old_value' => $oldCost,
            'new_value' => $newCost,
            'reference_type' => PurchaseOrder::class,
            'reference_id' => $po->id,
            'memo' => 'PO '.$po->number.' ordered qty '.$qty.' @ '.$newCost,
            'occurred_at' => $po->order_date?->toDateTimeString() ?? now(),
        ]);

        if (bccomp($oldCost, $newCost, 2) === 0) {
            return [
                'changed' => false,
                'old_cost' => $oldCost,
                'new_cost' => $newCost,
                'suggested_sales_price' => null,
                'direction' => null,
                'history' => null,
            ];
        }

        $direction = bccomp($newCost, $oldCost, 2) > 0 ? 'increased' : 'decreased';
        $suggested = $this->suggestSalesPrice(
            number_format((float) $item->sales_price, 2, '.', ''),
            $oldCost,
            $newCost
        );

        $item->purchase_cost = $newCost;
        $item->save();

        $history = $this->record($item, ItemHistory::EVENT_PURCHASE_COST, [
            'balance_after' => $item->on_hand,
            'old_value' => $oldCost,
            'new_value' => $newCost,
            'unit_cost' => $newCost,
            'suggested_sales_price' => $suggested,
            'reference_type' => PurchaseOrder::class,
            'reference_id' => $po->id,
            'memo' => 'PO '.$po->number.' cost '.$direction.' from '.$oldCost.' to '.$newCost
                .($suggested !== null ? ' — review sales price (suggest '.$suggested.')' : ' — review sales price'),
            'occurred_at' => now(),
        ]);

        return [
            'changed' => true,
            'old_cost' => $oldCost,
            'new_cost' => $newCost,
            'suggested_sales_price' => $suggested,
            'direction' => $direction,
            'history' => $history,
        ];
    }

    public function recordSalesPriceChange(Item $item, string $oldPrice, string $newPrice, ?string $memo = null): ?ItemHistory
    {
        $oldPrice = number_format((float) $oldPrice, 2, '.', '');
        $newPrice = number_format((float) $newPrice, 2, '.', '');

        if (bccomp($oldPrice, $newPrice, 2) === 0) {
            return null;
        }

        return $this->record($item, ItemHistory::EVENT_SALES_PRICE, [
            'balance_after' => $item->on_hand,
            'old_value' => $oldPrice,
            'new_value' => $newPrice,
            'memo' => $memo ?: 'Sales price changed from '.$oldPrice.' to '.$newPrice,
            'occurred_at' => now(),
        ]);
    }

    public function recordPurchaseCostManualChange(Item $item, string $oldCost, string $newCost): ?ItemHistory
    {
        $oldCost = number_format((float) $oldCost, 2, '.', '');
        $newCost = number_format((float) $newCost, 2, '.', '');

        if (bccomp($oldCost, $newCost, 2) === 0) {
            return null;
        }

        $direction = bccomp($newCost, $oldCost, 2) > 0 ? 'increased' : 'decreased';
        $suggested = $this->suggestSalesPrice(
            number_format((float) $item->sales_price, 2, '.', ''),
            $oldCost,
            $newCost
        );

        return $this->record($item, ItemHistory::EVENT_PURCHASE_COST, [
            'balance_after' => $item->on_hand,
            'old_value' => $oldCost,
            'new_value' => $newCost,
            'unit_cost' => $newCost,
            'suggested_sales_price' => $suggested,
            'memo' => 'Purchase cost '.$direction.' from '.$oldCost.' to '.$newCost
                .($suggested !== null ? ' — review sales price (suggest '.$suggested.')' : ''),
            'occurred_at' => now(),
        ]);
    }

    public function suggestSalesPrice(string $currentSalesPrice, string $oldCost, string $newCost): ?string
    {
        if (bccomp($oldCost, '0', 2) <= 0) {
            $delta = bcsub($newCost, $oldCost, 2);

            return number_format((float) bcadd($currentSalesPrice, $delta, 2), 2, '.', '');
        }

        $ratio = bcdiv($newCost, $oldCost, 6);
        $suggested = bcmul($currentSalesPrice, $ratio, 2);

        if (bccomp($suggested, $currentSalesPrice, 2) === 0) {
            return null;
        }

        return number_format((float) $suggested, 2, '.', '');
    }
}
