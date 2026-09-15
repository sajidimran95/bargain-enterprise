<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithReportDelivery;
use App\Livewire\Concerns\WithReportFilters;
use App\Models\Item;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Inventory Valuation Summary')]
class InventoryValuationReport extends Component
{
    use WithReportDelivery;
    use WithReportFilters;

    public string $search = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);
        $this->datePreset = 'today';
        $this->applyDatePreset('today');
    }

    public function exportExcel(): StreamedResponse
    {
        $rows = $this->rows()->map(fn (array $row) => [
            $row['sku'],
            $row['name'],
            number_format($row['qty'], 2, '.', ''),
            number_format($row['avg_cost'], 4, '.', ''),
            number_format($row['asset_value'], 2, '.', ''),
            number_format($row['sales_price'], 2, '.', ''),
            number_format($row['retail_value'], 2, '.', ''),
        ]);

        return $this->exportReportCsv(
            'inventory-valuation.csv',
            ['SKU', 'Name', 'On Hand', 'Avg Cost', 'Asset Value', 'Sales Price', 'Retail Value'],
            $rows
        );
    }

    /**
     * @return Collection<int, array{sku: string, name: string, qty: float, avg_cost: float, asset_value: float, sales_price: float, retail_value: float}>
     */
    protected function rows(): Collection
    {
        return Item::query()
            ->active()
            ->when($this->search !== '', function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(fn ($inner) => $inner->where('sku', 'like', $like)->orWhere('name', 'like', $like));
            })
            ->orderBy('sku')
            ->get()
            ->map(function (Item $item) {
                $qty = (float) $item->on_hand;
                $cost = (float) ($item->average_cost ?: $item->purchase_cost ?: 0);
                $price = (float) $item->sales_price;

                return [
                    'sku' => $item->sku,
                    'name' => $item->sales_description ?: $item->name,
                    'qty' => $qty,
                    'avg_cost' => $cost,
                    'asset_value' => round($qty * $cost, 2),
                    'sales_price' => $price,
                    'retail_value' => round($qty * $price, 2),
                ];
            })
            ->values();
    }

    public function render()
    {
        $rows = $this->rows();

        return view('livewire.reports.inventory-valuation-report', [
            'rows' => $rows,
            'assetTotal' => $rows->sum('asset_value'),
            'retailTotal' => $rows->sum('retail_value'),
            'datePresetOptions' => $this->datePresetOptions(),
            'sortByOptions' => $this->sortByOptions(),
            'subtitle' => 'As of '.$this->to,
        ])->layoutData([
            'title' => 'Inventory Valuation Summary',
            'windowTitle' => 'Inventory Valuation Summary',
        ]);
    }

    protected function reportPdfTitle(): string
    {
        return 'Inventory Valuation Summary';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return 'As of '.$this->to;
    }

    protected function reportPdfFilename(): string
    {
        return 'inventory-valuation.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $html = '<table><thead><tr><th>SKU</th><th>Name</th><th class="num">Qty</th><th class="num">Asset Value</th></tr></thead><tbody>';
        foreach ($this->rows() as $row) {
            $html .= '<tr><td>'.e($row['sku']).'</td><td>'.e($row['name']).'</td><td class="num">'.e(number_format($row['qty'], 2)).'</td><td class="num">'.e(number_format($row['asset_value'], 2)).'</td></tr>';
        }
        $html .= '</tbody></table>';

        return $html;
    }
}
