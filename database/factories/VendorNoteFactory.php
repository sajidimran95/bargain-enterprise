<?php

namespace Database\Factories;

use App\Models\Vendor;
use App\Models\VendorNote;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VendorNote>
 */
class VendorNoteFactory extends Factory
{
    protected $model = VendorNote::class;

    public function definition(): array
    {
        return [
            'vendor_id' => Vendor::factory(),
            'user_id' => null,
            'body' => fake()->sentence(),
        ];
    }
}
