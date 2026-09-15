<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithErpListActions;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\VendorBill;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Reports')]
class ReportCenter extends Component
{
    use WithErpListActions;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);
    }

    public function exportCustomers(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('report.export'), 403);

        $rows = Customer::query()
            ->active()
            ->orderBy('display_name')
            ->get(['customer_number', 'display_name', 'email', 'phone', 'balance'])
            ->map(fn (Customer $customer) => [
                $customer->customer_number,
                $customer->display_name,
                $customer->email,
                $customer->phone,
                number_format((float) $customer->balance, 2, '.', ''),
            ]);

        return $this->csvDownload(
            'customer-directory.csv',
            ['Number', 'Name', 'Email', 'Phone', 'Balance'],
            $rows
        );
    }

    public function exportInventory(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('report.export'), 403);

        $rows = Item::query()
            ->active()
            ->orderBy('sku')
            ->get(['sku', 'name', 'on_hand', 'reorder_min', 'sales_price'])
            ->map(fn (Item $item) => [
                $item->sku,
                $item->name,
                number_format((float) $item->on_hand, 2, '.', ''),
                number_format((float) $item->reorder_min, 2, '.', ''),
                number_format((float) $item->sales_price, 2, '.', ''),
            ]);

        return $this->csvDownload(
            'inventory-stock.csv',
            ['SKU', 'Name', 'On Hand', 'Reorder Min', 'Sales Price'],
            $rows
        );
    }

    public function exportOpenAr(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('report.export'), 403);

        $rows = Invoice::query()
            ->with('customer')
            ->whereIn('status', ['open', 'partial'])
            ->orderByDesc('invoice_date')
            ->get()
            ->map(fn (Invoice $invoice) => [
                $invoice->invoice_date?->format('Y-m-d'),
                $invoice->invoice_number,
                $invoice->customer?->display_name,
                $invoice->status,
                number_format((float) $invoice->total, 2, '.', ''),
                number_format((float) $invoice->balance_due, 2, '.', ''),
            ]);

        return $this->csvDownload(
            'open-invoices-ar.csv',
            ['Date', 'Invoice #', 'Customer', 'Status', 'Total', 'Balance Due'],
            $rows
        );
    }

    public function exportOpenAp(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('report.export'), 403);

        $rows = VendorBill::query()
            ->with('vendor')
            ->whereIn('status', ['open', 'partial'])
            ->orderByDesc('bill_date')
            ->get()
            ->map(fn (VendorBill $bill) => [
                $bill->bill_date?->format('Y-m-d'),
                $bill->bill_number,
                $bill->vendor?->display_name,
                $bill->status,
                number_format((float) $bill->total, 2, '.', ''),
                number_format((float) $bill->balance_due, 2, '.', ''),
            ]);

        return $this->csvDownload(
            'open-ap.csv',
            ['Date', 'Bill #', 'Vendor', 'Status', 'Total', 'Balance Due'],
            $rows
        );
    }

    public function exportSalesThisMonth(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('report.export'), 403);

        $rows = Invoice::query()
            ->with('customer')
            ->whereBetween('invoice_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->whereNot('status', 'draft')
            ->orderByDesc('invoice_date')
            ->get()
            ->map(fn (Invoice $invoice) => [
                $invoice->invoice_date?->format('Y-m-d'),
                $invoice->invoice_number,
                $invoice->customer?->display_name,
                $invoice->status,
                number_format((float) $invoice->total, 2, '.', ''),
            ]);

        return $this->csvDownload(
            'sales-this-month.csv',
            ['Date', 'Invoice #', 'Customer', 'Status', 'Total'],
            $rows
        );
    }

    public function render()
    {
        $customerCount = Customer::query()->active()->count();
        $inventoryUnits = Item::query()->active()->sum('on_hand');
        $openAr = Invoice::query()->whereIn('status', ['open', 'partial'])->sum('balance_due');
        $openAp = VendorBill::query()->whereIn('status', ['open', 'partial'])->sum('balance_due');
        $salesThisMonth = Invoice::query()
            ->whereBetween('invoice_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->whereNot('status', 'draft')
            ->sum('total');

        return view('livewire.reports.report-center', [
            'customerCount' => $customerCount,
            'inventoryUnits' => $inventoryUnits,
            'openAr' => $openAr,
            'openAp' => $openAp,
            'salesThisMonth' => $salesThisMonth,
        ])->layoutData([
            'title' => 'Reports',
            'windowTitle' => 'Report Center',
        ]);
    }
}
