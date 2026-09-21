<?php

namespace App\Http\Controllers\Purchasing;

use App\Models\GoodsReceipt;
use App\Services\DocumentPdfService;
use Illuminate\Http\Response;

class GoodsReceiptPdfController
{
    public function __invoke(GoodsReceipt $goodsReceipt, DocumentPdfService $pdf): Response
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);

        $goodsReceipt->load(['vendor', 'purchaseOrder', 'lines.item']);

        return $pdf->download(
            'pdf.goods-receipt',
            ['receipt' => $goodsReceipt],
            'receipt-'.$goodsReceipt->number.'.pdf'
        );
    }
}
