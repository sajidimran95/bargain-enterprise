<?php

namespace App\Services;

use App\Models\InventoryTransaction;
use App\Models\Item;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InventoryService
{
    /**
     * @param  array{type: string, qty_in?: float|string, qty_out?: float|string, unit_cost?: float|string, reference_type?: ?string, reference_id?: ?int, memo?: ?string, occurred_at?: mixed, created_by?: ?int, allow_negative?: bool}  $data
     */
    public function post(Item $item, array $data): InventoryTransaction
    {
        return DB::transaction(function () use ($item, $data) {
            $locked = Item::query()->whereKey($item->id)->lockForUpdate()->firstOrFail();

            $qtyIn = (string) ($data['qty_in'] ?? 0);
            $qtyOut = (string) ($data['qty_out'] ?? 0);
            $unitCost = (string) ($data['unit_cost'] ?? $locked->average_cost ?? 0);

            $delta = bcsub($qtyIn, $qtyOut, 4);
            $newBalance = bcadd((string) $locked->on_hand, $delta, 4);

            $policy = Setting::getValue('inventory.negative_policy', config('bargain.inventory.negative_policy', 'WARN'));
            $allowNegative = (bool) ($data['allow_negative'] ?? false);

            if (bccomp($newBalance, '0', 4) < 0 && $policy === 'BLOCK' && ! $allowNegative) {
                throw new RuntimeException("Negative inventory blocked for item [{$locked->sku}].");
            }

            if (bccomp($qtyIn, '0', 4) > 0) {
                $this->recalculateAverageCost($locked, $qtyIn, $unitCost);
            }

            $locked->on_hand = $newBalance;
            $locked->save();

            return InventoryTransaction::query()->create([
                'item_id' => $locked->id,
                'type' => $data['type'],
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'qty_in' => $qtyIn,
                'qty_out' => $qtyOut,
                'unit_cost' => $unitCost,
                'balance_after' => $newBalance,
                'created_by' => $data['created_by'] ?? auth()->id(),
                'memo' => $data['memo'] ?? null,
                'occurred_at' => $data['occurred_at'] ?? now(),
            ]);
        });
    }

    protected function recalculateAverageCost(Item $item, string $qtyIn, string $unitCost): void
    {
        $currentQty = (string) $item->on_hand;
        $currentAvg = (string) $item->average_cost;

        if (bccomp($currentQty, '0', 4) <= 0) {
            $item->average_cost = $unitCost;

            return;
        }

        $currentValue = bcmul($currentQty, $currentAvg, 4);
        $incomingValue = bcmul($qtyIn, $unitCost, 4);
        $newQty = bcadd($currentQty, $qtyIn, 4);

        if (bccomp($newQty, '0', 4) === 0) {
            $item->average_cost = 0;

            return;
        }

        $item->average_cost = bcdiv(bcadd($currentValue, $incomingValue, 4), $newQty, 4);
    }
}
