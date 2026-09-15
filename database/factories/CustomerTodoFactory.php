<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerTodo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerTodo>
 */
class CustomerTodoFactory extends Factory
{
    protected $model = CustomerTodo::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'assigned_to' => null,
            'title' => fake()->sentence(3),
            'notes' => null,
            'due_date' => fake()->optional()->date(),
            'is_completed' => false,
        ];
    }
}
