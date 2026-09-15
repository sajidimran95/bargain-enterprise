<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use App\Models\ItemType;
use App\Models\PriceLevel;
use App\Models\Setting;
use App\Models\TaxCode;
use App\Models\UnitOfMeasure;
use Illuminate\Database\Seeder;

class LookupTablesSeeder extends Seeder
{
    public function run(): void
    {
        Setting::setValue('company.name', 'Bargain Enterprise Inc.', 'string', 'company');
        Setting::setValue('inventory.negative_policy', 'WARN', 'string', 'inventory');
        Setting::setValue('inventory.allow_manager_override', true, 'boolean', 'inventory');

        foreach ([
            ['name' => 'Wholesale A', 'description' => 'Primary wholesale tier', 'adjustment_percent' => 0],
            ['name' => 'Wholesale B', 'description' => 'Volume wholesale tier', 'adjustment_percent' => -3],
            ['name' => 'Retail', 'description' => 'Retail counter pricing', 'adjustment_percent' => 12],
            ['name' => 'Special Customer', 'description' => 'Negotiated special pricing', 'adjustment_percent' => -5],
            ['name' => 'Zero', 'description' => 'No adjustment', 'adjustment_percent' => 0],
        ] as $level) {
            PriceLevel::query()->updateOrCreate(['name' => $level['name']], $level + ['is_active' => true]);
        }

        TaxCode::query()->updateOrCreate(['code' => 'Tax'], ['name' => 'Taxable (dev 6.35%)', 'rate' => 6.3500, 'is_active' => true]);
        TaxCode::query()->updateOrCreate(['code' => 'Non'], ['name' => 'Non-taxable', 'rate' => 0, 'is_active' => true]);

        foreach ([
            ['name' => 'Each', 'abbreviation' => 'ea'],
            ['name' => 'Pack', 'abbreviation' => 'pk'],
            ['name' => 'Box', 'abbreviation' => 'bx'],
            ['name' => 'Carton', 'abbreviation' => 'ctn'],
            ['name' => 'Case', 'abbreviation' => 'cs'],
        ] as $unit) {
            UnitOfMeasure::query()->updateOrCreate(['name' => $unit['name']], $unit + ['is_active' => true]);
        }

        foreach ([
            ['code' => '3251', 'name' => 'Cigars / Tobacco'],
            ['code' => '4101', 'name' => 'Vape / OTP'],
            ['code' => '5200', 'name' => 'Beverages'],
            ['code' => '5300', 'name' => 'Snacks'],
            ['code' => '5400', 'name' => 'Accessories'],
            ['code' => '5900', 'name' => 'Other'],
        ] as $category) {
            ItemCategory::query()->updateOrCreate(
                ['code' => $category['code']],
                $category + ['description' => 'Demo category for development', 'is_active' => true]
            );
        }

        foreach ([
            ['name' => 'tobacco', 'label' => 'Tobacco'],
            ['name' => 'vape', 'label' => 'Vape'],
            ['name' => 'beverage', 'label' => 'Beverage'],
            ['name' => 'snack', 'label' => 'Snack'],
            ['name' => 'accessory', 'label' => 'Accessory'],
            ['name' => 'general', 'label' => 'General'],
        ] as $type) {
            ItemType::query()->updateOrCreate(
                ['name' => $type['name']],
                $type + ['description' => null, 'is_active' => true]
            );
        }
    }
}
