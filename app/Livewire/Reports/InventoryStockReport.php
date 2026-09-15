<?php

namespace App\Livewire\Reports;

use App\Livewire\Concerns\WithReportDelivery;
use App\Livewire\Concerns\WithReportFilters;
use App\Models\Item;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('MSA Inventory')]
class InventoryStockReport extends Component
{
    use WithReportDelivery;
    use WithReportFilters;

    public string $status = 'active';

    public string $search = '';

    public string $searchField = 'all';

    public bool $searchWithinResults = false;

    public string $priorSearch = '';

    public bool $includeInactive = false;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('report.view'), 403);
        $this->datePreset = 'all';
        $this->applyDatePreset('all');
        $this->sortBy = 'default';
        $this->showExtraFilters = true;
    }

    public function updatedIncludeInactive(bool $value): void
    {
        $this->status = $value ? 'all' : 'active';
    }

    public function runSearch(): void
    {
        if ($this->searchWithinResults && filled($this->search)) {
            $this->priorSearch = trim($this->priorSearch.' '.$this->search);
        }
    }

    public function resetSearch(): void
    {
        $this->reset(['search', 'priorSearch', 'searchWithinResults', 'searchField', 'includeInactive']);
        $this->status = 'active';
    }

    public function exportExcel(): StreamedResponse
    {
        $rows = $this->rows()->map(fn (Item $item) => [
            $this->itemName($item),
            $item->sales_description ?: $item->name,
            $this->typeLabel($item),
            $item->itemType?->label,
            number_format((float) $item->on_hand, 4, '.', ''),
            number_format((float) $item->sales_price, 2, '.', ''),
            number_format((float) $item->purchase_cost, 2, '.', ''),
        ]);

        return $this->exportReportCsv(
            'msa-inventory.csv',
            ['Name', 'Description', 'Type', 'Item Type', 'Total Quantity On Hand', 'Price', 'Cost'],
            $rows
        );
    }

    /**
     * @return Collection<int, Item>
     */
    protected function rows(): Collection
    {
        $query = Item::query()->with(['category', 'itemType']);

        if ($this->status === 'active') {
            $query->active();
        } elseif ($this->status === 'inactive') {
            $query->where('is_active', false);
        }

        $term = $this->effectiveSearchTerm();
        if (filled($term)) {
            $exact = Item::query()->byScanCode($term);
            if ($this->status === 'active') {
                $exact->active();
            } elseif ($this->status === 'inactive') {
                $exact->where('is_active', false);
            }

            if ($exact->exists()) {
                $query->byScanCode($term);
            } else {
                $query->search($term, $this->searchField);
            }
        }

        return $this->applySort($query)->get();
    }

    protected function effectiveSearchTerm(): string
    {
        if ($this->searchWithinResults && filled($this->priorSearch)) {
            return trim($this->priorSearch.' '.$this->search);
        }

        return trim($this->search);
    }

    protected function applySort(Builder $query): Builder
    {
        return match ($this->sortBy) {
            'name', 'item' => $query->orderByRaw('COALESCE(NULLIF(barcode, ""), sku)'),
            'description' => $query->orderByRaw('COALESCE(NULLIF(sales_description, ""), name)'),
            'type' => $query->orderBy('type')->orderBy('sku'),
            'item_type' => $query->leftJoin('item_types', 'items.item_type_id', '=', 'item_types.id')
                ->orderBy('item_types.label')
                ->orderBy('items.sku')
                ->select('items.*'),
            'qty' => $query->orderByDesc('on_hand')->orderBy('sku'),
            'price' => $query->orderByDesc('sales_price')->orderBy('sku'),
            'cost' => $query->orderByDesc('purchase_cost')->orderBy('sku'),
            default => $query->orderByRaw('COALESCE(NULLIF(barcode, ""), sku)'),
        };
    }

    protected function itemName(Item $item): string
    {
        return (string) ($item->barcode ?: $item->sku);
    }

    protected function typeLabel(Item $item): string
    {
        return (string) str($item->type ?: 'inventory_part')->replace('_', ' ')->title();
    }

    /**
     * @return array<string, string>
     */
    protected function inventorySortOptions(): array
    {
        return [
            'default' => 'Default',
            'name' => 'Name',
            'description' => 'Description',
            'type' => 'Type',
            'item_type' => 'Item Type',
            'qty' => 'Total Quantity On Hand',
            'price' => 'Price',
            'cost' => 'Cost',
        ];
    }

    protected function reportPdfTitle(): string
    {
        return 'MSA Inventory';
    }

    protected function reportPdfSubtitle(): ?string
    {
        return null;
    }

    protected function reportPdfFilename(): string
    {
        return 'msa-inventory.pdf';
    }

    protected function reportPdfBodyHtml(): string
    {
        $html = '<table><thead><tr><th>Name</th><th>Description</th><th>Type</th><th>Item Type</th><th class="num">Qty OH</th><th class="num">Price</th><th class="num">Cost</th></tr></thead><tbody>';
        foreach ($this->rows() as $item) {
            $html .= '<tr>'
                .'<td>'.e($this->itemName($item)).'</td>'
                .'<td>'.e($item->sales_description ?: $item->name).'</td>'
                .'<td>'.e($this->typeLabel($item)).'</td>'
                .'<td>'.e((string) ($item->itemType?->label ?? '')).'</td>'
                .'<td class="num">'.e(number_format((float) $item->on_hand, 0)).'</td>'
                .'<td class="num">'.e(number_format((float) $item->sales_price, 2)).'</td>'
                .'<td class="num">'.e(number_format((float) $item->purchase_cost, 2)).'</td>'
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
            'sortByOptions' => $this->inventorySortOptions(),
        ])->layoutData([
            'title' => 'MSA Inventory',
            'windowTitle' => 'MSA Inventory',
        ]);
    }
}
