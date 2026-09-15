<?php

namespace App\Http\Controllers\Sales;

use App\Models\CreditMemo;
use App\Services\DocumentPdfService;
use Illuminate\Http\Response;

class CreditMemoPdfController
{
    public function __invoke(CreditMemo $creditMemo, DocumentPdfService $pdf): Response
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        $creditMemo->load(['customer', 'lines.item']);

        return $pdf->download(
            'pdf.credit-memo',
            ['creditMemo' => $creditMemo],
            'credit-memo-'.$creditMemo->credit_number.'.pdf'
        );
    }
}
