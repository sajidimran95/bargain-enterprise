<?php

namespace App\Services;

use App\Models\InventoryTransaction;
use App\Models\Item;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InventoryService
{
    public function __construct(protected ItemHistoryService $history) {}

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

            $tx = InventoryTransaction::query()->create([
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

            $this->history->recordFromInventoryTransaction($locked->fresh(), $tx);

            return $tx;
        });
    }

    /**
     * Sync sale quantities for a document by posting only the net delta per item.
     * Positive delta = additional qty_out (sale); negative = qty_in (return to stock).
     *
     * @param  iterable<int, array{item: Item, quantity: float|string}>  $oldLines
     * @param  iterable<int, array{item: Item, quantity: float|string}>  $newLines
     * @param  array{occurred_at?: mixed, created_by?: ?int, allow_negative?: bool, memo?: ?string, type?: string}  $meta
     */
    public function syncSaleQuantities(
        iterable $oldLines,
        iterable $newLines,
        string $referenceType,
        int $referenceId,
        array $meta = [],
    ): void {
        $oldByItem = $this->aggregateInventoryQtyByItem($oldLines);
        $newByItem = $this->aggregateInventoryQtyByItem($newLines);
        $itemIds = array_unique([...array_keys($oldByItem), ...array_keys($newByItem)]);

        foreach ($itemIds as $itemId) {
            $oldQty = $oldByItem[$itemId] ?? '0.0000';
            $newQty = $newByItem[$itemId] ?? '0.0000';
            $delta = bcsub($newQty, $oldQty, 4);

            if (bccomp($delta, '0', 4) === 0) {
                continue;
            }

            $item = Item::query()->findOrFail($itemId);
            if (! $item->tracksInventory()) {
                continue;
            }

            $payload = [
                'type' => $meta['type'] ?? 'invoice_edit',
                'unit_cost' => $item->average_cost,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'occurred_at' => $meta['occurred_at'] ?? now(),
                'created_by' => $meta['created_by'] ?? auth()->id(),
                'allow_negative' => (bool) ($meta['allow_negative'] ?? false),
                'memo' => $meta['memo'] ?? null,
            ];

            if (bccomp($delta, '0', 4) > 0) {
                $payload['qty_out'] = $delta;
            } else {
                $payload['qty_in'] = bcmul($delta, '-1', 4);
            }

            $this->post($item, $payload);
        }
    }

    /**
     * Adjust on_so_qty commitment (does not change on_hand).
     *
     * @param  iterable<int, array{item: Item, quantity: float|string}|array{item_id: int, quantity: float|string}>  $oldLines
     * @param  iterable<int, array{item: Item, quantity: float|string}|array{item_id: int, quantity: float|string}>  $newLines
     */
    public function syncOnSoQty(iterable $oldLines, iterable $newLines): void
    {
        $oldByItem = $this->aggregateInventoryQtyByItem($oldLines);
        $newByItem = $this->aggregateInventoryQtyByItem($newLines);
        $itemIds = array_unique([...array_keys($oldByItem), ...array_keys($newByItem)]);

        foreach ($itemIds as $itemId) {
            $oldQty = $oldByItem[$itemId] ?? '0.0000';
            $newQty = $newByItem[$itemId] ?? '0.0000';
            $delta = bcsub($newQty, $oldQty, 4);

            if (bccomp($delta, '0', 4) === 0) {
                continue;
            }

            $locked = Item::query()->whereKey($itemId)->lockForUpdate()->firstOrFail();
            if (! $locked->tracksInventory()) {
                continue;
            }

            $locked->on_so_qty = bcadd((string) $locked->on_so_qty, $delta, 4);
            if (bccomp((string) $locked->on_so_qty, '0', 4) < 0) {
                $locked->on_so_qty = '0.0000';
            }
            $locked->save();
        }
    }

    /**
     * Adjust on_po_qty commitment (does not change on_hand).
     *
     * @param  iterable<int, array{item: Item, quantity: float|string}|array{item_id: int, quantity: float|string}>  $oldLines
     * @param  iterable<int, array{item: Item, quantity: float|string}|array{item_id: int, quantity: float|string}>  $newLines
     */
    public function syncOnPoQty(iterable $oldLines, iterable $newLines): void
    {
        $oldByItem = $this->aggregateInventoryQtyByItem($oldLines);
        $newByItem = $this->aggregateInventoryQtyByItem($newLines);
        $itemIds = array_unique([...array_keys($oldByItem), ...array_keys($newByItem)]);

        foreach ($itemIds as $itemId) {
            $oldQty = $oldByItem[$itemId] ?? '0.0000';
            $newQty = $newByItem[$itemId] ?? '0.0000';
            $delta = bcsub($newQty, $oldQty, 4);

            if (bccomp($delta, '0', 4) === 0) {
                continue;
            }

            $locked = Item::query()->whereKey($itemId)->lockForUpdate()->firstOrFail();
            if (! $locked->tracksInventory()) {
                continue;
            }

            $locked->on_po_qty = bcadd((string) $locked->on_po_qty, $delta, 4);
            if (bccomp((string) $locked->on_po_qty, '0', 4) < 0) {
                $locked->on_po_qty = '0.0000';
            }
            $locked->save();
        }
    }

    /**
     * Sync purchase receipt quantities (qty_in). Positive delta = more received; negative = reverse stock in.
     *
     * @param  iterable<int, array{item: Item, quantity: float|string, unit_cost?: float|string}>  $oldLines
     * @param  iterable<int, array{item: Item, quantity: float|string, unit_cost?: float|string}>  $newLines
     * @param  array{occurred_at?: mixed, created_by?: ?int, allow_negative?: bool, memo?: ?string, type?: string}  $meta
     */
    public function syncPurchaseQuantities(
        iterable $oldLines,
        iterable $newLines,
        string $referenceType,
        int $referenceId,
        array $meta = [],
    ): void {
        $oldByItem = $this->aggregateInventoryQtyByItem($oldLines);
        $newByItem = $this->aggregateInventoryQtyByItem($newLines);
        $costs = [];
        foreach ($newLines as $line) {
            $item = $line['item'] ?? null;
            $itemId = $item instanceof Item ? (int) $item->id : (int) ($line['item_id'] ?? 0);
            if ($itemId > 0 && isset($line['unit_cost'])) {
                $costs[$itemId] = (string) $line['unit_cost'];
            }
        }

        $itemIds = array_unique([...array_keys($oldByItem), ...array_keys($newByItem)]);

        foreach ($itemIds as $itemId) {
            $oldQty = $oldByItem[$itemId] ?? '0.0000';
            $newQty = $newByItem[$itemId] ?? '0.0000';
            $delta = bcsub($newQty, $oldQty, 4);

            if (bccomp($delta, '0', 4) === 0) {
                continue;
            }

            $item = Item::query()->findOrFail($itemId);
            if (! $item->tracksInventory()) {
                continue;
            }

            $payload = [
                'type' => $meta['type'] ?? 'receipt_edit',
                'unit_cost' => $costs[$itemId] ?? $item->average_cost,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'occurred_at' => $meta['occurred_at'] ?? now(),
                'created_by' => $meta['created_by'] ?? auth()->id(),
                'allow_negative' => (bool) ($meta['allow_negative'] ?? false),
                'memo' => $meta['memo'] ?? null,
            ];

            if (bccomp($delta, '0', 4) > 0) {
                $payload['qty_in'] = $delta;
            } else {
                $payload['qty_out'] = bcmul($delta, '-1', 4);
            }

            $this->post($item, $payload);
        }
    }

    /**
     * @param  iterable<int, array{item?: Item, item_id?: int, quantity: float|string}>  $lines
     * @return array<int, string>
     */
    protected function aggregateInventoryQtyByItem(iterable $lines): array
    {
        $totals = [];

        foreach ($lines as $line) {
            $item = $line['item'] ?? null;
            $itemId = $item instanceof Item ? (int) $item->id : (int) ($line['item_id'] ?? 0);
            if ($itemId <= 0) {
                continue;
            }

            if ($item instanceof Item && ! $item->tracksInventory()) {
                continue;
            }

            if (! $item instanceof Item) {
                $item = Item::query()->find($itemId);
                if (! $item || ! $item->tracksInventory()) {
                    continue;
                }
            }

            $qty = number_format((float) $line['quantity'], 4, '.', '');
            $totals[$itemId] = bcadd($totals[$itemId] ?? '0.0000', $qty, 4);
        }

        return $totals;
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
