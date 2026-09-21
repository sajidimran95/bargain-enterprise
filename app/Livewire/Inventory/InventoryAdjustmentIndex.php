<?php

namespace App\Livewire\Inventory;

use App\Livewire\Concerns\WithErpListActions;
use App\Models\InventoryTransaction;
use App\Models\Item;
use App\Services\InventoryService;
use App\Support\ItemCatalog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Inventory Adjustments')]
class InventoryAdjustmentIndex extends Component
{
    use AuthorizesRequests;
    use WithErpListActions;
    use WithPagination;

    public bool $showForm = false;

    /** @var array<string, mixed> */
    public array $form = [
        'item_id' => '',
        'direction' => 'in',
        'qty' => '',
        'unit_cost' => '',
        'memo' => '',
    ];

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('inventory.adjust'), 403);

        if (request()->boolean('new')) {
            $this->newAdjustment();
        }
    }

    public function newAdjustment(): void
    {
        abort_unless(auth()->user()?->hasPermission('inventory.adjust'), 403);
        $this->form = [
            'item_id' => '',
            'direction' => 'in',
            'qty' => '',
            'unit_cost' => '',
            'memo' => '',
        ];
        $this->showForm = true;
    }

    public function cancelForm(): void
    {
        $this->showForm = false;
    }

    public function saveAdjustment(InventoryService $inventory): void
    {
        abort_unless(auth()->user()?->hasPermission('inventory.adjust'), 403);

        $validated = $this->validate([
            'form.item_id' => ['required', 'exists:items,id'],
            'form.direction' => ['required', 'in:in,out'],
            'form.qty' => ['required', 'numeric', 'min:0.0001'],
            'form.unit_cost' => ['nullable', 'numeric', 'min:0'],
            'form.memo' => ['nullable', 'string', 'max:255'],
        ])['form'];

        $item = Item::query()->findOrFail($validated['item_id']);

        $payload = [
            'type' => 'adjustment',
            'memo' => $validated['memo'] ?: 'Manual inventory adjustment',
            'created_by' => auth()->id(),
            'allow_negative' => auth()->user()?->hasPermission('inventory.override') ?? false,
        ];

        if ($validated['direction'] === 'in') {
            $payload['qty_in'] = $validated['qty'];
            $payload['qty_out'] = 0;
            $payload['unit_cost'] = $validated['unit_cost'] !== '' && $validated['unit_cost'] !== null
                ? $validated['unit_cost']
                : $item->average_cost;
        } else {
            $payload['qty_in'] = 0;
            $payload['qty_out'] = $validated['qty'];
            $payload['unit_cost'] = $item->average_cost;
        }

        try {
            $inventory->post($item, $payload);
        } catch (RuntimeException $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return;
        }

        $this->showForm = false;
        $this->resetPage();
        $this->dispatch('be-toast', message: 'Inventory adjustment posted for '.$item->sku.'.');
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('inventory.adjust'), 403);

        $rows = InventoryTransaction::query()
            ->with('item')
            ->where('type', 'adjustment')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('memo', 'like', $like)
                        ->orWhereHas('item', function ($item) use ($like) {
                            $item->where('sku', 'like', $like)->orWhere('name', 'like', $like);
                        });
                });
            })
            ->latest('occurred_at')
            ->latest('id')
            ->limit(500)
            ->get()
            ->map(fn (InventoryTransaction $tx) => [
                $tx->occurred_at?->format('Y-m-d H:i'),
                $tx->item?->sku,
                $tx->item?->name,
                number_format((float) $tx->qty_in, 4, '.', ''),
                number_format((float) $tx->qty_out, 4, '.', ''),
                number_format((float) $tx->balance_after, 4, '.', ''),
                $tx->memo,
            ]);

        return $this->csvDownload(
            'inventory-adjustments.csv',
            ['When', 'SKU', 'Item', 'Qty In', 'Qty Out', 'Balance After', 'Memo'],
            $rows
        );
    }

    public function render()
    {
        $transactions = InventoryTransaction::query()
            ->with('item')
            ->where('type', 'adjustment')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('memo', 'like', $like)
                        ->orWhereHas('item', function ($item) use ($like) {
                            $item->where('sku', 'like', $like)->orWhere('name', 'like', $like);
                        });
                });
            })
            ->latest('occurred_at')
            ->latest('id')
            ->paginate(30);

        $itemOptions = ItemCatalog::activeItems(150)
            ->mapWithKeys(fn (Item $item) => [
                $item->id => ($item->barcode ?: $item->sku).' — '.($item->sales_description ?: $item->name).' (OH: '.$item->on_hand.')',
            ])
            ->all();

        return view('livewire.inventory.inventory-adjustment-index', [
            'transactions' => $transactions,
            'itemOptions' => $itemOptions,
        ])->layoutData([
            'title' => 'Inventory Adjustments',
            'windowTitle' => 'Inventory Adjustments',
        ]);
    }
}
