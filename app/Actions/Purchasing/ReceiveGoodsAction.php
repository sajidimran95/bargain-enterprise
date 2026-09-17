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

class ReceiveGoodsAction
{
    public function __construct(protected InventoryService $inventory) {}

    /**
     * @param  array<string, mixed>  $header
     * @param  array<int, array<string, mixed>>  $lines
     */
    public function handle(array $header, array $lines): GoodsReceipt
    {
        $header = Validator::make($header, [
            'number' => ['required', 'string', 'unique:goods_receipts,number'],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'purchase_order_id' => ['nullable', 'exists:purchase_orders,id'],
            'receipt_date' => ['required', 'date'],
            'memo' => ['nullable', 'string'],
            'created_by' => ['nullable', 'exists:users,id'],
        ])->validate();

        return DB::transaction(function () use ($header, $lines) {
            $receipt = GoodsReceipt::query()->create([
                'number' => $header['number'],
                'vendor_id' => $header['vendor_id'],
                'purchase_order_id' => $header['purchase_order_id'] ?? null,
                'receipt_date' => $header['receipt_date'],
                'memo' => $header['memo'] ?? null,
                'created_by' => $header['created_by'] ?? auth()->id(),
            ]);

            foreach ($lines as $line) {
                $qty = number_format((float) $line['quantity'], 4, '.', '');
                $cost = number_format((float) $line['unit_cost'], 4, '.', '');

                GoodsReceiptLine::query()->create([
                    'goods_receipt_id' => $receipt->id,
                    'item_id' => $line['item_id'],
                    'purchase_order_line_id' => $line['purchase_order_line_id'] ?? null,
                    'quantity' => $qty,
                    'unit_cost' => $cost,
                ]);

                $item = Item::query()->findOrFail($line['item_id']);

                if ($item->tracksInventory()) {
                    $this->inventory->post($item, [
                        'type' => 'purchase',
                        'qty_in' => $qty,
                        'unit_cost' => $cost,
                        'reference_type' => GoodsReceipt::class,
                        'reference_id' => $receipt->id,
                        'occurred_at' => $header['receipt_date'],
                        'created_by' => $header['created_by'] ?? auth()->id(),
                        'memo' => 'Receipt '.$receipt->number,
                    ]);
                }

                if (! empty($line['purchase_order_line_id'])) {
                    $poLine = PurchaseOrderLine::query()->lockForUpdate()->find($line['purchase_order_line_id']);
                    if ($poLine) {
                        $poLine->qty_received = bcadd((string) $poLine->qty_received, $qty, 4);
                        $poLine->save();

                        if ($item->tracksInventory()) {
                            $lockedItem = Item::query()->lockForUpdate()->findOrFail($item->id);
                            $reducePo = $qty;
                            if (bccomp((string) $lockedItem->on_po_qty, $reducePo, 4) < 0) {
                                $reducePo = (string) $lockedItem->on_po_qty;
                            }
                            $lockedItem->on_po_qty = bcsub((string) $lockedItem->on_po_qty, $reducePo, 4);
                            $lockedItem->save();
                        }
                    }
                }
            }

            if (! empty($header['purchase_order_id'])) {
                $po = PurchaseOrder::query()->with('lines')->find($header['purchase_order_id']);
                if ($po) {
                    $allReceived = $po->lines->every(fn ($l) => bccomp((string) $l->qty_received, (string) $l->quantity, 4) >= 0);
                    $anyReceived = $po->lines->contains(fn ($l) => bccomp((string) $l->qty_received, '0', 4) > 0);
                    $po->status = $allReceived ? 'received' : ($anyReceived ? 'partial' : $po->status);
                    $po->save();
                }
            }

            return $receipt->load('lines');
        });
    }
}
