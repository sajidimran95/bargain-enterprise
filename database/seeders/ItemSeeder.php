<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemPrice;
use App\Models\ItemType;
use App\Models\PriceLevel;
use App\Models\TaxCode;
use App\Models\UnitOfMeasure;
use App\Models\Vendor;
use Database\Seeders\Support\DemoCatalog;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $tax = TaxCode::query()->where('code', 'Tax')->first();
        $each = UnitOfMeasure::query()->where('name', 'Each')->first();
        $case = UnitOfMeasure::query()->where('name', 'Case')->first();
        $categories = ItemCategory::query()->pluck('id', 'code');
        $types = ItemType::query()->pluck('id', 'name');
        $vendors = Vendor::query()->active()->get();
        $levels = PriceLevel::query()->get()->keyBy('name');

        foreach (DemoCatalog::products() as $index => $product) {
            $vendor = $vendors[$index % max(1, $vendors->count())] ?? null;
            $item = Item::query()->updateOrCreate(
                ['sku' => $product['sku']],
                [
                    'name' => $product['name'],
                    'type' => 'inventory_part',
                    'sales_description' => $product['name'],
                    'purchase_description' => $product['name'],
                    'purchase_cost' => $product['cost'],
                    'sales_price' => $product['price'],
                    'unit_of_measure_id' => $product['pack'] >= 12 ? ($case?->id ?? $each?->id) : $each?->id,
                    'preferred_vendor_id' => $vendor?->id,
                    'tax_code_id' => $tax?->id,
                    'cogs_account' => 'Cost of Goods Sold',
                    'income_account' => 'Sales',
                    'asset_account' => 'Inventory Asset',
                    'reorder_min' => 10,
                    'reorder_max' => 200,
                    'on_hand' => 0,
                    'average_cost' => $product['cost'],
                    'item_category_id' => $categories[$product['category']] ?? null,
                    'item_type_id' => $types[$product['type']] ?? null,
                    'items_per_container' => $product['pack'],
                    'promotion' => $product['promo'] ?? null,
                    'is_active' => $index % 25 !== 0,
                ]
            );

            $retail = (float) $product['price'];
            $prices = [
                'Wholesale A' => round($retail * 0.88, 2),
                'Wholesale B' => round($retail * 0.85, 2),
                'Retail' => $retail,
                'Special Customer' => round($retail * 0.82, 2),
                'Zero' => $retail,
            ];

            foreach ($prices as $levelName => $price) {
                if (! isset($levels[$levelName])) {
                    continue;
                }
                ItemPrice::query()->updateOrCreate(
                    [
                        'item_id' => $item->id,
                        'price_level_id' => $levels[$levelName]->id,
                    ],
                    ['price' => $price]
                );
            }
        }
    }
}
