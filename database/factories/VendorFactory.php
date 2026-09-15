<?php

namespace Database\Factories;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vendor>
 */
class VendorFactory extends Factory
{
    protected $model = Vendor::class;

    public function definition(): array
    {
        $company = fake()->company();

        return [
            'vendor_number' => fake()->optional()->numerify('V#####'),
            'company_name' => $company,
            'display_name' => $company,
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->optional()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'bill_from_city' => fake()->city(),
            'bill_from_state' => fake()->stateAbbr(),
            'is_active' => true,
        ];
    }
}
