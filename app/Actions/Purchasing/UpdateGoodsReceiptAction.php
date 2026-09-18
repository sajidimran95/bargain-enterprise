<?php

namespace App\Actions\Purchasing;

use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptLine;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderLine;
use App\Services\InventoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RuntimeException;

class UpdateGoodsReceiptAction
{
    public function __construct(protected InventoryService $inventory) {}

    /**
     * @param  array<string, mixed>  $header
     * @param  array<int, array<string, mixed>>  $lines
     */
    public function handle(GoodsReceipt $receipt, array $header, array $lines): GoodsReceipt
    {
        $header = Validator::make($header, [
            'number' => [
                'required',
                'string',
                Rule::unique('goods_receipts', 'number')->ignore($receipt->id),
            ],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'purchase_order_id' => ['nullable', 'exists:purchase_orders,id'],
            'receipt_date' => ['required', 'date'],
            'memo' => ['nullable', 'string'],
            'updated_by' => ['nullable', 'exists:users,id'],
        ])->validate();

        return DB::transaction(function () use ($receipt, $header, $lines) {
            $receipt = GoodsReceipt::query()->whereKey($receipt->id)->lockForUpdate()->with('lines.item')->firstOrFail();

            $oldLines = $receipt->lines->map(fn (GoodsReceiptLine $line) => [
                'item' => $line->item,
                'quantity' => $line->quantity,
                'unit_cost' => $line->unit_cost,
                'purchase_order_line_id' => $line->purchase_order_line_id,
            ])->all();

            $newPrepared = [];
            foreach ($lines as $line) {
                $item = Item::query()->findOrFail($line['item_id']);
                $newPrepared[] = [
                    'item' => $item,
                    'quantity' => number_format((float) $line['quantity'], 4, '.', ''),
                    'unit_cost' => number_format((float) $line['unit_cost'], 4, '.', ''),
                    'purchase_order_line_id' => $line['purchase_order_line_id'] ?? null,
                ];
            }

            $this->inventory->syncPurchaseQuantities(
                $oldLines,
                $newPrepared,
                GoodsReceipt::class,
                (int) $receipt->id,
                [
                    'type' => 'receipt_edit',
                    'occurred_at' => $header['receipt_date'],
                    'created_by' => $header['updated_by'] ?? auth()->id(),
                    'memo' => 'Receipt edit '.$receipt->number,
                ]
            );

            // Reverse old PO received qty, then apply new.
            foreach ($oldLines as $old) {
                if (! empty($old['purchase_order_line_id'])) {
                    $this->adjustPoLineReceived((int) $old['purchase_order_line_id'], (string) $old['quantity'], false);
                }
            }
            foreach ($newPrepared as $new) {
                if (! empty($new['purchase_order_line_id'])) {
                    $this->adjustPoLineReceived((int) $new['purchase_order_line_id'], (string) $new['quantity'], true);
                }
            }

            $receipt->update([
                'number' => $header['number'],
                'vendor_id' => $header['vendor_id'],
                'purchase_order_id' => $header['purchase_order_id'] ?? null,
                'receipt_date' => $header['receipt_date'],
                'memo' => $header['memo'] ?? null,
            ]);

            $receipt->lines()->delete();
            foreach ($newPrepared as $line) {
                GoodsReceiptLine::query()->create([
                    'goods_receipt_id' => $receipt->id,
                    'item_id' => $line['item']->id,
                    'purchase_order_line_id' => $line['purchase_order_line_id'],
                    'quantity' => $line['quantity'],
                    'unit_cost' => $line['unit_cost'],
                ]);
            }

            $poId = $header['purchase_order_id'] ?? $receipt->purchase_order_id;
            if ($poId) {
                $this->refreshPoStatus((int) $poId);
            }

            return $receipt->load('lines');
        });
    }

    protected function adjustPoLineReceived(int $poLineId, string $qty, bool $increase): void
    {
        $poLine = PurchaseOrderLine::query()->lockForUpdate()->find($poLineId);
        if (! $poLine) {
            return;
        }

        if ($increase) {
            $poLine->qty_received = bcadd((string) $poLine->qty_received, $qty, 4);
            $poLine->save();

            $item = Item::query()->lockForUpdate()->find($poLine->item_id);
            if ($item?->tracksInventory()) {
                $reduce = $qty;
                if (bccomp((string) $item->on_po_qty, $reduce, 4) < 0) {
                    $reduce = (string) $item->on_po_qty;
                }
                $item->on_po_qty = bcsub((string) $item->on_po_qty, $reduce, 4);
                $item->save();
            }

            return;
        }

        $reverse = $qty;
        if (bccomp((string) $poLine->qty_received, $reverse, 4) < 0) {
            $reverse = (string) $poLine->qty_received;
        }
        $poLine->qty_received = bcsub((string) $poLine->qty_received, $reverse, 4);
        $poLine->save();

        $item = Item::query()->lockForUpdate()->find($poLine->item_id);
        if ($item?->tracksInventory()) {
            $item->on_po_qty = bcadd((string) $item->on_po_qty, $reverse, 4);
            $item->save();
        }
    }

    protected function refreshPoStatus(int $purchaseOrderId): void
    {
        $po = PurchaseOrder::query()->with('lines')->find($purchaseOrderId);
        if (! $po) {
            throw new RuntimeException('Purchase order not found.');
        }

        $allReceived = $po->lines->every(fn ($l) => bccomp((string) $l->qty_received, (string) $l->quantity, 4) >= 0);
        $anyReceived = $po->lines->contains(fn ($l) => bccomp((string) $l->qty_received, '0', 4) > 0);
        $po->status = $allReceived ? 'received' : ($anyReceived ? 'partial' : 'open');
        $po->save();
    }
}
