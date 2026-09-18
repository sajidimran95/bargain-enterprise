<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Support\SystemPermissionCatalog;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (SystemPermissionCatalog::definitions() as $permission) {
            Permission::query()->updateOrCreate(
                ['name' => $permission['name']],
                [
                    'label' => $permission['label'],
                    'group' => $permission['group'],
                    'menu' => $permission['menu'],
                    'submenu' => $permission['submenu'],
                    'sort_order' => $permission['sort_order'],
                ]
            );
        }

        $roles = [
            'admin' => [
                'label' => 'Admin',
                'description' => 'Full system access — all menus and permissions',
                'permissions' => '*',
            ],
            'owner' => [
                'label' => 'Owner',
                'description' => 'Full system access (legacy admin alias)',
                'permissions' => '*',
            ],
            'manager' => [
                'label' => 'Manager',
                'description' => 'Operations management across sales, purchasing, and inventory',
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
                    'employees.view',
                    'users.manage',
                ],
            ],
            'sales_representative' => [
                'label' => 'Sales Representative',
                'description' => 'Customer sales, invoices, payments, and item lookup',
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
