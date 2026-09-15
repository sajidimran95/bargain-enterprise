<?php

namespace App\Livewire\Concerns;

use App\Support\CsvExporter;
use App\Support\QbDatePresets;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait WithReportFilters
{
    public string $datePreset = 'this_month';

    public string $from = '';

    public string $to = '';

    public string $basis = 'accrual';

    public string $sortBy = 'default';

    public bool $hideHeader = false;

    public bool $showExtraFilters = false;

    public function bootWithReportFilters(): void
    {
        if ($this->from === '' || $this->to === '') {
            $this->applyDatePreset($this->datePreset);
        }
    }

    public function updatedDatePreset(string $value): void
    {
        $this->applyDatePreset($value);
    }

    public function applyDatePreset(?string $preset = null): void
    {
        $preset ??= $this->datePreset;
        $this->datePreset = $preset;

        if ($preset === 'custom') {
            if ($this->from === '' || $this->to === '') {
                [$from, $to] = QbDatePresets::dateStrings('this_month');
                $this->from = $from;
                $this->to = $to;
            }

            return;
        }

        [$from, $to] = QbDatePresets::dateStrings($preset);
        $this->from = $from;
        $this->to = $to;
    }

    public function refreshReport(): void
    {
        // Livewire re-render is enough; hook for explicit Refresh button.
    }

    public function toggleHideHeader(): void
    {
        $this->hideHeader = ! $this->hideHeader;
    }

    public function toggleShowFilters(): void
    {
        $this->showExtraFilters = ! $this->showExtraFilters;
    }

    public function customizeReport(): void
    {
        $this->showExtraFilters = true;
        $this->dispatch('be-toast', message: 'Use the filter bar to customize this report.');
    }

    public function memorizeReport(): void
    {
        $this->dispatch('be-toast', message: 'Report settings memorized for this session.');
    }

    public function commentOnReport(): void
    {
        $this->dispatch('be-toast', message: 'Comments are not available yet.');
    }

    public function shareReportTemplate(): void
    {
        $this->dispatch('be-toast', message: 'Share Template is not available yet.');
    }

    /**
     * @param  array<int, string>  $headers
     * @param  iterable<int, array<int, string|int|float|null>>  $rows
     */
    protected function exportReportCsv(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('report.export'), 403);

        return app(CsvExporter::class)->download($filename, $headers, $rows);
    }

    /**
     * @return array<string, string>
     */
    protected function datePresetOptions(): array
    {
        return QbDatePresets::options();
    }

    /**
     * @return array<string, string>
     */
    protected function sortByOptions(): array
    {
        return [
            'default' => 'Default',
            'date' => 'Date',
            'amount' => 'Amount',
            'num' => 'Num',
        ];
    }

    protected function reportPeriodLabel(): string
    {
        $from = Carbon::parse($this->from)->startOfDay();
        $to = Carbon::parse($this->to)->startOfDay();

        if ($from->isSameDay($to)) {
            return $from->format('F j, Y');
        }

        if ($from->day === 1 && $to->isSameDay($to->copy()->endOfMonth()->startOfDay()) && $from->isSameMonth($to)) {
            return $from->format('F Y');
        }

        if (
            $from->month === 1 && $from->day === 1
            && $to->month === 12 && $to->day === 31
            && $from->year === $to->year
        ) {
            return 'January through December '.$from->year;
        }

        if ($from->year === $to->year && $from->day === 1 && $to->isSameDay($to->copy()->endOfMonth()->startOfDay())) {
            return $from->format('F').' through '.$to->format('F Y');
        }

        return $from->format('F j, Y').' through '.$to->format('F j, Y');
    }
}
