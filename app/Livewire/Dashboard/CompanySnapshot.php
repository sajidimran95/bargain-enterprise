<?php

namespace App\Livewire\Dashboard;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Vendor;
use App\Models\VendorBill;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
class CompanySnapshot extends Component
{
    #[Url]
    public string $insightsTab = 'company';

    public bool $showAddContent = false;

    /** @var array<string, bool> */
    public array $widgets = [];

    public function mount(): void
    {
        $this->widgets = $this->defaultWidgets();
        $saved = session('insights.widgets');
        if (is_array($saved)) {
            $this->widgets = array_merge($this->widgets, array_intersect_key($saved, $this->widgets));
        }
    }

    public function setInsightsTab(string $tab): void
    {
        if (! in_array($tab, ['company', 'payments', 'customer'], true)) {
            return;
        }

        $this->insightsTab = $tab;
    }

    public function toggleAddContent(): void
    {
        $this->showAddContent = ! $this->showAddContent;
    }

    public function toggleWidget(string $key): void
    {
        if (! array_key_exists($key, $this->widgets)) {
            return;
        }

        $this->widgets[$key] = ! $this->widgets[$key];
        session(['insights.widgets' => $this->widgets]);
    }

    public function restoreDefaultWidgets(): void
    {
        $this->widgets = $this->defaultWidgets();
        session(['insights.widgets' => $this->widgets]);
        $this->insightsTab = 'company';
        $this->showAddContent = false;
        $this->dispatch('be-toast', message: 'Snapshot widgets restored to default.');
    }

    /**
     * @return array<string, bool>
     */
    protected function defaultWidgets(): array
    {
        return [
            'income_expense' => true,
            'account_balances' => true,
            'expense_breakdown' => true,
            'customers_owe' => true,
            'top_customers' => true,
            'best_sellers' => true,
            'pop' => true,
        ];
    }

    /**
     * @return Collection<int, array{label: string, this_year: float, last_year: float}>
     */
    protected function weeklyPop(int $weeks = 8): Collection
    {
        $rows = collect();
        $start = now()->startOfWeek()->subWeeks($weeks - 1);

        for ($i = 0; $i < $weeks; $i++) {
            $weekStart = $start->copy()->addWeeks($i);
            $weekEnd = $weekStart->copy()->endOfWeek();
            $prevStart = $weekStart->copy()->subYear();
            $prevEnd = $weekEnd->copy()->subYear();

            $thisYear = (float) Invoice::query()
                ->where('status', '!=', 'draft')
                ->whereBetween('invoice_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
                ->sum('total');

            $lastYear = (float) Invoice::query()
                ->where('status', '!=', 'draft')
                ->whereBetween('invoice_date', [$prevStart->toDateString(), $prevEnd->toDateString()])
                ->sum('total');

            $rows->push([
                'label' => $weekStart->format('M j'),
                'this_year' => $thisYear,
                'last_year' => $lastYear,
            ]);
        }

        return $rows;
    }

    public function render()
    {
        $year = now()->year;

        $monthlyIncome = Invoice::query()
            ->whereYear('invoice_date', $year)
            ->where('status', '!=', 'draft')
            ->get(['invoice_date', 'total'])
            ->groupBy(fn (Invoice $invoice) => (int) $invoice->invoice_date->format('n'))
            ->map(fn ($group) => $group->sum(fn (Invoice $invoice) => (float) $invoice->total));

        $customersWhoOwe = Customer::query()
            ->active()
            ->where('balance', '>', 0)
            ->orderByDesc('balance')
            ->limit(8)
            ->get(['id', 'display_name', 'balance']);

        $topCustomers = Invoice::query()
            ->select('customer_id', DB::raw('SUM(total) as sales'))
            ->where('status', '!=', 'draft')
            ->where('invoice_date', '>=', now()->subYear())
            ->groupBy('customer_id')
            ->orderByDesc('sales')
            ->with('customer')
            ->limit(8)
            ->get();

        $bestSellers = InvoiceLine::query()
            ->select('item_id', DB::raw('SUM(quantity) as qty'), DB::raw('SUM(amount) as sales'))
            ->groupBy('item_id')
            ->orderByDesc('sales')
            ->with('item')
            ->limit(8)
            ->get();

        $recentPayments = Payment::query()
            ->with('customer')
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $undeposited = Payment::query()
            ->with('customer')
            ->where('deposited', false)
            ->orderByDesc('payment_date')
            ->limit(10)
            ->get();

        $openInvoices = Invoice::query()
            ->with('customer')
            ->whereIn('status', ['open', 'partial'])
            ->where('balance_due', '>', 0)
            ->orderBy('due_date')
            ->limit(10)
            ->get();

        $expenseByMonth = VendorBill::query()
            ->whereYear('bill_date', $year)
            ->where('status', '!=', 'draft')
            ->get(['bill_date', 'total'])
            ->groupBy(fn (VendorBill $bill) => (int) $bill->bill_date->format('n'))
            ->map(fn ($group) => $group->sum(fn (VendorBill $bill) => (float) $bill->total));

        return view('livewire.dashboard.company-snapshot', [
            'monthlyIncome' => $monthlyIncome,
            'expenseByMonth' => $expenseByMonth,
            'arBalance' => (float) Customer::query()->sum('balance'),
            'apBalance' => (float) Vendor::query()->sum('balance'),
            'inventoryValue' => (float) Item::query()->selectRaw('SUM(on_hand * average_cost) as value')->value('value'),
            'openInvoiceCount' => Invoice::query()->whereIn('status', ['open', 'partial'])->count(),
            'openBillCount' => VendorBill::query()->whereIn('status', ['open', 'partial'])->count(),
            'customersWhoOwe' => $customersWhoOwe,
            'topCustomers' => $topCustomers,
            'bestSellers' => $bestSellers,
            'recentPayments' => $recentPayments,
            'undeposited' => $undeposited,
            'openInvoices' => $openInvoices,
            'paymentsReceivedYtd' => (float) Payment::query()->whereYear('payment_date', $year)->sum('amount'),
            'undepositedTotal' => (float) Payment::query()->where('deposited', false)->sum('amount'),
            'year' => $year,
            'popWeeks' => $this->weeklyPop(),
            'widgetLabels' => [
                'income_expense' => 'Income and Expense Trend',
                'account_balances' => 'Account Balances',
                'expense_breakdown' => 'Expense Breakdown',
                'customers_owe' => 'Customers Who Owe Money',
                'top_customers' => 'Top Customers by Sales',
                'best_sellers' => 'Best-Selling Items',
                'pop' => 'Prev Year Income Comparison',
            ],
        ])->layoutData([
            'title' => 'Company Snapshot',
            'windowTitle' => 'Snapshots',
        ]);
    }
}
