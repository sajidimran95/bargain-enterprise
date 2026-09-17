<?php

namespace App\Models;

use Database\Factories\ItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'sku',
    'barcode',
    'name',
    'type',
    'parent_id',
    'manufacturer_part_number',
    'unit_of_measure_id',
    'purchase_description',
    'purchase_cost',
    'cogs_account',
    'preferred_vendor_id',
    'sales_description',
    'sales_price',
    'tax_code_id',
    'income_account',
    'asset_account',
    'reorder_min',
    'reorder_max',
    'on_hand',
    'average_cost',
    'on_po_qty',
    'on_so_qty',
    'item_category_id',
    'item_type_id',
    'items_per_container',
    'promotion',
    'is_active',
])]
class Item extends Model
{
    /** @use HasFactory<ItemFactory> */
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'purchase_cost' => 'decimal:2',
            'sales_price' => 'decimal:2',
            'reorder_min' => 'decimal:4',
            'reorder_max' => 'decimal:4',
            'on_hand' => 'decimal:4',
            'average_cost' => 'decimal:4',
            'on_po_qty' => 'decimal:4',
            'on_so_qty' => 'decimal:4',
            'items_per_container' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function unitOfMeasure(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class);
    }

    public function preferredVendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'preferred_vendor_id');
    }

    public function taxCode(): BelongsTo
    {
        return $this->belongsTo(TaxCode::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ItemCategory::class, 'item_category_id');
    }

    public function itemType(): BelongsTo
    {
        return $this->belongsTo(ItemType::class);
    }

    public function inventoryTransactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ItemHistory::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ItemPrice::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(ItemNote::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeSearch(Builder $query, ?string $term, string $field = 'all'): Builder
    {
        if (! filled($term)) {
            return $query;
        }

        $like = '%'.$term.'%';

        return match ($field) {
            'sku' => $query->where('sku', 'like', $like),
            'name' => $query->where('name', 'like', $like),
            'description' => $query->where(function (Builder $q) use ($like) {
                $q->where('sales_description', 'like', $like)
                    ->orWhere('purchase_description', 'like', $like);
            }),
            default => $query->where(function (Builder $q) use ($like) {
                $q->where('sku', 'like', $like)
                    ->orWhere('barcode', 'like', $like)
                    ->orWhere('name', 'like', $like)
                    ->orWhere('sales_description', 'like', $like)
                    ->orWhere('purchase_description', 'like', $like)
                    ->orWhere('promotion', 'like', $like)
                    ->orWhere('manufacturer_part_number', 'like', $like);
            }),
        };
    }

    public function scopeByScanCode(Builder $query, string $code): Builder
    {
        return $query->where(function (Builder $q) use ($code) {
            $q->where('barcode', $code)
                ->orWhere('sku', $code)
                ->orWhere('manufacturer_part_number', $code);
        });
    }

    public function tracksInventory(): bool
    {
        return in_array($this->type, ['inventory_part', 'inventory_assembly'], true);
    }
}
