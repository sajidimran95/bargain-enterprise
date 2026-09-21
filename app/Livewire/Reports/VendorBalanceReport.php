<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithReportDelivery;
use App\Livewire\Concerns\WithReportFilters;
use App\Models\VendorBill;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Vendor Balance Summary')]
class VendorBalanceReport extends Component
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
            $row['vendor'],
            number_format($row['total'], 2, '.', ''),
            number_format($row['balance'], 2, '.', ''),
        ]);

        return $this->exportReportCsv('vendor-balance.csv', ['Vendor', 'Billed', 'Balance Due'], $rows);
    }

    /**
     * @return Collection<int, array{vendor: string, total: float, balance: float}>
     */
    protected function rows(): Collection
    {
        return VendorBill::query()
            ->with('vendor')
            ->whereIn('status', ['open', 'partial', 'paid'])
            ->whereDate('bill_date', '>=', $this->from)
            ->whereDate('bill_date', '<=', $this->to)
            ->get()
            ->groupBy('vendor_id')
            ->map(function (Collection $bills) {
                $total = 0.0;
                $balance = 0.0;
                foreach ($bills as $bill) {
                    $sign = $bill->isCreditDocument() ? -1.0 : 1.0;
                    $total += $sign * (float) $bill->total;
                    $balance += $sign * (float) $bill->balance_due;
                }

                return [
                    'vendor' => $bills->first()?->vendor?->display_name ?? 'Unknown',
                    'total' => $total,
                    'balance' => $balance,
                ];
            })
            ->sortBy('vendor')
            ->values();
    }

    public function render()
    {
        $rows = $this->rows();

        return view('livewire.reports.vendor-balance-report', [
            'rows' => $rows,
            'grandTotal' => $rows->sum('total'),
            'grandBalance' => $rows->sum('balance'),
            'datePresetOptions' => $this->datePresetOptions(),
            'sortByOptions' => $this->sortByOptions(),
            'subtitle' => $this->reportPeriodLabel(),
        ])->layoutData([
            'title' => 'Vendor Balance Summary',
            'windowTitle' => 'Vendor Balance Summary',
        ]);
    }

    protected function reportPdfTitle(): string
    {
        return 'Vendor Balance Summary';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return $this->reportPeriodLabel();
    }

    protected function reportPdfFilename(): string
    {
        return 'vendor-balance.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $html = '<table><thead><tr><th>Vendor</th><th class="num">Balance</th></tr></thead><tbody>';
        foreach ($this->rows() as $row) {
            $html .= '<tr><td>'.e($row['vendor']).'</td><td class="num">'.e(number_format($row['balance'], 2)).'</td></tr>';
        }

        return $html.'</tbody></table>';
    }
}
