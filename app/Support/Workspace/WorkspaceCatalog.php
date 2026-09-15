<?php

namespace App\Support\Workspace;

use Illuminate\Support\Facades\Route;

class WorkspaceCatalog
{
    /**
     * @return array<string, array{type: string, title: string, closable?: bool, permission?: string|null}>
     */
    public static function definitions(): array
    {
        return [
            'dashboard' => ['type' => 'dashboard', 'title' => 'Home Page', 'closable' => false],
            'dashboard.home' => ['type' => 'dashboard', 'title' => 'Home Page', 'closable' => false],
            'dashboard.snapshots' => ['type' => 'snapshots', 'title' => 'Insights', 'closable' => true],
            'customers.index' => ['type' => 'customers', 'title' => 'Customers', 'closable' => true],
            'customers.create' => ['type' => 'customer-form', 'title' => 'New Customer', 'closable' => true],
            'customers.edit' => ['type' => 'customer-form', 'title' => 'Edit Customer', 'closable' => true],
            'customers.show' => ['type' => 'customers', 'title' => 'Customer', 'closable' => true],
            'vendors.index' => ['type' => 'vendors', 'title' => 'Vendors', 'closable' => true],
            'vendors.create' => ['type' => 'vendor-form', 'title' => 'New Vendor', 'closable' => true],
            'vendors.edit' => ['type' => 'vendor-form', 'title' => 'Edit Vendor', 'closable' => true],
            'vendors.show' => ['type' => 'vendors', 'title' => 'Vendor', 'closable' => true],
            'items.index' => ['type' => 'items', 'title' => 'Item List', 'closable' => true],
            'items.create' => ['type' => 'item-form', 'title' => 'New Item', 'closable' => true],
            'items.edit' => ['type' => 'item-form', 'title' => 'Edit Item', 'closable' => true],
            'lookups.index' => ['type' => 'lookups', 'title' => 'Lookups', 'closable' => true],
            'inventory.index' => ['type' => 'inventory', 'title' => 'Stock Status', 'closable' => true],
            'inventory.adjustments' => ['type' => 'inventory', 'title' => 'Inventory Adjustments', 'closable' => true],
            'quotes.index' => ['type' => 'quotes', 'title' => 'Quotes', 'closable' => true],
            'quotes.create' => ['type' => 'quote-form', 'title' => 'Create Quote', 'closable' => true],
            'sales-orders.index' => ['type' => 'sales-orders', 'title' => 'Sales Orders', 'closable' => true],
            'sales-orders.fulfillment' => ['type' => 'sales-orders', 'title' => 'SO Fulfillment', 'closable' => true],
            'sales-orders.create' => ['type' => 'sales-order-form', 'title' => 'Create Sales Order', 'closable' => true],
            'invoices.index' => ['type' => 'invoices', 'title' => 'Invoices', 'closable' => true],
            'invoices.create' => ['type' => 'invoice-form', 'title' => 'Create Invoices', 'closable' => true],
            'payments.index' => ['type' => 'payments', 'title' => 'Payments', 'closable' => true],
            'payments.create' => ['type' => 'payment-form', 'title' => 'Receive Payments', 'closable' => true],
            'credit-memos.index' => ['type' => 'credit-memos', 'title' => 'Credit Memos', 'closable' => true],
            'credit-memos.create' => ['type' => 'credit-memo-form', 'title' => 'Create Credit Memo', 'closable' => true],
            'credit-memos.apply' => ['type' => 'credit-memo-apply', 'title' => 'Apply Credits', 'closable' => true],
            'sales-receipts.index' => ['type' => 'sales-receipts', 'title' => 'Sales Receipts', 'closable' => true],
            'sales-receipts.create' => ['type' => 'sales-receipt-form', 'title' => 'Create Sales Receipt', 'closable' => true],
            'purchase-orders.index' => ['type' => 'purchase-orders', 'title' => 'Purchase Orders', 'closable' => true],
            'purchase-orders.create' => ['type' => 'purchase-order-form', 'title' => 'Create Purchase Order', 'closable' => true],
            'goods-receipts.index' => ['type' => 'goods-receipts', 'title' => 'Receive Inventory', 'closable' => true],
            'goods-receipts.create' => ['type' => 'goods-receipt-form', 'title' => 'Receive Inventory', 'closable' => true],
            'vendor-bills.index' => ['type' => 'vendor-bills', 'title' => 'Enter Bills', 'closable' => true],
            'vendor-bills.create' => ['type' => 'vendor-bill-form', 'title' => 'Enter Bills', 'closable' => true],
            'vendor-payments.index' => ['type' => 'vendor-payments', 'title' => 'Pay Bills', 'closable' => true],
            'vendor-payments.create' => ['type' => 'vendor-payment-form', 'title' => 'Pay Bills', 'closable' => true],
            'banking.index' => ['type' => 'banking', 'title' => 'Bank Accounts', 'closable' => true],
            'banking.create' => ['type' => 'banking', 'title' => 'New Bank Account', 'closable' => true],
            'deposits.index' => ['type' => 'deposits', 'title' => 'Deposits', 'closable' => true],
            'deposits.create' => ['type' => 'deposit-form', 'title' => 'Make Deposits', 'closable' => true],
            'checks.index' => ['type' => 'checks', 'title' => 'Checks', 'closable' => true],
            'checks.create' => ['type' => 'check-form', 'title' => 'Write Checks', 'closable' => true],
            'reconciliation.index' => ['type' => 'reconciliation', 'title' => 'Reconcile', 'closable' => true],
            'accounting.chart' => ['type' => 'accounting', 'title' => 'Chart of Accounts', 'closable' => true],
            'accounting.journals' => ['type' => 'accounting', 'title' => 'Journal Entries', 'closable' => true],
            'reports.index' => ['type' => 'reports', 'title' => 'Report Center', 'closable' => true],
            'reports.customers' => ['type' => 'reports', 'title' => 'MSA Customer List', 'closable' => true],
            'reports.inventory' => ['type' => 'reports', 'title' => 'MSA Inventory', 'closable' => true],
            'reports.sales-by-item' => ['type' => 'reports', 'title' => 'MSA Sales Report', 'closable' => true],
            'reports.open-balance' => ['type' => 'reports', 'title' => 'Customer Open Balance', 'closable' => true],
            'reports.ar-aging' => ['type' => 'reports', 'title' => 'A/R Aging Summary', 'closable' => true],
            'reports.ap-aging' => ['type' => 'reports', 'title' => 'A/P Aging Summary', 'closable' => true],
            'reports.inventory-valuation' => ['type' => 'reports', 'title' => 'Inventory Valuation', 'closable' => true],
            'reports.profit-loss' => ['type' => 'reports', 'title' => 'Profit & Loss', 'closable' => true],
            'reports.balance-sheet' => ['type' => 'reports', 'title' => 'Balance Sheet', 'closable' => true],
            'reports.trial-balance' => ['type' => 'reports', 'title' => 'Trial Balance', 'closable' => true],
            'reports.general-ledger' => ['type' => 'reports', 'title' => 'General Ledger', 'closable' => true],
            'reports.cash-flow' => ['type' => 'reports', 'title' => 'Statement of Cash Flows', 'closable' => true],
            'reports.vendor-balance' => ['type' => 'reports', 'title' => 'Vendor Balance Summary', 'closable' => true],
            'reports.purchase-by-item' => ['type' => 'reports', 'title' => 'Purchases by Item', 'closable' => true],
            'settings.index' => ['type' => 'settings', 'title' => 'Settings', 'closable' => true],
            'audit.index' => ['type' => 'audit', 'title' => 'Audit Log', 'closable' => true],
            'import.index' => ['type' => 'import', 'title' => 'QB Import', 'closable' => true],
            'profile' => ['type' => 'profile', 'title' => 'Profile', 'closable' => true],
        ];
    }

    /**
     * @return array{type: string, title: string, closable: bool, permission: ?string}|null
     */
    public static function get(string $routeName): ?array
    {
        $definitions = self::definitions();
        if (! isset($definitions[$routeName])) {
            if (! Route::has($routeName)) {
                return null;
            }

            return [
                'type' => $routeName,
                'title' => str($routeName)->afterLast('.')->replace('-', ' ')->title()->toString(),
                'closable' => true,
                'permission' => null,
            ];
        }

        $def = $definitions[$routeName];

        return [
            'type' => $def['type'],
            'title' => $def['title'],
            'closable' => $def['closable'] ?? true,
            'permission' => $def['permission'] ?? null,
        ];
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public static function tabId(string $routeName, array $params = []): string
    {
        if ($params === []) {
            return $routeName;
        }

        ksort($params);

        return $routeName.':'.http_build_query($params);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public static function titleFor(string $routeName, array $params = [], ?string $override = null): string
    {
        if (filled($override)) {
            return $override;
        }

        return self::get($routeName)['title'] ?? $routeName;
    }

    /**
     * @param  array<string, mixed>  $params
     */
    public static function embedUrl(string $routeName, array $params = [], ?string $tabId = null): string
    {
        $url = route($routeName, $params, absolute: false);
        $separator = str_contains($url, '?') ? '&' : '?';
        $url .= $separator.'embed=1';

        if ($tabId) {
            $url .= '&tab_id='.urlencode($tabId);
        }

        return $url;
    }

    /**
     * Routes that must never be swallowed by the workspace redirect.
     *
     * @return list<string>
     */
    public static function excludedRouteNames(): array
    {
        return [
            'dashboard',
            'login',
            'register',
            'password.request',
            'password.reset',
            'password.email',
            'password.store',
            'password.confirm',
            'verification.notice',
            'verification.verify',
            'verification.send',
            'logout',
            'invoices.pdf',
            'credit-memos.pdf',
        ];
    }
}
