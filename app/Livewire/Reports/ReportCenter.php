<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithErpListActions;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\VendorBill;
use App\Support\ErpReportsCatalog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Reports')]
class ReportCenter extends Component
{
    use WithErpListActions;

    #[Url]
    public string $tab = 'standard';

    #[Url]
    public string $category = 'mfg-wholesale';

    public string $search = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);

        if (ErpReportsCatalog::category($this->category) === null) {
            $this->category = 'mfg-wholesale';
        }
    }

    public function selectCategory(string $id): void
    {
        if (ErpReportsCatalog::category($id) === null) {
            return;
        }

        $this->category = $id;
    }

    public function selectTab(string $tab): void
    {
        if (! in_array($tab, ['standard', 'memorized', 'favorites', 'recent', 'contributed'], true)) {
            return;
        }

        $this->tab = $tab;
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
        $categories = ErpReportsCatalog::sidebarCategories();
        $active = ErpReportsCatalog::category($this->category) ?? ErpReportsCatalog::category('mfg-wholesale');

        $groups = collect($active['groups'] ?? [])
            ->map(function (array $group) {
                $reports = collect($group['reports'] ?? [])
                    ->filter(function (array $report) {
                        if ($this->search === '') {
                            return true;
                        }

                        return str_contains(
                            mb_strtolower($report['label'] ?? ''),
                            mb_strtolower($this->search)
                        );
                    })
                    ->values()
                    ->all();

                return [
                    'title' => $group['title'] ?? null,
                    'reports' => $reports,
                ];
            })
            ->filter(fn (array $group) => $group['reports'] !== [])
            ->values()
            ->all();

        return view('livewire.reports.report-center', [
            'categories' => $categories,
            'activeCategory' => $active,
            'groups' => $groups,
            'tabs' => [
                'standard' => 'Standard',
                'memorized' => 'Memorized',
                'favorites' => 'Favorites',
                'recent' => 'Recent',
                'contributed' => 'Contributed',
            ],
        ])->layoutData([
            'title' => 'Reports',
            'windowTitle' => 'Report Center',
        ]);
    }
}
