<?php

namespace App\Http\Controllers\Sales;

use App\Models\Invoice;
use Illuminate\Contracts\View\View;

class InvoicePrintController
{
    public function __invoke(Invoice $invoice): View
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        $invoice->load(['customer', 'lines.item']);

        return view('print.invoice', ['invoice' => $invoice]);
    }
}
