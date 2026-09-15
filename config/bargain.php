<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Product & Company
    |--------------------------------------------------------------------------
    | UI follows QuickBooks Desktop POS style; brand shown to users is JapsPOS.
    */
    'product_name' => env('PRODUCT_NAME', 'JapsPOS'),
    'company_name' => env('COMPANY_NAME', 'JapsPOS Inc.'),

    /*
    |--------------------------------------------------------------------------
    | Inventory negative stock policy: ALLOW | WARN | BLOCK
    |--------------------------------------------------------------------------
    */
    'inventory' => [
        'negative_policy' => env('INVENTORY_NEGATIVE_POLICY', 'WARN'),
        'allow_manager_override' => env('INVENTORY_ALLOW_MANAGER_OVERRIDE', true),
    ],
];
