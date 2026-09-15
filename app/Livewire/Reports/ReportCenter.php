<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithErpListActions;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Setting;
use App\Models\VendorBill;
use App\Support\ErpReportsCatalog;
use App\Support\QbDatePresets;
use App\Support\ReportCenterPreview;
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

    /** @var array<string, string> */
    public array $datePresets = [];

    /** @var array<string, string> */
    public array $customFrom = [];

    /** @var array<string, string> */
    public array $customTo = [];

    /** @var list<string> */
    public array $favoriteKeys = [];

    /** @var list<string> */
    public array $memorizedKeys = [];

    /** @var list<string> */
    public array $recentKeys = [];

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);

        if (ErpReportsCatalog::category($this->category) === null) {
            $this->category = 'mfg-wholesale';
        }

        $userId = (int) auth()->id();
        $this->favoriteKeys = array_values(array_filter(
            Setting::getValue($this->settingKey('favorites', $userId), []) ?: []
        ));
        $this->memorizedKeys = array_values(array_filter(
            Setting::getValue($this->settingKey('memorized', $userId), []) ?: []
        ));
        $this->recentKeys = array_values(array_filter(
            Setting::getValue($this->settingKey('recent', $userId), []) ?: []
        ));
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

    public function setDatePreset(string $key, string $preset): void
    {
        if (! array_key_exists($preset, QbDatePresets::options())) {
            return;
        }

        $this->datePresets[$key] = $preset;

        if ($preset !== 'custom') {
            [$from, $to] = QbDatePresets::dateStrings($preset);
            $this->customFrom[$key] = $from;
            $this->customTo[$key] = $to;
        } elseif (! isset($this->customFrom[$key], $this->customTo[$key])) {
            [$from, $to] = QbDatePresets::dateStrings('this_fiscal_ytd');
            $this->customFrom[$key] = $from;
            $this->customTo[$key] = $to;
        }
    }

    public function setCustomFrom(string $key, string $value): void
    {
        $this->datePresets[$key] = 'custom';
        $this->customFrom[$key] = $value !== '' ? $value : ($this->customFrom[$key] ?? now()->toDateString());
    }

    public function setCustomTo(string $key, string $value): void
    {
        $this->datePresets[$key] = 'custom';
        $this->customTo[$key] = $value !== '' ? $value : ($this->customTo[$key] ?? now()->toDateString());
    }

    public function toggleFavorite(string $key): void
    {
        if (in_array($key, $this->favoriteKeys, true)) {
            $this->favoriteKeys = array_values(array_filter(
                $this->favoriteKeys,
                fn (string $existing) => $existing !== $key
            ));
        } else {
            $this->favoriteKeys[] = $key;
        }

        $this->persistList('favorites', $this->favoriteKeys);
    }

    public function toggleMemorize(string $key): void
    {
        if (in_array($key, $this->memorizedKeys, true)) {
            $this->memorizedKeys = array_values(array_filter(
                $this->memorizedKeys,
                fn (string $existing) => $existing !== $key
            ));
        } else {
            $this->memorizedKeys[] = $key;
        }

        $this->persistList('memorized', $this->memorizedKeys);
    }

    public function recordRecent(string $key): void
    {
        $this->recentKeys = array_values(array_unique([
            $key,
            ...array_filter($this->recentKeys, fn (string $existing) => $existing !== $key),
        ]));
        $this->recentKeys = array_slice($this->recentKeys, 0, 24);
        $this->persistList('recent', $this->recentKeys);
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
        $previewCache = [];

        $enrich = function (array $report) use (&$previewCache): array {
            $key = ErpReportsCatalog::reportKey($report);
            $preset = $this->datePresets[$key] ?? 'this_fiscal_ytd';

            if ($preset === 'custom') {
                $from = $this->customFrom[$key] ?? now()->startOfYear()->toDateString();
                $to = $this->customTo[$key] ?? now()->toDateString();
            } else {
                [$from, $to] = QbDatePresets::dateStrings($preset);
            }

            $route = $report['route'] ?? null;
            $cacheKey = ($route ?? 'none').'|'.$from.'|'.$to;

            if (! isset($previewCache[$cacheKey])) {
                $previewCache[$cacheKey] = ReportCenterPreview::forRoute($route, $from, $to);
            }

            return [
                'key' => $key,
                'label' => $report['label'] ?? 'Report',
                'route' => $route,
                'date_preset' => $preset,
                'from' => $from,
                'to' => $to,
                'from_display' => QbDatePresets::display($from),
                'to_display' => QbDatePresets::display($to),
                'is_favorite' => in_array($key, $this->favoriteKeys, true),
                'is_memorized' => in_array($key, $this->memorizedKeys, true),
                'preview' => $previewCache[$cacheKey],
            ];
        };

        $filterSearch = function (array $report): bool {
            if ($this->search === '') {
                return true;
            }

            return str_contains(
                mb_strtolower($report['label'] ?? ''),
                mb_strtolower($this->search)
            );
        };

        if ($this->tab === 'standard') {
            $groups = collect($active['groups'] ?? [])
                ->map(function (array $group) use ($enrich, $filterSearch) {
                    $reports = collect($group['reports'] ?? [])
                        ->filter($filterSearch)
                        ->map($enrich)
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
        } else {
            $source = match ($this->tab) {
                'favorites' => ErpReportsCatalog::reportsByKeys($this->favoriteKeys),
                'memorized' => $this->memorizedKeys !== []
                    ? ErpReportsCatalog::reportsByKeys($this->memorizedKeys)
                    : collect(ErpReportsCatalog::flatReports())
                        ->filter(fn (array $report) => str_contains(mb_strtolower($report['label']), 'msa')
                            || in_array($report['label'], ['Profit & Loss', 'Balance Sheet', 'A/R Aging Summary', 'Open Invoices'], true))
                        ->take(8)
                        ->values()
                        ->all(),
                'recent' => ErpReportsCatalog::reportsByKeys($this->recentKeys),
                'contributed' => ErpReportsCatalog::contributedReports(),
                default => [],
            };

            $reports = collect($source)
                ->filter($filterSearch)
                ->map($enrich)
                ->values()
                ->all();

            $groups = $reports === []
                ? []
                : [['title' => null, 'reports' => $reports]];
        }

        return view('livewire.reports.report-center', [
            'categories' => $categories,
            'activeCategory' => $active,
            'groups' => $groups,
            'datePresetOptions' => QbDatePresets::options(),
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

    private function settingKey(string $bucket, int $userId): string
    {
        return "report_center.{$bucket}.{$userId}";
    }

    /**
     * @param  list<string>  $keys
     */
    private function persistList(string $bucket, array $keys): void
    {
        Setting::setValue(
            $this->settingKey($bucket, (int) auth()->id()),
            array_values($keys),
            'json',
            'reports'
        );
    }
}
