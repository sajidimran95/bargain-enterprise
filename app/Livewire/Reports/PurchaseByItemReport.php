<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithReportDelivery;
use App\Livewire\Concerns\WithReportFilters;
use App\Models\VendorBillLine;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Purchases by Item')]
class PurchaseByItemReport extends Component
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
            $row['sku'],
            $row['name'],
            number_format($row['qty'], 2, '.', ''),
            number_format($row['amount'], 2, '.', ''),
        ]);

        return $this->exportReportCsv('purchases-by-item.csv', ['SKU', 'Item', 'Qty', 'Amount'], $rows);
    }

    /**
     * @return Collection<int, array{sku: string, name: string, qty: float, amount: float}>
     */
    protected function rows(): Collection
    {
        return VendorBillLine::query()
            ->with(['item', 'vendorBill'])
            ->whereNotNull('item_id')
            ->whereHas('vendorBill', function ($q) {
                $q->whereNotIn('status', ['draft', 'pending'])
                    ->where(function ($memo) {
                        $memo->whereNull('memo')
                            ->orWhere('memo', 'not like', '%CREDIT%');
                    })
                    ->whereDate('bill_date', '>=', $this->from)
                    ->whereDate('bill_date', '<=', $this->to);
            })
            ->get()
            ->groupBy('item_id')
            ->map(function (Collection $lines) {
                $item = $lines->first()?->item;

                return [
                    'sku' => $item?->sku ?? '—',
                    'name' => $item?->name ?? ($lines->first()?->description ?? 'Unknown'),
                    'qty' => (float) $lines->sum('quantity'),
                    'amount' => (float) $lines->sum('amount'),
                ];
            })
            ->sortBy('sku')
            ->values();
    }

    public function render()
    {
        $rows = $this->rows();

        return view('livewire.reports.purchase-by-item-report', [
            'rows' => $rows,
            'grandQty' => $rows->sum('qty'),
            'grandAmount' => $rows->sum('amount'),
            'datePresetOptions' => $this->datePresetOptions(),
            'sortByOptions' => $this->sortByOptions(),
            'subtitle' => $this->reportPeriodLabel(),
        ])->layoutData([
            'title' => 'Purchases by Item',
            'windowTitle' => 'Purchases by Item',
        ]);
    }

    protected function reportPdfTitle(): string
    {
        return 'Purchases by Item';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return $this->reportPeriodLabel();
    }

    protected function reportPdfFilename(): string
    {
        return 'purchases-by-item.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $html = '<table><thead><tr><th>SKU</th><th>Item</th><th class="num">Amount</th></tr></thead><tbody>';
        foreach ($this->rows() as $row) {
            $html .= '<tr><td>'.e($row['sku']).'</td><td>'.e($row['name']).'</td><td class="num">'.e(number_format($row['amount'], 2)).'</td></tr>';
        }

        return $html.'</tbody></table>';
    }
}
