<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Company
    |--------------------------------------------------------------------------
    */
    'company_name' => env('COMPANY_NAME', 'Bargain Enterprise Inc.'),

    /*
    |--------------------------------------------------------------------------
    | Inventory negative stock policy: ALLOW | WARN | BLOCK
    |--------------------------------------------------------------------------
    | Source QuickBooks data contains large negatives. Default WARN until the
    | client confirms (see REQUIREMENTS.md Q2).
    */
    'inventory' => [
        'negative_policy' => env('INVENTORY_NEGATIVE_POLICY', 'WARN'),
        'allow_manager_override' => env('INVENTORY_ALLOW_MANAGER_OVERRIDE', true),
    ],
];
