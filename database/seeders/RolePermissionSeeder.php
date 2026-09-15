<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Invoices
            ['name' => 'invoice.view', 'label' => 'View Invoices', 'group' => 'invoices'],
            ['name' => 'invoice.create', 'label' => 'Create Invoices', 'group' => 'invoices'],
            ['name' => 'invoice.edit', 'label' => 'Edit Invoices', 'group' => 'invoices'],
            ['name' => 'invoice.delete', 'label' => 'Delete Invoices', 'group' => 'invoices'],
            ['name' => 'invoice.void', 'label' => 'Void Invoices', 'group' => 'invoices'],

            // Payments
            ['name' => 'payment.view', 'label' => 'View Payments', 'group' => 'payments'],
            ['name' => 'payment.create', 'label' => 'Create Payments', 'group' => 'payments'],

            // Customers / Vendors / Items
            ['name' => 'customer.view', 'label' => 'View Customers', 'group' => 'customers'],
            ['name' => 'customer.manage', 'label' => 'Manage Customers', 'group' => 'customers'],
            ['name' => 'vendor.view', 'label' => 'View Vendors', 'group' => 'vendors'],
            ['name' => 'vendor.manage', 'label' => 'Manage Vendors', 'group' => 'vendors'],
            ['name' => 'item.view', 'label' => 'View Items', 'group' => 'items'],
            ['name' => 'item.manage', 'label' => 'Manage Items', 'group' => 'items'],

            // Inventory
            ['name' => 'inventory.view', 'label' => 'View Inventory', 'group' => 'inventory'],
            ['name' => 'inventory.adjust', 'label' => 'Adjust Inventory', 'group' => 'inventory'],
            ['name' => 'inventory.override', 'label' => 'Override Inventory Policy', 'group' => 'inventory'],

            // Purchasing
            ['name' => 'purchase.view', 'label' => 'View Purchasing', 'group' => 'purchasing'],
            ['name' => 'purchase.create', 'label' => 'Create Purchasing Docs', 'group' => 'purchasing'],

            // Accounting / Banking
            ['name' => 'accounting.view', 'label' => 'View Accounting', 'group' => 'accounting'],
            ['name' => 'accounting.manage', 'label' => 'Manage Accounting', 'group' => 'accounting'],
            ['name' => 'banking.view', 'label' => 'View Banking', 'group' => 'banking'],
            ['name' => 'banking.manage', 'label' => 'Manage Banking', 'group' => 'banking'],

            // Reports / Settings
            ['name' => 'report.view', 'label' => 'View Reports', 'group' => 'reports'],
            ['name' => 'report.export', 'label' => 'Export Reports', 'group' => 'reports'],
            ['name' => 'audit.view', 'label' => 'View Audit Log', 'group' => 'audit'],
            ['name' => 'settings.manage', 'label' => 'Manage Settings', 'group' => 'settings'],
            ['name' => 'import.manage', 'label' => 'Manage QB Import', 'group' => 'import'],
            ['name' => 'dashboard.view', 'label' => 'View Dashboard', 'group' => 'dashboard'],
        ];

        foreach ($permissions as $permission) {
            Permission::query()->updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }

        $roles = [
            'owner' => [
                'label' => 'Owner',
                'description' => 'Full system access',
                'permissions' => '*',
            ],
            'manager' => [
                'label' => 'Manager',
                'description' => 'Operations management without destructive settings-only limits',
                'permissions' => [
                    'dashboard.view',
                    'invoice.view', 'invoice.create', 'invoice.edit', 'invoice.void',
                    'payment.view', 'payment.create',
                    'customer.view', 'customer.manage',
                    'vendor.view', 'vendor.manage',
                    'item.view', 'item.manage',
                    'inventory.view', 'inventory.adjust', 'inventory.override',
                    'purchase.view', 'purchase.create',
                    'accounting.view',
                    'banking.view', 'banking.manage',
                    'report.view', 'report.export',
                    'audit.view',
                ],
            ],
            'sales' => [
                'label' => 'Sales / Counter Staff',
                'description' => 'Point-of-sale and customer transactions',
                'permissions' => [
                    'dashboard.view',
                    'invoice.view', 'invoice.create', 'invoice.edit',
                    'payment.view', 'payment.create',
                    'customer.view', 'customer.manage',
                    'item.view',
                    'inventory.view',
                    'report.view',
                ],
            ],
            'bookkeeper' => [
                'label' => 'Bookkeeper',
                'description' => 'Accounting, banking, and reporting',
                'permissions' => [
                    'dashboard.view',
                    'invoice.view',
                    'payment.view', 'payment.create',
                    'customer.view',
                    'vendor.view',
                    'item.view',
                    'inventory.view',
                    'purchase.view',
                    'accounting.view', 'accounting.manage',
                    'banking.view', 'banking.manage',
                    'report.view', 'report.export',
                    'audit.view',
                ],
            ],
        ];

        $allPermissionIds = Permission::query()->pluck('id');

        foreach ($roles as $name => $data) {
            $role = Role::query()->updateOrCreate(
                ['name' => $name],
                [
                    'label' => $data['label'],
                    'description' => $data['description'],
                ]
            );

            if ($data['permissions'] === '*') {
                $role->permissions()->sync($allPermissionIds);
            } else {
                $ids = Permission::query()
                    ->whereIn('name', $data['permissions'])
                    ->pluck('id');
                $role->permissions()->sync($ids);
            }
        }
    }
}
