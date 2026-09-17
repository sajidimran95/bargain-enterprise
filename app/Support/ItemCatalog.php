<?php

namespace App\Support;

use App\Models\Item;
use Illuminate\Support\Collection;

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
            ->orderByRaw("LOWER(COALESCE(NULLIF(barcode, ''), sku)) asc")
            ->orderByRaw('LOWER(name) asc');

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }

    /**
     * Select options: id => "CODE — Description" (alphabetical by code).
     *
     * @return array<int|string, string>
     */
    public static function selectOptions(?int $limit = 2000, bool $purchase = false): array
    {
        return self::activeItems($limit)
            ->mapWithKeys(function (Item $item) use ($purchase) {
                return [$item->id => self::labelFor($item, $purchase)];
            })
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

    protected static function labelFor(Item $item, bool $purchase): string
    {
        $code = (string) ($item->barcode ?: $item->sku);
        $label = $purchase
            ? ($item->purchase_description ?: $item->name)
            : ($item->sales_description ?: $item->name);

        return $code.' — '.$label;
    }
}
