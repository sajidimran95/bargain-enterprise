<?php

namespace App\Livewire\Items;

use App\Actions\Items\CreateItemAction;
use App\Actions\Items\UpdateItemAction;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemHistory;
use App\Models\ItemNote;
use App\Models\ItemType;
use App\Models\TaxCode;
use App\Models\UnitOfMeasure;
use App\Models\Vendor;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Item')]
class ItemForm extends Component
{
    use AuthorizesRequests;

    public ?Item $item = null;

    public string $sku = '';

    public string $barcode = '';

    public string $name = '';

    public string $type = 'inventory_part';

    public ?int $parent_id = null;

    public ?string $manufacturer_part_number = '';

    public ?int $unit_of_measure_id = null;

    public ?string $purchase_description = '';

    public string $purchase_cost = '0.00';

    public ?string $cogs_account = 'Cost of Goods Sold';

    public ?int $preferred_vendor_id = null;

    public ?string $sales_description = '';

    public string $sales_price = '0.00';

    public ?int $tax_code_id = null;

    public ?string $income_account = 'Sales';

    public ?string $asset_account = 'Inventory Asset';

    public ?string $reorder_min = null;

    public ?string $reorder_max = null;

    public ?int $item_category_id = null;

    public ?int $item_type_id = null;

    public ?string $items_per_container = null;

    public ?string $promotion = '';

    public bool $is_active = true;

    public bool $itemInactive = false;

    public bool $isSubitem = false;

    public bool $showCustomFields = false;

    public bool $showNotes = false;

    public bool $showSpelling = false;

    public bool $showHistory = false;

    public string $noteBody = '';

    public ?int $editingNoteId = null;

    /** @var array<int, array{field: string, label: string, issue: string, original: string, suggestion: string}> */
    public array $spellingIssues = [];

    public function mount(?Item $item = null): void
    {
        if ($item?->exists) {
            $this->authorize('update', $item);
            $this->item = $item;
            $data = $item->only([
                'sku', 'barcode', 'name', 'type', 'parent_id', 'manufacturer_part_number', 'unit_of_measure_id',
                'purchase_description', 'cogs_account', 'preferred_vendor_id', 'sales_description',
                'tax_code_id', 'income_account', 'asset_account', 'item_category_id', 'item_type_id',
                'promotion', 'is_active',
            ]);

            foreach ([
                'sku', 'barcode', 'name', 'type', 'manufacturer_part_number', 'purchase_description',
                'cogs_account', 'sales_description', 'income_account', 'asset_account', 'promotion',
            ] as $stringField) {
                $data[$stringField] = (string) ($data[$stringField] ?? '');
            }

            $this->fill($data);
            $this->purchase_cost = (string) $item->purchase_cost;
            $this->sales_price = (string) $item->sales_price;
            $this->reorder_min = $item->reorder_min !== null ? (string) $item->reorder_min : null;
            $this->reorder_max = $item->reorder_max !== null ? (string) $item->reorder_max : null;
            $this->items_per_container = $item->items_per_container !== null ? (string) $item->items_per_container : null;
            $this->itemInactive = ! $item->is_active;
            $this->isSubitem = $item->parent_id !== null;
        } else {
            $this->authorize('create', Item::class);
        }
    }

    public function updatedItemInactive(bool $value): void
    {
        $this->is_active = ! $value;
    }

    public function updatedIsSubitem(bool $value): void
    {
        if (! $value) {
            $this->parent_id = null;
        }
    }

    public function openCustomFields(): void
    {
        $this->showCustomFields = true;
    }

    public function closeCustomFields(): void
    {
        $this->showCustomFields = false;
    }

    public function acceptBarcode(): void
    {
        $code = trim($this->barcode);
        $this->barcode = $code;
        $this->resetErrorBag('barcode');

        if ($code === '') {
            $this->dispatch('be-toast', message: 'Barcode cleared. SKU will be used on save if still blank.');

            return;
        }

        if (strlen($code) > 64) {
            $this->addError('barcode', 'Barcode / UPC may not be longer than 64 characters.');

            return;
        }

        $duplicate = Item::query()
            ->when($this->item?->exists, fn ($q) => $q->where('id', '!=', $this->item->id))
            ->where(function ($q) use ($code) {
                $q->where('barcode', $code)->orWhere('sku', $code);
            })
            ->first();

        if ($duplicate) {
            $this->addError('barcode', 'Barcode already used by item '.$duplicate->sku.'.');
            $this->dispatch('be-toast', message: 'Barcode already used by '.$duplicate->sku.'.');

            return;
        }

        $this->dispatch('be-toast', message: 'Barcode accepted: '.$code);
        $this->dispatch('be-focus-barcode');
    }

    public function useSkuAsBarcode(): void
    {
        $this->barcode = trim($this->sku);
        $this->acceptBarcode();
    }

    public function clearBarcode(): void
    {
        $this->barcode = '';
        $this->resetErrorBag('barcode');
        $this->dispatch('be-toast', message: 'Barcode cleared.');
        $this->dispatch('be-focus-barcode');
    }

    public function openNotes(): void
    {
        if (! $this->ensureItemPersisted()) {
            return;
        }

        $this->noteBody = '';
        $this->editingNoteId = null;
        $this->showNotes = true;
    }

    public function closeNotes(): void
    {
        $this->showNotes = false;
        $this->noteBody = '';
        $this->editingNoteId = null;
    }

    public function saveNote(): void
    {
        if (! $this->ensureItemPersisted()) {
            return;
        }

        $this->authorize('update', $this->item);
        $this->validate(['noteBody' => ['required', 'string', 'max:5000']]);

        if ($this->editingNoteId) {
            ItemNote::query()
                ->where('item_id', $this->item->id)
                ->whereKey($this->editingNoteId)
                ->update(['body' => $this->noteBody]);
        } else {
            ItemNote::query()->create([
                'item_id' => $this->item->id,
                'user_id' => auth()->id(),
                'body' => $this->noteBody,
            ]);
        }

        $this->noteBody = '';
        $this->editingNoteId = null;
        $this->dispatch('be-toast', message: 'Note saved.');
    }

    public function editNote(int $noteId): void
    {
        $note = ItemNote::query()
            ->where('item_id', $this->item?->id)
            ->whereKey($noteId)
            ->firstOrFail();

        $this->editingNoteId = $note->id;
        $this->noteBody = $note->body;
        $this->showNotes = true;
    }

    public function deleteNote(int $noteId): void
    {
        ItemNote::query()
            ->where('item_id', $this->item?->id)
            ->whereKey($noteId)
            ->delete();

        if ($this->editingNoteId === $noteId) {
            $this->editingNoteId = null;
            $this->noteBody = '';
        }

        $this->dispatch('be-toast', message: 'Note deleted.');
    }

    public function checkSpelling(): void
    {
        $this->spellingIssues = $this->findSpellingIssues();
        $this->showSpelling = true;

        if ($this->spellingIssues === []) {
            $this->dispatch('be-toast', message: 'No spelling or formatting issues found.');
        }
    }

    public function closeSpelling(): void
    {
        $this->showSpelling = false;
    }

    public function applySpellingFixes(): void
    {
        foreach ($this->spellingIssues as $issue) {
            $field = $issue['field'];
            if (! property_exists($this, $field)) {
                continue;
            }

            $this->{$field} = $issue['suggestion'];
        }

        $this->spellingIssues = $this->findSpellingIssues();
        $this->dispatch('be-toast', message: 'Spelling and formatting fixes applied.');

        if ($this->spellingIssues === []) {
            $this->showSpelling = false;
        }
    }

    public function openHistory(): void
    {
        if (! $this->item?->exists) {
            $this->dispatch('be-toast', message: 'Save the item first to view history.');

            return;
        }

        $this->showHistory = true;
    }

    public function closeHistory(): void
    {
        $this->showHistory = false;
    }

    public function applySuggestedSalesPrice(int $historyId): void
    {
        if (! $this->item?->exists) {
            return;
        }

        $history = $this->item->histories()->whereKey($historyId)->first();
        if (! $history?->suggested_sales_price) {
            $this->dispatch('be-toast', message: 'No suggested sales price on this history row.');

            return;
        }

        $this->sales_price = number_format((float) $history->suggested_sales_price, 2, '.', '');
        $this->dispatch('be-toast', message: 'Sales price set to '.$this->sales_price.'. Click OK to save.');
        $this->showHistory = false;
    }

    public function save(CreateItemAction $create, UpdateItemAction $update): mixed
    {
        if (! $this->persistItem($create, $update)) {
            return null;
        }

        return redirect()->route('items.index');
    }

    public function render()
    {
        $title = $this->item ? 'Edit Item' : 'New Item';

        $typeHelp = match ($this->type) {
            'inventory_assembly' => 'Use for items built from other inventory parts.',
            'non_inventory' => 'Use for goods you buy or sell but do not track as inventory.',
            'service' => 'Use for services you charge for or purchase.',
            default => 'Use for goods you purchase, track as inventory, and resell.',
        };

        $notes = $this->item
            ? $this->item->notes()->with('user')->latest()->get()
            : collect();

        $histories = $this->item
            ? $this->item->histories()->with('createdBy')->latest('occurred_at')->latest('id')->limit(100)->get()
            : collect();

        $pendingCostAlert = $histories
            ->first(fn ($h) => $h->event === ItemHistory::EVENT_PURCHASE_COST
                && filled($h->suggested_sales_price)
                && bccomp((string) $h->suggested_sales_price, (string) $this->sales_price, 2) !== 0);

        return view('livewire.items.item-form', [
            'units' => UnitOfMeasure::query()->where('is_active', true)->orderBy('name')->get(),
            'vendors' => Vendor::query()->active()->orderBy('display_name')->get(),
            'taxCodes' => TaxCode::query()->where('is_active', true)->orderBy('code')->get(),
            'categories' => ItemCategory::query()->where('is_active', true)->orderBy('code')->get(),
            'itemTypes' => ItemType::query()->where('is_active', true)->orderBy('label')->get(),
            'parents' => Item::query()->active()->when($this->item, fn ($q) => $q->where('id', '!=', $this->item->id))->orderBy('sku')->limit(200)->get(),
            'typeHelp' => $typeHelp,
            'notes' => $notes,
            'noteCount' => $notes instanceof Collection ? $notes->count() : 0,
            'histories' => $histories,
            'pendingCostAlert' => $pendingCostAlert,
        ])->layoutData([
            'title' => $title,
            'windowTitle' => $title,
        ]);
    }

    protected function ensureItemPersisted(): bool
    {
        if ($this->item?->exists) {
            return true;
        }

        if (! filled($this->sku)) {
            $this->addError('sku', 'Enter an Item Name/Number before adding notes.');
            $this->dispatch('be-toast', message: 'Enter an Item Name/Number before adding notes.');

            return false;
        }

        return $this->persistItem(app(CreateItemAction::class), app(UpdateItemAction::class));
    }

    protected function persistItem(CreateItemAction $create, UpdateItemAction $update): bool
    {
        $this->is_active = ! $this->itemInactive;

        $payload = [
            'sku' => $this->sku,
            'barcode' => $this->barcode ?: $this->sku,
            'name' => $this->name ?: $this->sales_description ?: $this->sku,
            'type' => $this->type,
            'parent_id' => $this->isSubitem ? $this->parent_id : null,
            'manufacturer_part_number' => $this->manufacturer_part_number ?: null,
            'unit_of_measure_id' => $this->unit_of_measure_id,
            'purchase_description' => $this->purchase_description ?: null,
            'purchase_cost' => $this->purchase_cost,
            'cogs_account' => $this->cogs_account ?: null,
            'preferred_vendor_id' => $this->preferred_vendor_id,
            'sales_description' => $this->sales_description ?: null,
            'sales_price' => $this->sales_price,
            'tax_code_id' => $this->tax_code_id,
            'income_account' => $this->income_account ?: null,
            'asset_account' => $this->asset_account ?: null,
            'reorder_min' => $this->reorder_min !== null && $this->reorder_min !== '' ? $this->reorder_min : null,
            'reorder_max' => $this->reorder_max !== null && $this->reorder_max !== '' ? $this->reorder_max : null,
            'item_category_id' => $this->item_category_id,
            'item_type_id' => $this->item_type_id,
            'items_per_container' => $this->items_per_container !== null && $this->items_per_container !== '' ? $this->items_per_container : null,
            'promotion' => $this->promotion ?: null,
            'is_active' => $this->is_active,
        ];

        try {
            $item = $this->item
                ? $update->handle($this->item, $payload)
                : $create->handle($payload);
        } catch (ValidationException $e) {
            foreach ($e->errors() as $field => $messages) {
                foreach ($messages as $message) {
                    $this->addError($field, $message);
                }
            }

            return false;
        }

        $this->item = $item->fresh();

        return true;
    }

    public function cancelNoteEdit(): void
    {
        $this->editingNoteId = null;
        $this->noteBody = '';
    }

    /**
     * @return array<int, array{field: string, label: string, issue: string, original: string, suggestion: string}>
     */
    protected function findSpellingIssues(): array
    {
        $fields = [
            'name' => 'Name / Title',
            'purchase_description' => 'Purchase Description',
            'sales_description' => 'Sales Description',
            'promotion' => 'Promotion',
            'manufacturer_part_number' => "Manufacturer's Part Number",
        ];

        $issues = [];

        foreach ($fields as $field => $label) {
            $value = (string) ($this->{$field} ?? '');
            if ($value === '') {
                continue;
            }

            $suggestion = $this->normalizeText($value);
            if ($suggestion !== $value) {
                $issues[] = [
                    'field' => $field,
                    'label' => $label,
                    'issue' => 'Extra spaces or repeated words',
                    'original' => $value,
                    'suggestion' => $suggestion,
                ];
            }
        }

        return $issues;
    }

    protected function normalizeText(string $value): string
    {
        $normalized = preg_replace('/[ \t]+/', ' ', trim($value)) ?? trim($value);
        $normalized = preg_replace('/\s+\n/', "\n", $normalized) ?? $normalized;
        $normalized = preg_replace('/\n{3,}/', "\n\n", $normalized) ?? $normalized;
        $normalized = preg_replace('/\b(\w+)\s+\1\b/i', '$1', $normalized) ?? $normalized;

        return $normalized;
    }
}
