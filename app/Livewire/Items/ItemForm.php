<?php

namespace App\Livewire\Items;

use App\Actions\Items\CreateItemAction;
use App\Actions\Items\UpdateItemAction;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemType;
use App\Models\TaxCode;
use App\Models\UnitOfMeasure;
use App\Models\Vendor;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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
        } else {
            $this->authorize('create', Item::class);
        }
    }

    public function save(CreateItemAction $create, UpdateItemAction $update): mixed
    {
        $payload = [
            'sku' => $this->sku,
            'barcode' => $this->barcode ?: $this->sku,
            'name' => $this->name ?: $this->sales_description ?: $this->sku,
            'type' => $this->type,
            'parent_id' => $this->parent_id,
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

            return null;
        }

        return redirect()->route('items.index');
    }

    public function render()
    {
        $title = $this->item ? 'Edit Item' : 'New Item';

        return view('livewire.items.item-form', [
            'units' => UnitOfMeasure::query()->where('is_active', true)->orderBy('name')->get(),
            'vendors' => Vendor::query()->active()->orderBy('display_name')->get(),
            'taxCodes' => TaxCode::query()->where('is_active', true)->orderBy('code')->get(),
            'categories' => ItemCategory::query()->where('is_active', true)->orderBy('code')->get(),
            'itemTypes' => ItemType::query()->where('is_active', true)->orderBy('label')->get(),
            'parents' => Item::query()->active()->when($this->item, fn ($q) => $q->where('id', '!=', $this->item->id))->orderBy('sku')->limit(200)->get(),
        ])->layoutData([
            'title' => $title,
            'windowTitle' => $title,
        ]);
    }
}
