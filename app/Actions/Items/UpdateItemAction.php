<?php

namespace App\Actions\Items;

use App\Models\Item;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UpdateItemAction
{
    public function __construct(protected AuditLogger $audit) {}

    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Item $item, array $data): Item
    {
        $validated = $this->validate($item, $data);

        return DB::transaction(function () use ($item, $validated) {
            $old = $this->audit->snapshot($item);
            $item->update($validated);
            $item->refresh();

            $new = $this->audit->snapshot($item);
            $changedOld = [];
            $changedNew = [];
            foreach (array_unique([...array_keys($old), ...array_keys($new)]) as $key) {
                $left = $old[$key] ?? null;
                $right = $new[$key] ?? null;
                if ($left != $right) {
                    $changedOld[$key] = $left;
                    $changedNew[$key] = $right;
                }
            }

            if ($changedNew !== []) {
                $this->audit->record('updated', $item, $changedOld, $changedNew);
            }

            return $item;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function validate(Item $item, array $data): array
    {
        $validator = Validator::make($data, [
            'sku' => ['required', 'string', 'max:100', Rule::unique('items', 'sku')->ignore($item->id)],
            'barcode' => ['nullable', 'string', 'max:64', Rule::unique('items', 'barcode')->ignore($item->id)],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:50'],
            'parent_id' => ['nullable', 'exists:items,id', Rule::notIn([$item->id])],
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

        return $validator->validated();
    }
}
