<?php

namespace App\Livewire\Dashboard;

use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\VendorBill;
use Livewire\Component;

class HomePlaceholder extends Component
{
    public function render()
    {
        return view('livewire.dashboard.home-placeholder', [
            'counts' => [
                'purchase_orders' => PurchaseOrder::query()->whereIn('status', ['open', 'partial', 'draft'])->count(),
                'vendor_bills' => VendorBill::query()->whereIn('status', ['open', 'partial'])->count(),
                'sales_orders' => SalesOrder::query()->whereIn('status', ['open', 'partial', 'draft'])->count(),
                'invoices' => Invoice::query()->whereIn('status', ['open', 'partial'])->count(),
            ],
        ]);
    }
}
