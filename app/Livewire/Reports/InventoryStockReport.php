<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithReportDelivery;
use App\Livewire\Concerns\WithReportFilters;
use App\Models\Item;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Inventory Stock')]
class InventoryStockReport extends Component
{
    use WithReportDelivery;
    use WithReportFilters;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);
        $this->datePreset = 'all';
        $this->applyDatePreset('all');
    }

    public function exportExcel(): StreamedResponse
    {
        $rows = $this->rows()->map(fn (Item $item) => [
            $item->sku,
            $item->sales_description ?: $item->name,
            number_format((float) $item->on_hand, 4, '.', ''),
            $item->category?->code,
            $item->items_per_container !== null ? number_format((float) $item->items_per_container, 4, '.', '') : '',
            $item->promotion,
            number_format((float) $item->sales_price, 2, '.', ''),
        ]);

        return $this->exportReportCsv(
            'inventory-stock.csv',
            ['Item', 'Description', 'Qty OH', 'Category', 'Items/Container', 'Promotion', 'Price'],
            $rows
        );
    }

    protected function rows()
    {
        return Item::query()
            ->with('category')
            ->active()
            ->orderBy('sku')
            ->get();
    }

    protected function reportPdfTitle(): string
    {
        return 'Inventory / Stock';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return null;
    }

    protected function reportPdfFilename(): string
    {
        return 'inventory-stock.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $html = '<table><thead><tr><th>Item</th><th>Description</th><th class="num">Qty OH</th><th>Category</th><th>Promotion</th><th class="num">Price</th></tr></thead><tbody>';
        foreach ($this->rows() as $item) {
            $html .= '<tr>'
                .'<td>'.e($item->sku).'</td>'
                .'<td>'.e($item->sales_description ?: $item->name).'</td>'
                .'<td class="num">'.e(number_format((float) $item->on_hand, 2)).'</td>'
                .'<td>'.e((string) $item->category?->code).'</td>'
                .'<td>'.e((string) $item->promotion).'</td>'
                .'<td class="num">'.e(number_format((float) $item->sales_price, 2)).'</td>'
                .'</tr>';
        }
        $html .= '</tbody></table>';

        return $html;
    }

    public function render()
    {
        return view('livewire.reports.inventory-stock-report', [
            'rows' => $this->rows(),
            'datePresetOptions' => $this->datePresetOptions(),
        ])->layoutData([
            'title' => 'Inventory Stock',
            'windowTitle' => 'Inventory Stock',
        ]);
    }
}
