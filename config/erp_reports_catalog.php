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
                ['label' => 'Memorized Report List', 'route' => 'reports.index'],
                [
                    'label' => 'Accountant',
                    'children' => [
                        ['label' => 'Adjusted Trial Balance', 'route' => 'reports.trial-balance'],
                        ['label' => 'Balance Sheet', 'route' => 'reports.balance-sheet'],
                        ['label' => 'General Ledger', 'route' => 'reports.general-ledger'],
                        ['label' => 'Profit & Loss', 'route' => 'reports.profit-loss'],
                    ],
                ],
                [
                    'label' => 'Banking',
                    'children' => [
                        ['label' => 'Deposit Detail', 'route' => 'reports.cash-flow'],
                        ['label' => 'Check Detail', 'route' => 'reports.cash-flow'],
                    ],
                ],
                [
                    'label' => 'Company',
                    'children' => [
                        ['label' => 'Balance Sheet', 'route' => 'reports.balance-sheet'],
                        ['label' => 'Profit & Loss', 'route' => 'reports.profit-loss'],
                        ['label' => 'Statement of Cash Flows', 'route' => 'reports.cash-flow'],
                    ],
                ],
                [
                    'label' => 'Customers',
                    'children' => [
                        ['label' => 'A/R Aging Summary', 'route' => 'reports.ar-aging'],
                        ['label' => 'Customer Balance Detail', 'route' => 'reports.open-balance'],
                        ['label' => 'Customer Balance Summary', 'route' => 'reports.open-balance'],
                        ['label' => 'Open Invoices', 'route' => 'reports.open-balance'],
                        ['label' => 'Accounts Receivable Graph', 'route' => 'reports.ar-aging'],
                    ],
                ],
                [
                    'label' => 'Employees',
                    'children' => [
                        ['label' => 'Payroll Item Detail', 'route' => 'reports.profit-loss'],
                        ['label' => 'Payroll Liability Balances', 'route' => 'reports.balance-sheet'],
                        ['label' => 'Payroll Summary', 'route' => 'reports.profit-loss'],
                    ],
                ],
                ['label' => 'Item Listing', 'route' => 'reports.inventory'],
                [
                    'label' => 'Manufacturing and Wholesale',
                    'children' => [
                        ['label' => 'Inventory Reorder Report by Vendor', 'route' => 'reports.inventory'],
                        ['label' => 'Open Purchase Orders by Item', 'route' => 'purchase-orders.index'],
                        ['label' => 'Open Sales Orders by Item', 'route' => 'sales-orders.fulfillment'],
                        ['label' => 'Profitability by Product', 'route' => 'reports.sales-by-item'],
                        ['label' => 'Sales Volume by Customer', 'route' => 'reports.sales-by-item'],
                    ],
                ],
                ['label' => 'Sales by Item Detail', 'route' => 'reports.sales-by-item'],
                [
                    'label' => 'Vendors',
                    'children' => [
                        ['label' => 'A/P Aging Summary', 'route' => 'reports.ap-aging'],
                        ['label' => 'Unpaid Bills Detail', 'route' => 'reports.ap-aging'],
                        ['label' => 'Vendor Balance Detail', 'route' => 'reports.vendor-balance'],
                    ],
                ],
            ],
        ],
        [
            'label' => 'Scheduled Reports',
            'children' => [
                ['label' => 'Scheduled Reports List', 'route' => 'reports.index'],
            ],
        ],
        ['label' => 'Commented Reports', 'route' => 'reports.index'],
        ['label' => 'Cash Flow Hub', 'route' => 'reports.cash-flow'],
        ['separator' => true],
        ['label' => 'Company Snapshot', 'route' => 'dashboard.snapshots'],
        ['label' => 'Advanced Reporting', 'route' => 'reports.index'],
        ['label' => 'Process Multiple Reports', 'route' => 'reports.index'],
        ['label' => 'QuickBooks Desktop Statement Writer', 'route' => 'reports.profit-loss'],
        ['label' => 'Combine Reports from Multiple Companies', 'route' => 'reports.index'],
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
                        ['label' => 'Sales by Rep Detail', 'route' => 'reports.sales-by-item'],
                        ['label' => 'Sales by Product', 'route' => 'reports.sales-by-item'],
                    ],
                ],
                [
                    'title' => 'Open Orders',
                    'reports' => [
                        ['label' => 'Open Sales Orders by Item', 'route' => 'sales-orders.fulfillment'],
                        ['label' => 'Open Purchase Orders by Item', 'route' => 'purchase-orders.index'],
                    ],
                ],
                [
                    'title' => 'Inventory',
                    'reports' => [
                        ['label' => 'Inventory Reorder Report by Vendor', 'route' => 'reports.inventory'],
                        ['label' => 'Profitability by Product', 'route' => 'reports.sales-by-item'],
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
                        ['label' => 'Profit & Loss', 'route' => 'reports.profit-loss'],
                        ['label' => 'Balance Sheet', 'route' => 'reports.balance-sheet'],
                        ['label' => 'Statement of Cash Flows', 'route' => 'reports.cash-flow'],
                        ['label' => 'Trial Balance', 'route' => 'reports.trial-balance'],
                        ['label' => 'General Ledger', 'route' => 'reports.general-ledger'],
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
                        ['label' => 'A/R Aging Summary', 'route' => 'reports.ar-aging'],
                        ['label' => 'A/R Aging Detail', 'route' => 'reports.ar-aging'],
                        ['label' => 'Customer Balance Summary', 'route' => 'reports.open-balance'],
                        ['label' => 'Customer Balance Detail', 'route' => 'reports.open-balance'],
                        ['label' => 'Open Invoices', 'route' => 'reports.open-balance'],
                        ['label' => 'Accounts Receivable Graph', 'route' => 'reports.ar-aging'],
                        ['label' => 'MSA Customer List', 'route' => 'reports.customers'],
                        ['label' => 'Collections Report', 'route' => 'reports.ar-aging'],
                        ['label' => 'Average Days to Pay Summary', 'route' => 'reports.ar-aging'],
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
                        ['label' => 'Sales by Customer Summary', 'route' => 'reports.sales-by-item'],
                        ['label' => 'Sales by Customer Detail', 'route' => 'reports.sales-by-item'],
                        ['label' => 'Sales by Rep Summary', 'route' => 'reports.sales-by-item'],
                        ['label' => 'Sales Graph', 'route' => 'reports.sales-by-item'],
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
                        ['label' => 'Time by Job Summary', 'route' => 'reports.profit-loss'],
                        ['label' => 'Time by Job Detail', 'route' => 'reports.profit-loss'],
                        ['label' => 'Mileage by Vehicle', 'route' => 'reports.profit-loss'],
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
                        ['label' => 'A/P Aging Summary', 'route' => 'reports.ap-aging'],
                        ['label' => 'A/P Aging Detail', 'route' => 'reports.ap-aging'],
                        ['label' => 'Vendor Balance Summary', 'route' => 'reports.vendor-balance'],
                        ['label' => 'Vendor Balance Detail', 'route' => 'reports.vendor-balance'],
                        ['label' => 'Unpaid Bills Detail', 'route' => 'reports.ap-aging'],
                        ['label' => 'Accounts Payable Graph', 'route' => 'reports.ap-aging'],
                        ['label' => 'Transaction List by Vendor', 'route' => 'reports.vendor-balance'],
                    ],
                ],
                [
                    'reports' => [
                        ['label' => '1099 Summary', 'route' => 'reports.vendor-balance'],
                        ['label' => '1099 Detail', 'route' => 'reports.vendor-balance'],
                    ],
                ],
                [
                    'reports' => [
                        ['label' => 'Vendor Phone List', 'route' => 'reports.vendor-balance'],
                        ['label' => 'Vendor Contact List', 'route' => 'reports.vendor-balance'],
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
                        ['label' => 'Purchases by Item Summary', 'route' => 'reports.purchase-by-item'],
                        ['label' => 'Purchases by Item Detail', 'route' => 'reports.purchase-by-item'],
                        ['label' => 'Purchases by Vendor Summary', 'route' => 'reports.vendor-balance'],
                        ['label' => 'Open Purchase Orders', 'route' => 'purchase-orders.index'],
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
                        ['label' => 'Inventory Valuation Summary', 'route' => 'reports.inventory-valuation'],
                        ['label' => 'Inventory Stock Status by Item', 'route' => 'reports.inventory'],
                        ['label' => 'Inventory Valuation Detail', 'route' => 'reports.inventory-valuation'],
                        ['label' => 'Physical Inventory Worksheet', 'route' => 'reports.inventory'],
                        ['label' => 'Pending Builds', 'route' => 'reports.inventory'],
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
                        ['label' => 'Summarize Payroll Data in Excel', 'route' => 'reports.profit-loss'],
                        ['label' => 'More Payroll Reports in Excel', 'route' => 'reports.profit-loss'],
                    ],
                ],
                [
                    'reports' => [
                        ['label' => 'Payroll Summary', 'route' => 'reports.profit-loss'],
                        ['label' => 'Payroll Item Detail', 'route' => 'reports.profit-loss'],
                        ['label' => 'Employee Earnings Summary', 'route' => 'reports.profit-loss'],
                        ['label' => 'Payroll Transaction Detail', 'route' => 'reports.profit-loss'],
                        ['label' => 'Payroll Liability Balances', 'route' => 'reports.balance-sheet'],
                    ],
                ],
                [
                    'reports' => [
                        ['label' => 'Employee Contact List', 'route' => 'reports.customers'],
                        ['label' => 'Employee Withholding', 'route' => 'reports.profit-loss'],
                        ['label' => 'Paid Time Off List', 'route' => 'reports.profit-loss'],
                        ['label' => 'New Hire List', 'route' => 'reports.customers'],
                        ['label' => 'Terminated Employees List', 'route' => 'reports.customers'],
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
                        ['label' => 'Deposit Detail', 'route' => 'reports.cash-flow'],
                        ['label' => 'Check Detail', 'route' => 'reports.cash-flow'],
                        ['label' => 'Missing Checks', 'route' => 'reports.cash-flow'],
                        ['label' => 'Reconciliation Discrepancy', 'route' => 'reconciliation.index'],
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
                        ['label' => 'Adjusted Trial Balance', 'route' => 'reports.trial-balance'],
                        ['label' => 'Trial Balance', 'route' => 'reports.trial-balance'],
                        ['label' => 'General Ledger', 'route' => 'reports.general-ledger'],
                        ['label' => 'Journal', 'route' => 'accounting.journals'],
                        ['label' => 'Audit Trail', 'route' => 'audit.index'],
                        ['label' => 'Voided/Deleted Transactions', 'route' => 'audit.index'],
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
                        ['label' => 'Budget vs. Actual', 'route' => 'reports.profit-loss'],
                        ['label' => 'Budget Overview', 'route' => 'reports.profit-loss'],
                        ['label' => 'Forecast Overview', 'route' => 'reports.profit-loss'],
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
                        ['label' => 'Account Listing', 'route' => 'accounting.chart'],
                        ['label' => 'Item Price List', 'route' => 'reports.inventory'],
                        ['label' => 'Item Price List for Price Level', 'route' => 'reports.inventory'],
                        ['label' => 'Item Listing', 'route' => 'reports.inventory'],
                        ['label' => 'Payroll Item Listing', 'route' => 'reports.inventory'],
                        ['label' => 'Fixed Asset Listing', 'route' => 'accounting.chart'],
                    ],
                ],
                [
                    'reports' => [
                        ['label' => 'Unit of Measure Set Listing', 'route' => 'lookups.index'],
                        ['label' => 'Unit of Measure Sets with Related Units', 'route' => 'lookups.index'],
                        ['label' => 'Items with Units of Measure', 'route' => 'reports.inventory'],
                    ],
                ],
                [
                    'reports' => [
                        ['label' => 'Customer Phone List', 'route' => 'reports.customers'],
                        ['label' => 'Customer Contact List', 'route' => 'reports.customers'],
                        ['label' => 'Vendor Phone List', 'route' => 'reports.vendor-balance'],
                        ['label' => 'Vendor Contact List', 'route' => 'reports.vendor-balance'],
                        ['label' => 'Employee Contact List', 'route' => 'reports.customers'],
                        ['label' => 'Other Names Phone List', 'route' => 'reports.customers'],
                        ['label' => 'Other Names Contact List', 'route' => 'reports.customers'],
                    ],
                ],
                [
                    'reports' => [
                        ['label' => 'Terms Listing', 'route' => 'lookups.index'],
                        ['label' => 'To Do Notes', 'route' => 'customers.index'],
                        ['label' => 'Memorized Transaction Listing', 'route' => 'invoices.index'],
                    ],
                ],
            ],
        ],
    ],

    'footer' => [
        [
            'label' => 'Contributed Reports',
            'children' => [
                ['label' => 'Company & Financial', 'route' => 'reports.index'],
                ['label' => 'Customers & Receivables', 'route' => 'reports.index'],
                ['label' => 'Sales', 'route' => 'reports.index'],
                ['label' => 'Jobs, Time & Mileage', 'route' => 'reports.index'],
                ['label' => 'Vendors & Payables', 'route' => 'reports.index'],
                ['label' => 'Purchases', 'route' => 'reports.index'],
                ['label' => 'Inventory', 'route' => 'reports.index'],
                ['label' => 'Employee & Payroll', 'route' => 'reports.index'],
                ['label' => 'Banking', 'route' => 'reports.index'],
                ['label' => 'Accountant & Taxes', 'route' => 'reports.index'],
                ['label' => 'Budgets & Forecasts', 'route' => 'reports.index'],
                ['label' => 'List', 'route' => 'reports.index'],
            ],
        ],
        [
            'label' => 'Custom Reports',
            'children' => [
                ['label' => 'Custom Summary Report', 'route' => 'reports.profit-loss'],
                ['label' => 'Custom Transaction Detail Report', 'route' => 'reports.general-ledger'],
            ],
        ],
        ['separator' => true],
        ['label' => 'QuickReport', 'route' => 'reports.open-balance', 'shortcut' => 'Ctrl+Q'],
        ['label' => 'Transaction History', 'route' => 'reports.general-ledger'],
        ['label' => 'Transaction Journal', 'route' => 'accounting.journals'],
    ],
];
