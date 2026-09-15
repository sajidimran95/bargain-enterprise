<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerNote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerNote>
 */
class CustomerNoteFactory extends Factory
{
    protected $model = CustomerNote::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'user_id' => null,
            'body' => fake()->sentence(),
            'is_pinned' => false,
        ];
    }
}
