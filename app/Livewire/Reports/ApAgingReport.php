<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithReportDelivery;
use App\Livewire\Concerns\WithReportFilters;
use App\Models\VendorBill;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('A/P Aging Summary')]
class ApAgingReport extends Component
{
    use WithReportDelivery;
    use WithReportFilters;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);
        $this->datePreset = 'today';
        $this->applyDatePreset('today');
    }

    public function exportExcel(): StreamedResponse
    {
        $rows = $this->rows()->map(fn (array $row) => [
            $row['vendor'],
            number_format($row['current'], 2, '.', ''),
            number_format($row['days_1_30'], 2, '.', ''),
            number_format($row['days_31_60'], 2, '.', ''),
            number_format($row['days_61_90'], 2, '.', ''),
            number_format($row['days_90_plus'], 2, '.', ''),
            number_format($row['total'], 2, '.', ''),
        ]);

        return $this->exportReportCsv(
            'ap-aging-summary.csv',
            ['Vendor', 'Current', '1-30', '31-60', '61-90', '>90', 'Total'],
            $rows
        );
    }

    /**
     * @return Collection<int, array{vendor: string, current: float, days_1_30: float, days_31_60: float, days_61_90: float, days_90_plus: float, total: float}>
     */
    protected function rows(): Collection
    {
        $asOf = Carbon::parse($this->to)->startOfDay();

        return VendorBill::query()
            ->with('vendor')
            ->whereIn('status', ['open', 'partial'])
            ->where('balance_due', '>', 0)
            ->where(function ($query) {
                $query->whereNull('memo')
                    ->orWhere('memo', 'not like', '%CREDIT%');
            })
            ->orderBy('vendor_id')
            ->get()
            ->groupBy('vendor_id')
            ->map(function (Collection $bills) use ($asOf) {
                $buckets = [
                    'current' => 0.0,
                    'days_1_30' => 0.0,
                    'days_31_60' => 0.0,
                    'days_61_90' => 0.0,
                    'days_90_plus' => 0.0,
                ];

                foreach ($bills as $bill) {
                    $due = Carbon::parse($bill->due_date ?? $bill->bill_date)->startOfDay();
                    $days = $due->diffInDays($asOf, false);
                    $balance = (float) $bill->balance_due;

                    if ($days <= 0) {
                        $buckets['current'] += $balance;
                    } elseif ($days <= 30) {
                        $buckets['days_1_30'] += $balance;
                    } elseif ($days <= 60) {
                        $buckets['days_31_60'] += $balance;
                    } elseif ($days <= 90) {
                        $buckets['days_61_90'] += $balance;
                    } else {
                        $buckets['days_90_plus'] += $balance;
                    }
                }

                $vendor = $bills->first()?->vendor;

                return [
                    'vendor' => $vendor?->display_name ?? 'Unknown',
                    ...$buckets,
                    'total' => array_sum($buckets),
                ];
            })
            ->sortBy('vendor')
            ->values();
    }

    public function render()
    {
        $rows = $this->rows();

        return view('livewire.reports.ap-aging-report', [
            'rows' => $rows,
            'totals' => [
                'current' => $rows->sum('current'),
                'days_1_30' => $rows->sum('days_1_30'),
                'days_31_60' => $rows->sum('days_31_60'),
                'days_61_90' => $rows->sum('days_61_90'),
                'days_90_plus' => $rows->sum('days_90_plus'),
                'total' => $rows->sum('total'),
            ],
            'datePresetOptions' => $this->datePresetOptions(),
            'sortByOptions' => $this->sortByOptions(),
            'subtitle' => 'As of '.$this->to,
        ])->layoutData([
            'title' => 'A/P Aging Summary',
            'windowTitle' => 'A/P Aging Summary',
        ]);
    }

    protected function reportPdfTitle(): string
    {
        return 'A/P Aging Summary';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return 'As of '.$this->to;
    }

    protected function reportPdfFilename(): string
    {
        return 'ap-aging-summary.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $html = '<table><thead><tr><th>Vendor</th><th class="num">Total</th></tr></thead><tbody>';
        foreach ($this->rows() as $row) {
            $html .= '<tr><td>'.e($row['vendor']).'</td><td class="num">'.e(number_format($row['total'], 2)).'</td></tr>';
        }
        $html .= '</tbody></table>';

        return $html;
    }
}
