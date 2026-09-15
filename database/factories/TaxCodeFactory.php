<?php

namespace Database\Factories;

use App\Models\TaxCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaxCode>
 */
class TaxCodeFactory extends Factory
{
    protected $model = TaxCode::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->lexify('???')),
            'name' => fake()->words(2, true),
            'rate' => fake()->randomFloat(4, 0, 10),
            'is_active' => true,
        ];
    }
}
