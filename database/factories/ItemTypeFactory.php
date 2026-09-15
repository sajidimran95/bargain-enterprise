<?php

namespace Database\Factories;

use App\Models\ItemType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ItemType>
 */
class ItemTypeFactory extends Factory
{
    protected $model = ItemType::class;

    public function definition(): array
    {
        $label = fake()->unique()->word();

        return [
            'name' => strtolower($label),
            'label' => ucfirst($label),
            'description' => null,
            'is_active' => true,
        ];
    }
}
