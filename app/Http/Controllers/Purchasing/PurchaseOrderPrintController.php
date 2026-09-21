<?php

namespace App\Http\Controllers\Purchasing;

use App\Models\PurchaseOrder;
use Illuminate\Contracts\View\View;

class PurchaseOrderPrintController
{
    public function __invoke(PurchaseOrder $purchaseOrder): View
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);

        $purchaseOrder->load(['vendor', 'lines.item']);

        return view('print.purchase-order', ['order' => $purchaseOrder]);
    }
}
