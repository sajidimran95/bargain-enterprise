<?php

namespace App\Actions\Lookups;

use App\Models\ItemCategory;
use App\Models\ItemType;
use App\Models\PaymentMethod;
use App\Models\PriceLevel;
use App\Models\TaxCode;
use App\Models\UnitOfMeasure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use InvalidArgumentException;

class UpsertLookupAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(string $type, array $data, ?int $id = null): Model
    {
        return match ($type) {
            'price_levels' => $this->upsert(PriceLevel::class, $this->validatePriceLevel($data, $id), $id),
            'tax_codes' => $this->upsert(TaxCode::class, $this->validateTaxCode($data, $id), $id),
            'units' => $this->upsert(UnitOfMeasure::class, $this->validateUnit($data, $id), $id),
            'categories' => $this->upsert(ItemCategory::class, $this->validateCategory($data, $id), $id),
            'item_types' => $this->upsert(ItemType::class, $this->validateItemType($data, $id), $id),
            'payment_methods' => $this->upsert(PaymentMethod::class, $this->validatePaymentMethod($data, $id), $id),
            default => throw new InvalidArgumentException("Unknown lookup type [{$type}]."),
        };
    }

    /**
     * @param  class-string<Model>  $modelClass
     * @param  array<string, mixed>  $data
     */
    protected function upsert(string $modelClass, array $data, ?int $id): Model
    {
        if ($id) {
            $model = $modelClass::query()->findOrFail($id);
            $model->update($data);

            return $model->refresh();
        }

        return $modelClass::query()->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function validatePriceLevel(array $data, ?int $id): array
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100', Rule::unique('price_levels', 'name')->ignore($id)],
            'description' => ['nullable', 'string', 'max:255'],
            'adjustment_percent' => ['nullable', 'numeric'],
            'is_active' => ['sometimes', 'boolean'],
        ])->validate();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function validateTaxCode(array $data, ?int $id): array
    {
        return Validator::make($data, [
            'code' => ['required', 'string', 'max:50', Rule::unique('tax_codes', 'code')->ignore($id)],
            'name' => ['required', 'string', 'max:100'],
            'rate' => ['required', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ])->validate();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function validateUnit(array $data, ?int $id): array
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100', Rule::unique('units_of_measure', 'name')->ignore($id)],
            'abbreviation' => ['nullable', 'string', 'max:20'],
            'is_active' => ['sometimes', 'boolean'],
        ])->validate();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function validateCategory(array $data, ?int $id): array
    {
        return Validator::make($data, [
            'code' => ['required', 'string', 'max:50', Rule::unique('item_categories', 'code')->ignore($id)],
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ])->validate();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function validateItemType(array $data, ?int $id): array
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100', Rule::unique('item_types', 'name')->ignore($id)],
            'label' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ])->validate();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function validatePaymentMethod(array $data, ?int $id): array
    {
        return Validator::make($data, [
            'code' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('payment_methods', 'code')->ignore($id)],
            'name' => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ])->validate();
    }
}
