<?php

namespace App\Actions\Items;

use App\Models\Item;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateItemAction
{
    public function __construct(protected AuditLogger $audit) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): Item
    {
        $validated = $this->validate($data);

        return DB::transaction(function () use ($validated) {
            if (empty($validated['name']) && ! empty($validated['sales_description'])) {
                $validated['name'] = $validated['sales_description'];
            }

            $item = Item::query()->create($validated);

            $this->audit->record('created', $item, null, [
                'sku' => $item->sku,
                'name' => $item->name,
                'sales_price' => $item->sales_price,
                'is_active' => $item->is_active,
            ]);

            return $item;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function validate(array $data): array
    {
        $validator = Validator::make($data, [
            'sku' => ['required', 'string', 'max:100', 'unique:items,sku'],
            'barcode' => ['nullable', 'string', 'max:64'],
            'name' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50'],
            'parent_id' => ['nullable', 'exists:items,id'],
            'manufacturer_part_number' => ['nullable', 'string', 'max:100'],
            'unit_of_measure_id' => ['nullable', 'exists:units_of_measure,id'],
            'purchase_description' => ['nullable', 'string'],
            'purchase_cost' => ['nullable', 'numeric'],
            'cogs_account' => ['nullable', 'string', 'max:100'],
            'preferred_vendor_id' => ['nullable', 'exists:vendors,id'],
            'sales_description' => ['nullable', 'string'],
            'sales_price' => ['nullable', 'numeric'],
            'tax_code_id' => ['nullable', 'exists:tax_codes,id'],
            'income_account' => ['nullable', 'string', 'max:100'],
            'asset_account' => ['nullable', 'string', 'max:100'],
            'reorder_min' => ['nullable', 'numeric'],
            'reorder_max' => ['nullable', 'numeric'],
            'item_category_id' => ['nullable', 'exists:item_categories,id'],
            'item_type_id' => ['nullable', 'exists:item_types,id'],
            'items_per_container' => ['nullable', 'numeric'],
            'promotion' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();
        $validated['type'] = $validated['type'] ?? 'inventory_part';
        $validated['name'] = $validated['name'] ?? ($validated['sales_description'] ?? $validated['sku']);
        $validated['barcode'] = $validated['barcode'] ?? $validated['sku'];

        return $validated;
    }
}
