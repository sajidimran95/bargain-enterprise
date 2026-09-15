<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Development/demo dataset.
     *
     * Safe for local use:
     *   php artisan migrate:fresh --seed
     *
     * Do NOT run migrate:fresh in production.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            ChartOfAccountsSeeder::class,
            LookupTablesSeeder::class,
            VendorSeeder::class,
            CustomerSeeder::class,
            ItemSeeder::class,
            InventorySeeder::class,
            TransactionalDemoSeeder::class,
        ]);
    }
}
