<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithReportDelivery;
use App\Livewire\Concerns\WithReportFilters;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\VendorBill;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Profit & Loss')]
class ProfitLossReport extends Component
{
    use WithReportDelivery;
    use WithReportFilters;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);
        $this->datePreset = 'this_month';
        $this->applyDatePreset('this_month');
    }

    /**
     * @return array{income: float, cogs: float, expenses: float, gross: float, net: float, income_accounts: list<array{name: string, amount: float}>, expense_accounts: list<array{name: string, amount: float}>}
     */
    protected function statement(): array
    {
        $income = (float) Invoice::query()
            ->whereNot('status', 'draft')
            ->whereDate('invoice_date', '>=', $this->from)
            ->whereDate('invoice_date', '<=', $this->to)
            ->sum('total');

        $cogs = (float) VendorBill::query()
            ->whereNot('status', 'draft')
            ->whereDate('bill_date', '>=', $this->from)
            ->whereDate('bill_date', '<=', $this->to)
            ->sum('total');

        $journalIncome = $this->accountTotals(['income']);
        $journalExpense = $this->accountTotals(['expense', 'cogs']);

        if ($journalIncome > 0 || $journalExpense > 0) {
            $income = max($income, $journalIncome);
            $cogs = max($cogs, $journalExpense);
        }

        $gross = $income - $cogs;

        return [
            'income' => $income,
            'cogs' => $cogs,
            'expenses' => $cogs,
            'gross' => $gross,
            'net' => $gross,
            'income_accounts' => [
                ['name' => 'Sales Revenue', 'amount' => $income],
            ],
            'expense_accounts' => [
                ['name' => 'Cost of Goods Sold / Purchases', 'amount' => $cogs],
            ],
        ];
    }

    protected function accountTotals(array $types): float
    {
        return (float) Account::query()
            ->whereIn('type', $types)
            ->withSum(['journalLines as debit_sum' => fn ($q) => $q->whereHas('journalEntry', fn ($j) => $j->whereDate('entry_date', '>=', $this->from)->whereDate('entry_date', '<=', $this->to))], 'debit')
            ->withSum(['journalLines as credit_sum' => fn ($q) => $q->whereHas('journalEntry', fn ($j) => $j->whereDate('entry_date', '>=', $this->from)->whereDate('entry_date', '<=', $this->to))], 'credit')
            ->get()
            ->sum(function (Account $account) {
                $debit = (float) ($account->debit_sum ?? 0);
                $credit = (float) ($account->credit_sum ?? 0);

                return in_array($account->type, ['income'], true)
                    ? $credit - $debit
                    : $debit - $credit;
            });
    }

    public function exportExcel(): StreamedResponse
    {
        $s = $this->statement();

        return $this->exportReportCsv(
            'profit-and-loss.csv',
            ['Section', 'Account', 'Amount'],
            [
                ['Income', 'Sales Revenue', number_format($s['income'], 2, '.', '')],
                ['COGS', 'Cost of Goods Sold / Purchases', number_format($s['cogs'], 2, '.', '')],
                ['Total', 'Gross / Net Profit', number_format($s['net'], 2, '.', '')],
            ]
        );
    }

    public function render()
    {
        return view('livewire.reports.profit-loss-report', [
            'statement' => $this->statement(),
            'datePresetOptions' => $this->datePresetOptions(),
            'sortByOptions' => $this->sortByOptions(),
            'subtitle' => $this->reportPeriodLabel(),
        ])->layoutData([
            'title' => 'Profit & Loss',
            'windowTitle' => 'Profit & Loss',
        ]);
    }

    protected function reportPdfTitle(): string
    {
        return 'Profit & Loss';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return $this->reportPeriodLabel();
    }

    protected function reportPdfFilename(): string
    {
        return 'profit-and-loss.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $s = $this->statement();

        return '<table><tbody>'
            .'<tr><td>Income</td><td class="num">'.e(number_format($s['income'], 2)).'</td></tr>'
            .'<tr><td>COGS / Purchases</td><td class="num">'.e(number_format($s['cogs'], 2)).'</td></tr>'
            .'<tr><td><strong>Net Profit</strong></td><td class="num"><strong>'.e(number_format($s['net'], 2)).'</strong></td></tr>'
            .'</tbody></table>';
    }
}
