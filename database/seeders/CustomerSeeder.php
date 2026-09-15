<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerNote;
use App\Models\PriceLevel;
use App\Models\TaxCode;
use Database\Seeders\Support\DemoCatalog;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $tax = TaxCode::query()->where('code', 'Tax')->first();
        $levels = PriceLevel::query()->pluck('id', 'name');
        $businesses = DemoCatalog::connecticutBusinesses();

        foreach ($businesses as $index => $biz) {
            $isInactive = str_contains($biz['name'], 'Inactive');
            $levelName = match (true) {
                $index % 5 === 0 => 'Retail',
                $index % 4 === 0 => 'Special Customer',
                $index % 3 === 0 => 'Wholesale B',
                $index % 7 === 0 => 'Zero',
                default => 'Wholesale A',
            };

            $customer = Customer::query()->updateOrCreate(
                ['display_name' => $biz['name']],
                [
                    'customer_number' => (string) (2341000 + $index),
                    'company_name' => explode(' ', $biz['name'])[0],
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'email' => strtolower(preg_replace('/[^a-z0-9]+/i', '', $biz['name'])).'@demo.local',
                    'phone' => '203-555-'.str_pad((string) (1000 + $index), 4, '0', STR_PAD_LEFT),
                    'bill_to_street1' => fake()->numberBetween(10, 900).' '.fake()->streetName(),
                    'bill_to_city' => $biz['city'],
                    'bill_to_state' => $biz['state'],
                    'bill_to_zip' => $biz['zip'],
                    'bill_to_country' => 'USA',
                    'price_level_id' => $levels[$levelName] ?? null,
                    'tax_code_id' => $tax?->id,
                    'terms' => $index % 2 === 0 ? 'Net 15' : 'Net 30',
                    'credit_limit' => $index % 3 === 0 ? 10000 : 5000,
                    'balance' => 0,
                    'online_payment_eligible' => $index % 4 === 0,
                    'pinned_note' => $index % 6 === 0 ? 'Prefers morning deliveries.' : null,
                    'is_active' => ! $isInactive,
                ]
            );

            CustomerContact::query()->updateOrCreate(
                ['customer_id' => $customer->id, 'name' => $customer->fullName()],
                [
                    'title' => 'Primary Contact',
                    'email' => $customer->email,
                    'phone' => $customer->phone,
                    'is_primary' => true,
                ]
            );

            if ($index % 3 === 0) {
                CustomerContact::query()->updateOrCreate(
                    ['customer_id' => $customer->id, 'name' => 'Billing Desk'],
                    [
                        'title' => 'Billing Contact',
                        'email' => 'billing'.$index.'@demo.local',
                        'phone' => $customer->phone,
                        'is_primary' => false,
                    ]
                );
                CustomerContact::query()->updateOrCreate(
                    ['customer_id' => $customer->id, 'name' => 'Purchasing Manager'],
                    [
                        'title' => 'Purchasing Contact',
                        'email' => 'buy'.$index.'@demo.local',
                        'phone' => $customer->phone,
                        'is_primary' => false,
                    ]
                );
            }

            if ($index % 5 === 0) {
                CustomerNote::query()->firstOrCreate(
                    ['customer_id' => $customer->id, 'body' => 'Established account — weekly tobacco order.'],
                    ['user_id' => null, 'is_pinned' => true]
                );
            }
        }
    }
}
