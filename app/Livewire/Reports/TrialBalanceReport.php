<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithReportDelivery;
use App\Livewire\Concerns\WithReportFilters;
use App\Models\Account;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Trial Balance')]
class TrialBalanceReport extends Component
{
    use WithReportDelivery;
    use WithReportFilters;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);
        $this->datePreset = 'this_year';
        $this->applyDatePreset('this_year');
    }

    public function exportExcel(): StreamedResponse
    {
        $rows = $this->rows()->map(fn (array $row) => [
            $row['number'],
            $row['name'],
            number_format($row['debit'], 2, '.', ''),
            number_format($row['credit'], 2, '.', ''),
        ]);

        return $this->exportReportCsv('trial-balance.csv', ['Acct #', 'Account', 'Debit', 'Credit'], $rows);
    }

    protected function rows()
    {
        return Account::query()
            ->where('is_active', true)
            ->withSum(['journalLines as debit_sum' => fn ($q) => $q->whereHas('journalEntry', fn ($j) => $j->whereDate('entry_date', '>=', $this->from)->whereDate('entry_date', '<=', $this->to))], 'debit')
            ->withSum(['journalLines as credit_sum' => fn ($q) => $q->whereHas('journalEntry', fn ($j) => $j->whereDate('entry_date', '>=', $this->from)->whereDate('entry_date', '<=', $this->to))], 'credit')
            ->orderBy('number')
            ->get()
            ->map(fn (Account $account) => [
                'number' => $account->number,
                'name' => $account->name,
                'type' => $account->type,
                'debit' => (float) ($account->debit_sum ?? 0),
                'credit' => (float) ($account->credit_sum ?? 0),
            ])
            ->filter(fn (array $row) => $row['debit'] > 0 || $row['credit'] > 0)
            ->values();
    }

    public function render()
    {
        $rows = $this->rows();

        return view('livewire.reports.trial-balance-report', [
            'rows' => $rows,
            'debitTotal' => $rows->sum('debit'),
            'creditTotal' => $rows->sum('credit'),
            'datePresetOptions' => $this->datePresetOptions(),
            'sortByOptions' => $this->sortByOptions(),
            'subtitle' => $this->reportPeriodLabel(),
        ])->layoutData([
            'title' => 'Trial Balance',
            'windowTitle' => 'Trial Balance',
        ]);
    }

    protected function reportPdfTitle(): string
    {
        return 'Trial Balance';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return $this->reportPeriodLabel();
    }

    protected function reportPdfFilename(): string
    {
        return 'trial-balance.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $html = '<table><thead><tr><th>Acct</th><th>Name</th><th class="num">Debit</th><th class="num">Credit</th></tr></thead><tbody>';
        foreach ($this->rows() as $row) {
            $html .= '<tr><td>'.e($row['number']).'</td><td>'.e($row['name']).'</td><td class="num">'.e(number_format($row['debit'], 2)).'</td><td class="num">'.e(number_format($row['credit'], 2)).'</td></tr>';
        }

        return $html.'</tbody></table>';
    }
}
