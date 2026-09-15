<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\PriceLevel;
use App\Models\TaxCode;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        $company = fake()->company();

        return [
            'customer_number' => fake()->optional()->numerify('######'),
            'company_name' => $company,
            'display_name' => $company,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->optional()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'bill_to_street1' => fake()->streetAddress(),
            'bill_to_city' => fake()->city(),
            'bill_to_state' => fake()->stateAbbr(),
            'bill_to_zip' => fake()->postcode(),
            'price_level_id' => PriceLevel::factory(),
            'tax_code_id' => TaxCode::factory(),
            'balance' => fake()->randomFloat(2, 0, 5000),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
