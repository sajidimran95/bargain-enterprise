<?php

namespace App\Livewire\Items;

use App\Actions\Items\ActivateItemAction;
use App\Actions\Items\DeactivateItemAction;
use App\Actions\Items\DuplicateItemAction;
use App\Models\Item;
use App\Support\CsvExporter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Item List')]
class ItemList extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $searchField = 'all';

    #[Url]
    public bool $includeInactive = false;

    #[Url]
    public ?int $selectedId = null;

    public bool $searchWithinResults = false;

    public string $priorSearch = '';

    public bool $showActivity = true;

    public function focusSearch(): void
    {
        $this->dispatch('be-focus-list-search');
    }

    public function mount(): void
    {
        $this->authorize('viewAny', Item::class);
    }

    public function updatingSearch(): void
    {
        if (! $this->searchWithinResults) {
            $this->priorSearch = '';
        }
        $this->resetPage();
    }

    public function selectItem(int $id): void
    {
        $this->selectedId = $id;
        $this->showActivity = true;
    }

    public function openActivityHistory(): void
    {
        if (! $this->selectedId) {
            $this->dispatch('be-toast', message: 'Select an item first.');

            return;
        }

        $this->showActivity = true;
    }

    public function toggleActivity(): void
    {
        $this->showActivity = ! $this->showActivity;
    }

    public function search(): void
    {
        if ($this->searchWithinResults && filled($this->search)) {
            $this->priorSearch = trim($this->priorSearch.' '.$this->search);
        }
        $this->resetPage();
    }

    public function resetSearch(): void
    {
        $this->reset(['search', 'priorSearch', 'searchWithinResults', 'searchField']);
        $this->includeInactive = false;
        $this->resetPage();
    }

    public function deactivateSelected(DeactivateItemAction $action): void
    {
        $item = $this->requireSelected();
        $this->authorize('delete', $item);
        $action->handle($item);
        $this->dispatch('be-toast', message: 'Item deactivated.');
    }

    public function activateSelected(ActivateItemAction $action): void
    {
        $item = $this->requireSelected();
        $this->authorize('update', $item);
        $action->handle($item);
        $this->dispatch('be-toast', message: 'Item activated.');
    }

    public function duplicateSelected(DuplicateItemAction $action): mixed
    {
        $item = $this->requireSelected();
        $this->authorize('create', Item::class);
        $copy = $action->handle($item);

        return redirect()->route('items.edit', $copy);
    }

    public function deleteSelected(): void
    {
        $item = $this->requireSelected();
        $this->authorize('delete', $item);
        $item->delete();
        $this->selectedId = null;
        $this->dispatch('be-toast', message: 'Item deleted.');
    }

    public function exportExcel(CsvExporter $exporter): StreamedResponse
    {
        $this->authorize('viewAny', Item::class);

        $term = $this->searchWithinResults && filled($this->priorSearch)
            ? trim($this->priorSearch.' '.$this->search)
            : $this->search;

        $rows = Item::query()
            ->with(['category', 'itemType'])
            ->when(! $this->includeInactive, fn ($q) => $q->active())
            ->search($term, $this->searchField)
            ->orderBy('sku')
            ->get()
            ->map(fn (Item $i) => [
                $i->sku,
                $i->sales_description ?: $i->name,
                $i->itemType?->label,
                $i->category?->code,
                $i->on_hand,
                $i->sales_price,
                $i->purchase_cost,
                $i->promotion,
                $i->is_active ? 'Active' : 'Inactive',
            ]);

        return $exporter->download('items.csv', [
            'SKU', 'Description', 'Item Type', 'Category', 'Qty On Hand', 'Price', 'Cost', 'Promotion', 'Status',
        ], $rows);
    }

    protected function requireSelected(): Item
    {
        abort_unless($this->selectedId, 404);

        return Item::query()->findOrFail($this->selectedId);
    }

    public function render()
    {
        $term = $this->searchWithinResults && filled($this->priorSearch)
            ? trim($this->priorSearch.' '.$this->search)
            : $this->search;

        $items = Item::query()
            ->with(['category', 'itemType'])
            ->when(! $this->includeInactive, fn ($q) => $q->active())
            ->search($term, $this->searchField)
            ->orderBy('sku')
            ->orderBy('id')
            ->paginate(40);

        if (! $this->selectedId && $items->count() > 0) {
            $this->selectedId = $items->first()->id;
        }

        $selected = $this->selectedId
            ? Item::query()->find($this->selectedId)
            : null;

        $activity = $selected && $this->showActivity
            ? $selected->histories()->with('createdBy')->latest('occurred_at')->latest('id')->limit(40)->get()
            : collect();

        return view('livewire.items.item-list', [
            'items' => $items,
            'selected' => $selected,
            'activity' => $activity,
        ])->layoutData([
            'title' => 'Item List',
            'windowTitle' => 'Item List',
        ]);
    }
}
