<?php

namespace App\Http\Controllers\Purchasing;

use App\Models\PurchaseOrder;
use App\Services\DocumentPdfService;
use Illuminate\Http\Response;

class PurchaseOrderPdfController
{
    public function __invoke(PurchaseOrder $purchaseOrder, DocumentPdfService $pdf): Response
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);

        $purchaseOrder->load(['vendor', 'lines.item']);

        return $pdf->download(
            'pdf.purchase-order',
            ['order' => $purchaseOrder],
            'po-'.$purchaseOrder->number.'.pdf'
        );
    }
}
