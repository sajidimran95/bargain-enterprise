<?php

namespace App\Livewire\Inventory;

use App\Livewire\Concerns\WithErpListActions;
use App\Models\InventoryTransaction;
use App\Models\Item;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Inventory')]
class InventoryIndex extends Component
{
    use AuthorizesRequests;
    use WithErpListActions;
    use WithPagination;

    #[Url]
    public string $stock = 'all';

    public function mount(): void
    {
        $this->authorize('viewAny', Item::class);
    }

    public function updatingStock(): void
    {
        $this->resetPage();
    }

    public function exportExcel(): StreamedResponse
    {
        $this->authorize('viewAny', Item::class);

        $rows = Item::query()
            ->with(['category', 'itemType'])
            ->active()
            ->search($this->search)
            ->when($this->stock === 'low', fn ($q) => $q->whereColumn('on_hand', '<=', 'reorder_min'))
            ->when($this->stock === 'zero', fn ($q) => $q->where('on_hand', 0))
            ->when($this->stock === 'negative', fn ($q) => $q->where('on_hand', '<', 0))
            ->orderBy('sku')
            ->get()
            ->map(fn (Item $item) => [
                $item->sku,
                $item->sales_description ?: $item->name,
                $item->category?->code,
                number_format((float) $item->on_hand, 4, '.', ''),
                number_format((float) $item->average_cost, 2, '.', ''),
                number_format((float) $item->reorder_min, 4, '.', ''),
                $item->promotion ?: '',
            ]);

        return $this->csvDownload(
            'inventory.csv',
            ['SKU', 'Description', 'Category', 'On Hand', 'Avg Cost', 'Reorder Min', 'Promotion'],
            $rows
        );
    }

    public function render()
    {
        $items = Item::query()
            ->with(['category', 'itemType'])
            ->active()
            ->search($this->search)
            ->when($this->stock === 'low', fn ($q) => $q->whereColumn('on_hand', '<=', 'reorder_min'))
            ->when($this->stock === 'zero', fn ($q) => $q->where('on_hand', 0))
            ->when($this->stock === 'negative', fn ($q) => $q->where('on_hand', '<', 0))
            ->orderBy('sku')
            ->paginate(40);

        $recent = InventoryTransaction::query()
            ->with('item')
            ->latest('occurred_at')
            ->latest('id')
            ->limit(15)
            ->get();

        return view('livewire.inventory.inventory-index', [
            'items' => $items,
            'recent' => $recent,
        ])->layoutData([
            'title' => 'Inventory',
            'windowTitle' => 'Inventory',
        ]);
    }
}
