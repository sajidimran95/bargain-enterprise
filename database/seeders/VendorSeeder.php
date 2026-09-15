<?php

namespace Database\Seeders;

use App\Models\Vendor;
use App\Models\VendorContact;
use Database\Seeders\Support\DemoCatalog;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        foreach (DemoCatalog::vendors() as $index => $name) {
            $inactive = str_contains($name, 'Inactive');
            $vendor = Vendor::query()->updateOrCreate(
                ['display_name' => $name],
                [
                    'vendor_number' => 'V'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                    'company_name' => $name,
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'email' => 'orders'.($index + 1).'@vendor-demo.local',
                    'phone' => '860-555-'.str_pad((string) (2000 + $index), 4, '0', STR_PAD_LEFT),
                    'bill_from_street1' => fake()->numberBetween(100, 800).' Industrial Pkwy',
                    'bill_from_city' => fake()->randomElement(['New Haven', 'Hartford', 'Bridgeport', 'Stamford']),
                    'bill_from_state' => 'CT',
                    'bill_from_zip' => '06'.fake()->numberBetween(100, 999),
                    'bill_from_country' => 'USA',
                    'terms' => 'Net 30',
                    'account_number' => 'A'.(10000 + $index),
                    'balance' => 0,
                    'notes' => $index % 4 === 0 ? 'Preferred weekly delivery Tuesday.' : null,
                    'is_active' => ! $inactive,
                ]
            );

            VendorContact::query()->updateOrCreate(
                ['vendor_id' => $vendor->id, 'name' => $vendor->fullName()],
                [
                    'title' => 'Sales Rep',
                    'email' => $vendor->email,
                    'phone' => $vendor->phone,
                    'is_primary' => true,
                ]
            );
        }
    }
}
