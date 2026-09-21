<?php

namespace App\Http\Controllers\Purchasing;

use App\Models\VendorBill;
use Illuminate\Contracts\View\View;

class VendorBillPrintController
{
    public function __invoke(VendorBill $vendorBill): View
    {
        abort_unless(auth()->user()?->hasPermission('purchase.view'), 403);

        $vendorBill->load(['vendor', 'lines.item']);

        return view('print.vendor-bill', ['bill' => $vendorBill]);
    }
}
