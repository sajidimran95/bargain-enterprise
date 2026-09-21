<?php

namespace App\Http\Controllers\Sales;

use App\Models\CreditMemo;
use Illuminate\Contracts\View\View;

class CreditMemoPrintController
{
    public function __invoke(CreditMemo $creditMemo): View
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        $creditMemo->load(['customer', 'lines.item']);

        return view('print.credit-memo', ['creditMemo' => $creditMemo]);
    }
}
