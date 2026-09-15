<?php

namespace Database\Factories;

use App\Models\Vendor;
use App\Models\VendorContact;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VendorContact>
 */
class VendorContactFactory extends Factory
{
    protected $model = VendorContact::class;

    public function definition(): array
    {
        return [
            'vendor_id' => Vendor::factory(),
            'name' => fake()->name(),
            'email' => fake()->optional()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'is_primary' => false,
        ];
    }
}
