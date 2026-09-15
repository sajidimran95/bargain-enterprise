<?php

use App\Support\ErpReportsCatalog;

/**
 * QuickBooks-style main menubar (order matches Home Page screenshot).
 * Each item: route (workspace tab), action (shell command), and/or children (flyout).
 *
 * @return array<string, list<array{label?: string, route?: string, action?: string, separator?: bool, children?: list<array<string, mixed>>, shortcut?: string, disabled?: bool, message?: string}>>
 */
return [
    'File' => [
        ['label' => 'New Invoice', 'route' => 'invoices.create'],
        ['label' => 'New Customer', 'route' => 'customers.create'],
        ['label' => 'New Vendor', 'route' => 'vendors.create'],
        ['label' => 'New Item', 'route' => 'items.create'],
        ['separator' => true],
        ['label' => 'Receive Payment', 'route' => 'payments.create'],
        ['label' => 'Enter Bills', 'route' => 'vendor-bills.create'],
        ['separator' => true],
        ['label' => 'Import from QuickBooks…', 'route' => 'import.index'],
        ['label' => 'Company Settings', 'route' => 'settings.index'],
        ['separator' => true],
        ['label' => 'Sign Out', 'action' => 'logout'],
    ],
    'Edit' => [
        ['label' => 'Find Customers', 'route' => 'customers.index'],
        ['label' => 'Find Vendors', 'route' => 'vendors.index'],
        ['label' => 'Find Items', 'route' => 'items.index'],
        ['separator' => true],
        ['label' => 'Preferences', 'route' => 'settings.index'],
    ],
    'View' => [
        ['label' => 'Home Page', 'route' => 'dashboard.home'],
        ['label' => 'Insights / Snapshots', 'route' => 'dashboard.snapshots'],
        ['separator' => true],
        ['label' => 'Open Windows', 'action' => 'focus-tabs'],
    ],
    'Lists' => [
        ['label' => 'Chart of Accounts', 'route' => 'accounting.chart'],
        ['label' => 'Item List', 'route' => 'items.index'],
        ['label' => 'Customer Center', 'route' => 'customers.index'],
        ['label' => 'Vendor Center', 'route' => 'vendors.index'],
        ['label' => 'Invoice List', 'route' => 'invoices.index'],
        ['label' => 'Purchase Orders', 'route' => 'purchase-orders.index'],
        ['label' => 'Lookups', 'route' => 'lookups.index'],
    ],
    'Favorites' => [
        ['label' => 'Create Invoices', 'route' => 'invoices.create'],
        ['label' => 'Receive Payments', 'route' => 'payments.create'],
        ['label' => 'Enter Bills', 'route' => 'vendor-bills.create'],
        ['label' => 'Item List', 'route' => 'items.index'],
        ['label' => 'Customer Center', 'route' => 'customers.index'],
    ],
    'Mfg & Whsle' => [
        ['label' => 'Create Quotes (Estimates)', 'route' => 'quotes.create'],
        ['label' => 'Create Sales Orders', 'route' => 'sales-orders.create'],
        ['label' => 'Sales Order Fulfillment Worksheet', 'route' => 'sales-orders.fulfillment'],
        ['label' => 'Create Invoices', 'route' => 'invoices.create', 'shortcut' => 'Ctrl+I'],
        ['label' => 'Receive Payments', 'route' => 'payments.create'],
        ['label' => 'Make Deposits', 'route' => 'deposits.create'],
        ['separator' => true],
        ['label' => 'Create Purchase Orders', 'route' => 'purchase-orders.create'],
        ['label' => 'Receive Items', 'route' => 'goods-receipts.create'],
        ['label' => 'Enter Bill for Received Items', 'route' => 'vendor-bills.create'],
        ['label' => 'Enter Bills', 'route' => 'vendor-bills.create'],
        ['label' => 'Pay Bills', 'route' => 'vendor-payments.create'],
        ['separator' => true],
        ['label' => 'Item List', 'route' => 'items.index'],
        [
            'label' => 'Inventory Activities',
            'children' => [
                ['label' => 'Inventory Stock Status', 'route' => 'inventory.index'],
                ['label' => 'Inventory Adjustments', 'route' => 'inventory.adjustments'],
                ['label' => 'Receive Inventory', 'route' => 'goods-receipts.create'],
            ],
        ],
        [
            'label' => 'Manufacturing and Wholesale Reports',
            'children' => ErpReportsCatalog::manufacturingWholesaleMenu(),
        ],
    ],
    'Company' => [
        ['label' => 'Home Page', 'route' => 'dashboard.home'],
        ['label' => 'Company Snapshot', 'route' => 'dashboard.snapshots'],
        ['label' => 'Chart of Accounts', 'route' => 'accounting.chart'],
        ['label' => 'Journal Entries', 'route' => 'accounting.journals'],
        ['separator' => true],
        ['label' => 'Import from QuickBooks…', 'route' => 'import.index'],
        ['label' => 'Audit Log', 'route' => 'audit.index'],
        ['label' => 'Settings', 'route' => 'settings.index'],
    ],
    'Customers' => [
        ['label' => 'Customer Center', 'route' => 'customers.index'],
        ['label' => 'Create Invoices', 'route' => 'invoices.create'],
        ['label' => 'Create Quotes', 'route' => 'quotes.create'],
        ['label' => 'Create Sales Orders', 'route' => 'sales-orders.create'],
        ['label' => 'Create Sales Receipts', 'route' => 'sales-receipts.create'],
        ['label' => 'Receive Payments', 'route' => 'payments.create'],
        ['label' => 'Create Credit Memos / Refunds', 'route' => 'credit-memos.create'],
        ['label' => 'Invoice List', 'route' => 'invoices.index'],
    ],
    'Vendors' => [
        ['label' => 'Vendor Center', 'route' => 'vendors.index'],
        ['label' => 'Purchase Orders', 'route' => 'purchase-orders.create'],
        ['label' => 'Receive Inventory', 'route' => 'goods-receipts.create'],
        ['label' => 'Enter Bills', 'route' => 'vendor-bills.create'],
        ['label' => 'Pay Bills', 'route' => 'vendor-payments.create'],
    ],
    'Employees' => [
        ['label' => 'Enter Time (coming later)', 'action' => 'toast', 'message' => 'Employees / Payroll is out of scope for v1.'],
        ['label' => 'Payroll (out of scope v1)', 'action' => 'toast', 'message' => 'Payroll is out of scope for v1.'],
    ],
    'Banking' => [
        ['label' => 'Bank Accounts', 'route' => 'banking.index'],
        ['label' => 'Record Deposits', 'route' => 'deposits.create'],
        ['label' => 'Write Checks', 'route' => 'checks.create'],
        ['label' => 'Reconcile', 'route' => 'reconciliation.index'],
        ['label' => 'Check Register', 'route' => 'checks.index'],
    ],
    'Reports' => ErpReportsCatalog::menubarItems(),
    'Window' => [
        ['label' => 'Home Page', 'route' => 'dashboard.home'],
        ['separator' => true],
        ['label' => 'Close', 'action' => 'close-tab'],
        ['label' => 'Close Others', 'action' => 'close-others'],
        ['label' => 'Close All', 'action' => 'close-all'],
        ['separator' => true],
        ['label' => 'Refresh Tab', 'action' => 'refresh-tab'],
    ],
    'Help' => [
        ['label' => 'Profile', 'route' => 'profile'],
        ['label' => 'About Bargain Enterprise', 'action' => 'toast', 'message' => 'Bargain Enterprise POS/ERP — wholesale distribution.'],
    ],
];
