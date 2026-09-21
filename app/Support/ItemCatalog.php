<?php

namespace App\Support;

use App\Models\Item;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ItemCatalog
{
    /**
     * Active items ordered A–Z by item code (barcode, else SKU), then name.
     *
     * @return Collection<int, Item>
     */
    public static function activeItems(?int $limit = null): Collection
    {
        $query = Item::query()
            ->active()
            ->select([
                'id',
                'sku',
                'barcode',
                'name',
                'sales_description',
                'purchase_description',
                'sales_price',
                'purchase_cost',
                'on_hand',
                'type',
                'is_active',
            ])
            ->orderByRaw("LOWER(COALESCE(NULLIF(barcode, ''), sku)) asc")
            ->orderByRaw('LOWER(name) asc');

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Select options: id => "CODE — Description" (alphabetical by code).
     * Cached briefly so document forms do not re-query the full catalog every Livewire round-trip.
     *
     * @return array<int|string, string>
     */
    public static function selectOptions(?int $limit = 150, bool $purchase = false): array
    {
        $limit ??= 150;
        $cacheKey = 'item_catalog.options.'.($purchase ? 'purchase' : 'sales').'.'.$limit;

        return Cache::remember($cacheKey, 60, function () use ($limit, $purchase) {
            return self::activeItems($limit)
                ->mapWithKeys(fn (Item $item) => [$item->id => self::labelFor($item, $purchase)])
                ->all();
        });
    }

    /**
     * Slim dropdown options for already-selected line items (forms that also use item search).
     *
     * @param  array<int, array<string, mixed>>  $lines
     * @return array<int|string, string>
     */
    public static function optionsForLineItems(array $lines, bool $purchase = false): array
    {
        $ids = collect($lines)
            ->pluck('item_id')
            ->filter(fn ($id) => filled($id) && (int) $id > 0)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if ($ids === []) {
            return [];
        }

        return Item::query()
            ->whereIn('id', $ids)
            ->select([
                'id',
                'sku',
                'barcode',
                'name',
                'sales_description',
                'purchase_description',
                'sales_price',
                'purchase_cost',
                'type',
                'is_active',
            ])
            ->get()
            ->mapWithKeys(fn (Item $item) => [$item->id => self::labelFor($item, $purchase)])
            ->all();
    }

    /**
     * Search matches for the item search bar (top N, A–Z by code).
     *
     * @return list<array{id: int, code: string, label: string}>
     */
    public static function search(string $query, int $limit = 5, bool $purchase = false): array
    {
        $query = trim($query);
        if ($query === '' || $limit < 1) {
            return [];
        }

        $like = '%'.mb_strtolower($query).'%';

        return Item::query()
            ->active()
            ->select([
                'id',
                'sku',
                'barcode',
                'name',
                'sales_description',
                'purchase_description',
                'sales_price',
                'purchase_cost',
                'type',
                'is_active',
            ])
            ->where(function ($q) use ($like) {
                $q->whereRaw('LOWER(barcode) like ?', [$like])
                    ->orWhereRaw('LOWER(sku) like ?', [$like])
                    ->orWhereRaw('LOWER(manufacturer_part_number) like ?', [$like])
                    ->orWhereRaw('LOWER(name) like ?', [$like])
                    ->orWhereRaw('LOWER(sales_description) like ?', [$like])
                    ->orWhereRaw('LOWER(purchase_description) like ?', [$like]);
            })
            ->orderByRaw("LOWER(COALESCE(NULLIF(barcode, ''), sku)) asc")
            ->orderByRaw('LOWER(name) asc')
            ->limit($limit)
            ->get()
            ->map(fn (Item $item) => [
                'id' => (int) $item->id,
                'code' => (string) ($item->barcode ?: $item->sku),
                'label' => self::labelFor($item, $purchase),
            ])
            ->values()
            ->all();
    }

    public static function forgetCachedOptions(): void
    {
        foreach (['sales', 'purchase'] as $mode) {
            foreach ([100, 150, 200, 500, 2000] as $limit) {
                Cache::forget('item_catalog.options.'.$mode.'.'.$limit);
            }
        }
    }

    protected static function labelFor(Item $item, bool $purchase): string
    {
        $code = (string) ($item->barcode ?: $item->sku);
        $description = $purchase
            ? ($item->purchase_description ?: $item->name)
            : ($item->sales_description ?: $item->name);

        return $code.' — '.$description;
    }
}
