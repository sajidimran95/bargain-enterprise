<?php

namespace App\Http\Controllers\Sales;

use App\Models\Invoice;
use App\Services\DocumentPdfService;
use Illuminate\Http\Response;

class InvoicePdfController
{
    public function __invoke(Invoice $invoice, DocumentPdfService $pdf): Response
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        $invoice->load(['customer', 'lines.item']);

        return $pdf->download(
            'pdf.invoice',
            ['invoice' => $invoice],
            'invoice-'.$invoice->invoice_number.'.pdf'
        );
    }
}
