<?php

namespace App\Actions\Lookups;

use App\Models\ItemCategory;
use App\Models\ItemType;
use App\Models\PriceLevel;
use App\Models\TaxCode;
use App\Models\UnitOfMeasure;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use RuntimeException;

class DeleteLookupAction
{
    public function handle(string $type, int $id): void
    {
        $record = $this->find($type, $id);

        if ($this->isInUse($type, $record)) {
            throw new RuntimeException('Cannot delete this lookup because it is in use. Deactivate it instead.');
        }

        $record->delete();
    }

    protected function find(string $type, int $id): Model
    {
        return match ($type) {
            'price_levels' => PriceLevel::query()->findOrFail($id),
            'tax_codes' => TaxCode::query()->findOrFail($id),
            'units' => UnitOfMeasure::query()->findOrFail($id),
            'categories' => ItemCategory::query()->findOrFail($id),
            'item_types' => ItemType::query()->findOrFail($id),
            default => throw new InvalidArgumentException("Unknown lookup type [{$type}]."),
        };
    }

    protected function isInUse(string $type, Model $record): bool
    {
        return match ($type) {
            'price_levels' => $record->customers()->exists(),
            'tax_codes' => $record->customers()->exists() || $record->items()->exists(),
            'units' => $record->items()->exists(),
            'categories' => $record->items()->exists(),
            'item_types' => $record->items()->exists(),
            default => false,
        };
    }
}
