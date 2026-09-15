<?php

namespace Database\Factories;

use App\Models\PriceLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PriceLevel>
 */
class PriceLevelFactory extends Factory
{
    protected $model = PriceLevel::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'description' => fake()->sentence(),
            'adjustment_percent' => fake()->randomFloat(2, -10, 15),
            'is_active' => true,
        ];
    }
}
