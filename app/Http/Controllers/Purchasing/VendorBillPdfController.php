<?php

namespace App\Http\Controllers\Purchasing;

use App\Models\VendorBill;
use App\Services\DocumentPdfService;
use Illuminate\Http\Response;

class VendorBillPdfController
{
    public function __invoke(VendorBill $vendorBill, DocumentPdfService $pdf): Response
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);

        $vendorBill->load(['vendor', 'lines.item']);

        return $pdf->download(
            'pdf.vendor-bill',
            ['bill' => $vendorBill],
            'bill-'.$vendorBill->bill_number.'.pdf'
        );
    }
}
