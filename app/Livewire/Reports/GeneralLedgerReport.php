<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithReportDelivery;
use App\Livewire\Concerns\WithReportFilters;
use App\Models\JournalLine;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('General Ledger')]
class GeneralLedgerReport extends Component
{
    use WithReportDelivery;
    use WithReportFilters;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);
        $this->datePreset = 'this_month';
        $this->applyDatePreset('this_month');
    }

    public function exportExcel(): StreamedResponse
    {
        $rows = $this->rows()->map(fn (array $row) => [
            $row['date'],
            $row['account'],
            $row['memo'],
            number_format($row['debit'], 2, '.', ''),
            number_format($row['credit'], 2, '.', ''),
        ]);

        return $this->exportReportCsv('general-ledger.csv', ['Date', 'Account', 'Memo', 'Debit', 'Credit'], $rows);
    }

    protected function rows()
    {
        return JournalLine::query()
            ->with(['account', 'journalEntry'])
            ->whereHas('journalEntry', fn ($q) => $q->whereDate('entry_date', '>=', $this->from)->whereDate('entry_date', '<=', $this->to))
            ->orderBy('id')
            ->get()
            ->map(fn (JournalLine $line) => [
                'date' => $line->journalEntry?->entry_date?->format('m/d/Y') ?? '',
                'account' => trim(($line->account?->number ?? '').' '.($line->account?->name ?? '')),
                'memo' => $line->memo ?: ($line->journalEntry?->memo ?? ''),
                'debit' => (float) $line->debit,
                'credit' => (float) $line->credit,
            ])
            ->values();
    }

    public function render()
    {
        $rows = $this->rows();

        return view('livewire.reports.general-ledger-report', [
            'rows' => $rows,
            'debitTotal' => $rows->sum('debit'),
            'creditTotal' => $rows->sum('credit'),
            'datePresetOptions' => $this->datePresetOptions(),
            'sortByOptions' => $this->sortByOptions(),
            'subtitle' => $this->reportPeriodLabel(),
        ])->layoutData([
            'title' => 'General Ledger',
            'windowTitle' => 'General Ledger',
        ]);
    }

    protected function reportPdfTitle(): string
    {
        return 'General Ledger';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return $this->reportPeriodLabel();
    }

    protected function reportPdfFilename(): string
    {
        return 'general-ledger.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $html = '<table><thead><tr><th>Date</th><th>Account</th><th class="num">Debit</th><th class="num">Credit</th></tr></thead><tbody>';
        foreach ($this->rows() as $row) {
            $html .= '<tr><td>'.e($row['date']).'</td><td>'.e($row['account']).'</td><td class="num">'.e(number_format($row['debit'], 2)).'</td><td class="num">'.e(number_format($row['credit'], 2)).'</td></tr>';
        }

        return $html.'</tbody></table>';
    }
}
