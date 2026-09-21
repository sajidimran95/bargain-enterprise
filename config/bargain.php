<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Product & Company
    |--------------------------------------------------------------------------
    | product_name = app/developer brand (JapsPOS)
    | company_name = client company file name (Bargain Enterprise)
    */
    'product_name' => env('PRODUCT_NAME', 'JapsPOS'),
    'company_name' => env('COMPANY_NAME', 'Bargain Enterprise Inc.'),

    /*
    |--------------------------------------------------------------------------
    | Inventory negative stock policy: ALLOW | WARN | BLOCK
    |--------------------------------------------------------------------------
    */
    'inventory' => [
        'negative_policy' => env('INVENTORY_NEGATIVE_POLICY', 'WARN'),
        'allow_manager_override' => env('INVENTORY_ALLOW_MANAGER_OVERRIDE', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Primary admin account (cannot be deleted)
    |--------------------------------------------------------------------------
    */
    'primary_admin_email' => env('PRIMARY_ADMIN_EMAIL', 'admin@gmail.com'),
];
