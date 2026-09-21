<?php

return [
    /*
    |--------------------------------------------------------------------------
    | QuickBooks-style sales report layouts (dynamic by selected type)
    |--------------------------------------------------------------------------
    */
    'layouts' => [
        'item_detail' => 'Sales by Item Detail',
        'customer_detail' => 'Sales by Customer Detail',
        'ship_to_detail' => 'Sales by Ship To Address',
        'rep_detail' => 'Sales by Rep Detail',
        'item_summary' => 'Sales by Item Summary',
        'customer_summary' => 'Sales by Customer Summary',
    ],

    'filenames' => [
        'item_detail' => 'sales-by-item-detail.xlsm',
        'customer_detail' => 'sales-by-customer-detail.xlsm',
        'ship_to_detail' => 'sales-by-ship-to-address.xlsm',
        'rep_detail' => 'sales-by-rep-detail.xlsm',
        'item_summary' => 'sales-by-item-summary.xlsm',
        'customer_summary' => 'sales-by-customer-summary.xlsm',
    ],

    /*
    |--------------------------------------------------------------------------
    | Columns per layout (from QuickBooks Desktop XLSM samples)
    | Leading '' = group / label column
    |--------------------------------------------------------------------------
    */
    'columns' => [
        'item_detail' => [
            '', 'Type', 'Date', 'Num', 'Memo', 'Name', 'Qty', 'U/M', 'Sales Price', 'Amount', 'Balance',
        ],
        'customer_detail' => [
            '', 'Type', 'Date', 'Num', 'Name Address', 'Name Street1', 'Name City', 'Name State', 'Name Zip',
            'Name Fax #', 'Memo', 'Name', 'Item', 'Qty', 'U/M', 'Sales Price', 'Amount', 'Balance',
        ],
        'ship_to_detail' => [
            '', 'Type', 'Date', 'Num', 'Ship To Address 1', 'Ship To Address 2', 'Ship Zip',
            'Name Address', 'Name Street1', 'Name City', 'Name State', 'Name Zip', 'Name Fax #',
            'Item', 'Account', 'Qty', 'Sales Price', 'Amount',
        ],
        'rep_detail' => [
            '', 'Type', 'Date', 'Num', 'Memo', 'Name', 'Item', 'Qty', 'U/M', 'Sales Price', 'Amount', 'Balance',
        ],
        'item_summary' => [
            '', 'Qty', 'Amount', '% of Sales', 'Avg Price', 'COGS', 'Avg COGS', 'Gross Margin', 'Gross Margin %',
        ],
        'customer_summary' => [
            '', 'Qty', 'Amount', '% of Sales', 'Avg Price', 'COGS', 'Avg COGS', 'Gross Margin', 'Gross Margin %',
        ],
    ],

    'numeric_columns' => [
        'Qty', 'Sales Price', 'Amount', 'Balance', '% of Sales', 'Avg Price', 'COGS', 'Avg COGS', 'Gross Margin', 'Gross Margin %',
    ],
];
