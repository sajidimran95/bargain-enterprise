<?php

/**
 * Shared QuickBooks-style report catalog for the Reports menubar and Report Center.
 * Only routes that exist in WorkspaceCatalog should use `route`; others use coming_soon.
 */
return [
    'tools' => [
        ['label' => 'Report Center', 'route' => 'reports.index'],
        [
            'label' => 'Memorized Reports',
            'children' => [
                ['label' => 'Memorized Report List', 'coming_soon' => true],
                [
                    'label' => 'Accountant',
                    'children' => [
                        ['label' => 'Adjusted Trial Balance', 'coming_soon' => true],
                        ['label' => 'Balance Sheet', 'coming_soon' => true],
                        ['label' => 'General Ledger', 'coming_soon' => true],
                        ['label' => 'Profit & Loss', 'coming_soon' => true],
                    ],
                ],
                [
                    'label' => 'Banking',
                    'children' => [
                        ['label' => 'Deposit Detail', 'coming_soon' => true],
                        ['label' => 'Check Detail', 'coming_soon' => true],
                    ],
                ],
                [
                    'label' => 'Company',
                    'children' => [
                        ['label' => 'Balance Sheet', 'coming_soon' => true],
                        ['label' => 'Profit & Loss', 'coming_soon' => true],
                        ['label' => 'Statement of Cash Flows', 'coming_soon' => true],
                    ],
                ],
                [
                    'label' => 'Customers',
                    'children' => [
                        ['label' => 'A/R Aging Summary', 'coming_soon' => true],
                        ['label' => 'Customer Balance Detail', 'route' => 'reports.open-balance'],
                        ['label' => 'Customer Balance Summary', 'route' => 'reports.open-balance'],
                        ['label' => 'Open Invoices', 'coming_soon' => true],
                        ['label' => 'Accounts Receivable Graph', 'coming_soon' => true],
                    ],
                ],
                [
                    'label' => 'Employees',
                    'children' => [
                        ['label' => 'Payroll Item Detail', 'coming_soon' => true],
                        ['label' => 'Payroll Liability Balances', 'coming_soon' => true],
                        ['label' => 'Payroll Summary', 'coming_soon' => true],
                    ],
                ],
                ['label' => 'Item Listing', 'route' => 'reports.inventory'],
                [
                    'label' => 'Manufacturing and Wholesale',
                    'children' => [
                        ['label' => 'Inventory Reorder Report by Vendor', 'coming_soon' => true],
                        ['label' => 'Open Purchase Orders by Item', 'coming_soon' => true],
                        ['label' => 'Open Sales Orders by Item', 'coming_soon' => true],
                        ['label' => 'Profitability by Product', 'coming_soon' => true],
                        ['label' => 'Sales Volume by Customer', 'route' => 'reports.sales-by-item'],
                    ],
                ],
                ['label' => 'Sales by Item Detail', 'route' => 'reports.sales-by-item'],
                [
                    'label' => 'Vendors',
                    'children' => [
                        ['label' => 'A/P Aging Summary', 'coming_soon' => true],
                        ['label' => 'Unpaid Bills Detail', 'coming_soon' => true],
                        ['label' => 'Vendor Balance Detail', 'coming_soon' => true],
                    ],
                ],
            ],
        ],
        [
            'label' => 'Scheduled Reports',
            'children' => [
                ['label' => 'Scheduled Reports List', 'coming_soon' => true],
            ],
        ],
        ['label' => 'Commented Reports', 'coming_soon' => true],
        ['label' => 'Cash Flow Hub', 'coming_soon' => true],
        ['separator' => true],
        ['label' => 'Company Snapshot', 'route' => 'dashboard.snapshots'],
        ['label' => 'Advanced Reporting', 'coming_soon' => true],
        ['label' => 'Process Multiple Reports', 'coming_soon' => true],
        ['label' => 'QuickBooks Desktop Statement Writer', 'coming_soon' => true],
        ['label' => 'Combine Reports from Multiple Companies', 'coming_soon' => true],
    ],

    'categories' => [
        [
            'id' => 'mfg-wholesale',
            'label' => 'Manufacturing and Wholesale Reports',
            'short' => 'Mfg & Wholesale',
            'groups' => [
                [
                    'title' => 'Sales Volume',
                    'reports' => [
                        ['label' => 'Sales Volume by Customer', 'route' => 'reports.sales-by-item'],
                        ['label' => 'Sales by Rep Detail', 'coming_soon' => true],
                        ['label' => 'Sales by Product', 'route' => 'reports.sales-by-item'],
                    ],
                ],
                [
                    'title' => 'Open Orders',
                    'reports' => [
                        ['label' => 'Open Sales Orders by Item', 'coming_soon' => true],
                        ['label' => 'Open Purchase Orders by Item', 'coming_soon' => true],
                    ],
                ],
                [
                    'title' => 'Inventory',
                    'reports' => [
                        ['label' => 'Inventory Reorder Report by Vendor', 'coming_soon' => true],
                        ['label' => 'Profitability by Product', 'coming_soon' => true],
                        ['label' => 'MSA Inventory', 'route' => 'reports.inventory'],
                    ],
                ],
            ],
        ],
        [
            'id' => 'company-financial',
            'label' => 'Company & Financial',
            'groups' => [
                [
                    'reports' => [
                        ['label' => 'Profit & Loss', 'coming_soon' => true],
                        ['label' => 'Balance Sheet', 'coming_soon' => true],
                        ['label' => 'Statement of Cash Flows', 'coming_soon' => true],
                        ['label' => 'Trial Balance', 'coming_soon' => true],
                        ['label' => 'General Ledger', 'coming_soon' => true],
                    ],
                ],
            ],
        ],
        [
            'id' => 'customers-receivables',
            'label' => 'Customers & Receivables',
            'groups' => [
                [
                    'reports' => [
                        ['label' => 'A/R Aging Summary', 'coming_soon' => true],
                        ['label' => 'A/R Aging Detail', 'coming_soon' => true],
                        ['label' => 'Customer Balance Summary', 'route' => 'reports.open-balance'],
                        ['label' => 'Customer Balance Detail', 'route' => 'reports.open-balance'],
                        ['label' => 'Open Invoices', 'coming_soon' => true],
                        ['label' => 'Accounts Receivable Graph', 'coming_soon' => true],
                        ['label' => 'MSA Customer List', 'route' => 'reports.customers'],
                        ['label' => 'Collections Report', 'coming_soon' => true],
                        ['label' => 'Average Days to Pay Summary', 'coming_soon' => true],
                    ],
                ],
            ],
        ],
        [
            'id' => 'sales',
            'label' => 'Sales',
            'groups' => [
                [
                    'reports' => [
                        ['label' => 'Sales by Item Summary', 'route' => 'reports.sales-by-item'],
                        ['label' => 'Sales by Item Detail', 'route' => 'reports.sales-by-item'],
                        ['label' => 'MSA Sales Report', 'route' => 'reports.sales-by-item'],
                        ['label' => 'Sales by Customer Summary', 'coming_soon' => true],
                        ['label' => 'Sales by Customer Detail', 'coming_soon' => true],
                        ['label' => 'Sales by Rep Summary', 'coming_soon' => true],
                        ['label' => 'Sales Graph', 'coming_soon' => true],
                    ],
                ],
            ],
        ],
        [
            'id' => 'jobs-time',
            'label' => 'Jobs, Time & Mileage',
            'groups' => [
                [
                    'reports' => [
                        ['label' => 'Time by Job Summary', 'coming_soon' => true],
                        ['label' => 'Time by Job Detail', 'coming_soon' => true],
                        ['label' => 'Mileage by Vehicle', 'coming_soon' => true],
                    ],
                ],
            ],
        ],
        [
            'id' => 'vendors-payables',
            'label' => 'Vendors & Payables',
            'groups' => [
                [
                    'reports' => [
                        ['label' => 'A/P Aging Summary', 'coming_soon' => true],
                        ['label' => 'A/P Aging Detail', 'coming_soon' => true],
                        ['label' => 'Vendor Balance Summary', 'coming_soon' => true],
                        ['label' => 'Vendor Balance Detail', 'coming_soon' => true],
                        ['label' => 'Unpaid Bills Detail', 'coming_soon' => true],
                        ['label' => 'Accounts Payable Graph', 'coming_soon' => true],
                        ['label' => 'Transaction List by Vendor', 'coming_soon' => true],
                    ],
                ],
                [
                    'reports' => [
                        ['label' => '1099 Summary', 'coming_soon' => true],
                        ['label' => '1099 Detail', 'coming_soon' => true],
                    ],
                ],
                [
                    'reports' => [
                        ['label' => 'Vendor Phone List', 'coming_soon' => true],
                        ['label' => 'Vendor Contact List', 'coming_soon' => true],
                    ],
                ],
            ],
        ],
        [
            'id' => 'purchases',
            'label' => 'Purchases',
            'groups' => [
                [
                    'reports' => [
                        ['label' => 'Purchases by Item Summary', 'coming_soon' => true],
                        ['label' => 'Purchases by Item Detail', 'coming_soon' => true],
                        ['label' => 'Purchases by Vendor Summary', 'coming_soon' => true],
                        ['label' => 'Open Purchase Orders', 'coming_soon' => true],
                    ],
                ],
            ],
        ],
        [
            'id' => 'inventory',
            'label' => 'Inventory',
            'groups' => [
                [
                    'reports' => [
                        ['label' => 'Inventory Valuation Summary', 'coming_soon' => true],
                        ['label' => 'Inventory Stock Status by Item', 'route' => 'reports.inventory'],
                        ['label' => 'Inventory Valuation Detail', 'coming_soon' => true],
                        ['label' => 'Physical Inventory Worksheet', 'coming_soon' => true],
                        ['label' => 'Pending Builds', 'coming_soon' => true],
                    ],
                ],
            ],
        ],
        [
            'id' => 'employees-payroll',
            'label' => 'Employees & Payroll',
            'groups' => [
                [
                    'reports' => [
                        ['label' => 'Summarize Payroll Data in Excel', 'coming_soon' => true],
                        ['label' => 'More Payroll Reports in Excel', 'coming_soon' => true],
                    ],
                ],
                [
                    'reports' => [
                        ['label' => 'Payroll Summary', 'coming_soon' => true],
                        ['label' => 'Payroll Item Detail', 'coming_soon' => true],
                        ['label' => 'Employee Earnings Summary', 'coming_soon' => true],
                        ['label' => 'Payroll Transaction Detail', 'coming_soon' => true],
                        ['label' => 'Payroll Liability Balances', 'coming_soon' => true],
                    ],
                ],
                [
                    'reports' => [
                        ['label' => 'Employee Contact List', 'coming_soon' => true],
                        ['label' => 'Employee Withholding', 'coming_soon' => true],
                        ['label' => 'Paid Time Off List', 'coming_soon' => true],
                        ['label' => 'New Hire List', 'coming_soon' => true],
                        ['label' => 'Terminated Employees List', 'coming_soon' => true],
                    ],
                ],
            ],
        ],
        [
            'id' => 'banking',
            'label' => 'Banking',
            'groups' => [
                [
                    'reports' => [
                        ['label' => 'Deposit Detail', 'coming_soon' => true],
                        ['label' => 'Check Detail', 'coming_soon' => true],
                        ['label' => 'Missing Checks', 'coming_soon' => true],
                        ['label' => 'Reconciliation Discrepancy', 'coming_soon' => true],
                    ],
                ],
            ],
        ],
        [
            'id' => 'accountant-taxes',
            'label' => 'Accountant & Taxes',
            'groups' => [
                [
                    'reports' => [
                        ['label' => 'Adjusted Trial Balance', 'coming_soon' => true],
                        ['label' => 'Trial Balance', 'coming_soon' => true],
                        ['label' => 'General Ledger', 'coming_soon' => true],
                        ['label' => 'Journal', 'coming_soon' => true],
                        ['label' => 'Audit Trail', 'coming_soon' => true],
                        ['label' => 'Voided/Deleted Transactions', 'coming_soon' => true],
                    ],
                ],
            ],
        ],
        [
            'id' => 'budgets-forecasts',
            'label' => 'Budgets & Forecasts',
            'groups' => [
                [
                    'reports' => [
                        ['label' => 'Budget vs. Actual', 'coming_soon' => true],
                        ['label' => 'Budget Overview', 'coming_soon' => true],
                        ['label' => 'Forecast Overview', 'coming_soon' => true],
                    ],
                ],
            ],
        ],
        [
            'id' => 'list',
            'label' => 'List',
            'groups' => [
                [
                    'reports' => [
                        ['label' => 'Account Listing', 'coming_soon' => true],
                        ['label' => 'Item Price List', 'coming_soon' => true],
                        ['label' => 'Item Price List for Price Level', 'coming_soon' => true],
                        ['label' => 'Item Listing', 'route' => 'reports.inventory'],
                        ['label' => 'Payroll Item Listing', 'coming_soon' => true],
                        ['label' => 'Fixed Asset Listing', 'coming_soon' => true],
                    ],
                ],
                [
                    'reports' => [
                        ['label' => 'Unit of Measure Set Listing', 'coming_soon' => true],
                        ['label' => 'Unit of Measure Sets with Related Units', 'coming_soon' => true],
                        ['label' => 'Items with Units of Measure', 'coming_soon' => true],
                    ],
                ],
                [
                    'reports' => [
                        ['label' => 'Customer Phone List', 'coming_soon' => true],
                        ['label' => 'Customer Contact List', 'route' => 'reports.customers'],
                        ['label' => 'Vendor Phone List', 'coming_soon' => true],
                        ['label' => 'Vendor Contact List', 'coming_soon' => true],
                        ['label' => 'Employee Contact List', 'coming_soon' => true],
                        ['label' => 'Other Names Phone List', 'coming_soon' => true],
                        ['label' => 'Other Names Contact List', 'coming_soon' => true],
                    ],
                ],
                [
                    'reports' => [
                        ['label' => 'Terms Listing', 'coming_soon' => true],
                        ['label' => 'To Do Notes', 'coming_soon' => true],
                        ['label' => 'Memorized Transaction Listing', 'coming_soon' => true],
                    ],
                ],
            ],
        ],
    ],

    'footer' => [
        [
            'label' => 'Contributed Reports',
            'children' => [
                ['label' => 'Company & Financial', 'coming_soon' => true],
                ['label' => 'Customers & Receivables', 'coming_soon' => true],
                ['label' => 'Sales', 'coming_soon' => true],
                ['label' => 'Jobs, Time & Mileage', 'coming_soon' => true],
                ['label' => 'Vendors & Payables', 'coming_soon' => true],
                ['label' => 'Purchases', 'coming_soon' => true],
                ['label' => 'Inventory', 'coming_soon' => true],
                ['label' => 'Employee & Payroll', 'coming_soon' => true],
                ['label' => 'Banking', 'coming_soon' => true],
                ['label' => 'Accountant & Taxes', 'coming_soon' => true],
                ['label' => 'Budgets & Forecasts', 'coming_soon' => true],
                ['label' => 'List', 'coming_soon' => true],
            ],
        ],
        [
            'label' => 'Custom Reports',
            'children' => [
                ['label' => 'Custom Summary Report', 'coming_soon' => true],
                ['label' => 'Custom Transaction Detail Report', 'coming_soon' => true],
            ],
        ],
        ['separator' => true],
        ['label' => 'QuickReport', 'coming_soon' => true, 'shortcut' => 'Ctrl+Q', 'disabled' => true],
        ['label' => 'Transaction History', 'coming_soon' => true],
        ['label' => 'Transaction Journal', 'coming_soon' => true],
    ],
];
