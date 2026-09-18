<?php

namespace App\Support;

/**
 * Menu / submenu permission matrix for role setup UI.
 * Keys are permission names used by hasPermission() throughout the app.
 */
class SystemPermissionCatalog
{
    /**
     * @return list<array{name: string, label: string, group: string, menu: string, submenu: string, sort_order: int}>
     */
    public static function definitions(): array
    {
        $rows = [];
        $sort = 0;

        foreach (self::tree() as $menu => $submenus) {
            foreach ($submenus as $submenu => $permissions) {
                foreach ($permissions as $name => $label) {
                    $rows[] = [
                        'name' => $name,
                        'label' => $label,
                        'group' => str($menu)->slug('_')->toString(),
                        'menu' => $menu,
                        'submenu' => $submenu,
                        'sort_order' => $sort++,
                    ];
                }
            }
        }

        return $rows;
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     */
    public static function tree(): array
    {
        return [
            'Company' => [
                'Home & Insights' => [
                    'dashboard.view' => 'Home / Company Snapshot',
                ],
                'Company Setup' => [
                    'settings.manage' => 'Company Info & Preferences',
                    'import.manage' => 'JapsPOS Import',
                    'audit.view' => 'Audit Log',
                ],
                'Users & Security' => [
                    'users.manage' => 'Create / Edit Users',
                    'roles.manage' => 'Roles & Menu Permissions',
                ],
            ],
            'Customers' => [
                'Customer Center' => [
                    'customer.view' => 'View Customers',
                    'customer.manage' => 'Create / Edit Customers',
                ],
                'Sales Documents' => [
                    'invoice.view' => 'View Invoices / Quotes / SO / Credits',
                    'invoice.create' => 'Create Sales Documents',
                    'invoice.edit' => 'Edit Sales Documents',
                    'invoice.delete' => 'Delete Sales Documents',
                    'invoice.void' => 'Void Invoices',
                ],
                'Payments' => [
                    'payment.view' => 'View Payments',
                    'payment.create' => 'Receive Payments',
                ],
            ],
            'Vendors' => [
                'Vendor Center' => [
                    'vendor.view' => 'View Vendors',
                    'vendor.manage' => 'Create / Edit Vendors',
                ],
                'Purchasing' => [
                    'purchase.view' => 'View PO / Receive / Bills / RTV',
                    'purchase.create' => 'Create / Edit Purchasing Docs',
                ],
            ],
            'Items & Inventory' => [
                'Items' => [
                    'item.view' => 'View Items',
                    'item.manage' => 'Create / Edit Items & Lookups',
                ],
                'Inventory' => [
                    'inventory.view' => 'View Stock Status',
                    'inventory.adjust' => 'Adjust Quantity / Value',
                    'inventory.override' => 'Override Negative Stock Policy',
                ],
            ],
            'Banking' => [
                'Accounts & Transactions' => [
                    'banking.view' => 'View Banking / Checks / Deposits',
                    'banking.manage' => 'Write Checks, Deposits, Reconcile',
                ],
            ],
            'Accounting' => [
                'Books' => [
                    'accounting.view' => 'View Chart of Accounts / Journals',
                    'accounting.manage' => 'Manage Accounts & Journal Entries',
                ],
            ],
            'Reports' => [
                'Report Center' => [
                    'report.view' => 'View Reports',
                    'report.export' => 'Export Reports',
                ],
            ],
            'Employees' => [
                'Time & Payroll' => [
                    'employees.view' => 'Enter Time / Payroll Center',
                ],
            ],
        ];
    }

    /**
     * @return array<string, array{label: string, submenus: array<string, list<array{name: string, label: string}>>}>
     */
    public static function forRoleForm(): array
    {
        $sections = [];

        foreach (self::tree() as $menu => $submenus) {
            $sections[$menu] = [
                'label' => $menu,
                'submenus' => [],
            ];
            foreach ($submenus as $submenu => $permissions) {
                $items = [];
                foreach ($permissions as $name => $label) {
                    $items[] = ['name' => $name, 'label' => $label];
                }
                $sections[$menu]['submenus'][$submenu] = $items;
            }
        }

        return $sections;
    }

    /**
     * @return list<string>
     */
    public static function allPermissionNames(): array
    {
        return array_column(self::definitions(), 'name');
    }
}
