<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\ItemType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Item>
 */
class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'sku' => fake()->unique()->ean13(),
            'barcode' => null,
            'name' => $name,
            'type' => 'inventory_part',
            'sales_description' => $name,
            'purchase_description' => $name,
            'sales_price' => fake()->randomFloat(2, 1, 100),
            'purchase_cost' => fake()->randomFloat(2, 0, 50),
            'on_hand' => fake()->randomFloat(4, -100, 500),
            'item_category_id' => ItemCategory::factory(),
            'item_type_id' => ItemType::factory(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
