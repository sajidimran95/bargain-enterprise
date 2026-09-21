<?php

namespace App\Http\Controllers\Purchasing;

use App\Models\GoodsReceipt;
use Illuminate\Contracts\View\View;

class GoodsReceiptPrintController
{
    public function __invoke(GoodsReceipt $goodsReceipt): View
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);

        $goodsReceipt->load(['vendor', 'purchaseOrder', 'lines.item']);

        return view('print.goods-receipt', ['receipt' => $goodsReceipt]);
    }
}
