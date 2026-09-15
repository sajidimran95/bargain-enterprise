<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class ErpMenubarCoverageTest extends TestCase
{
    public function test_every_menubar_route_exists(): void
    {
        $missing = [];

        foreach (config('erp_menubar') as $menu => $entries) {
            $this->collectMissingRoutes($entries, "{$menu}", $missing);
        }

        $this->assertSame([], $missing, 'Menubar routes missing: '.implode(', ', $missing));
    }

    public function test_qb_top_level_menus_are_present(): void
    {
        $menus = array_keys(config('erp_menubar'));

        $this->assertSame([
            'File',
            'Edit',
            'View',
            'Lists',
            'Favorites',
            'Mfg & Whsle',
            'Company',
            'Customers',
            'Vendors',
            'Employees',
            'Banking',
            'Reports',
            'Window',
            'Help',
        ], $menus);
    }

    public function test_customers_and_vendors_menus_include_core_qb_actions(): void
    {
        $customers = collect(config('erp_menubar.Customers'));
        $vendors = collect(config('erp_menubar.Vendors'));
        $banking = collect(config('erp_menubar.Banking'));
        $employees = collect(config('erp_menubar.Employees'));

        $this->assertTrue($customers->contains(fn ($i) => ($i['route'] ?? null) === 'customers.statements'));
        $this->assertTrue($customers->contains(fn ($i) => ($i['route'] ?? null) === 'credit-memos.apply'));
        $this->assertTrue($vendors->contains(fn ($i) => ($i['route'] ?? null) === 'vendor-payments.create'));
        $this->assertTrue($banking->contains(fn ($i) => ($i['route'] ?? null) === 'reconciliation.index'));
        $this->assertTrue($employees->contains(fn ($i) => ($i['route'] ?? null) === 'employees.time'));
        $this->assertTrue($employees->contains(fn ($i) => ($i['route'] ?? null) === 'employees.payroll'));
    }

    public function test_shortcut_catalog_routes_exist(): void
    {
        $missing = [];

        foreach (config('erp_shortcuts.catalog') as $id => $item) {
            $route = $item['route'] ?? null;
            if ($route && ! Route::has($route)) {
                $missing[] = "{$id}:{$route}";
            }
        }

        $this->assertSame([], $missing, 'Shortcut routes missing: '.implode(', ', $missing));
    }

    /**
     * @param  list<array<string, mixed>>  $entries
     * @param  list<string>  $missing
     */
    private function collectMissingRoutes(array $entries, string $path, array &$missing): void
    {
        foreach ($entries as $index => $entry) {
            $label = $entry['label'] ?? "item-{$index}";

            if (! empty($entry['route']) && ! Route::has($entry['route'])) {
                $missing[] = "{$path} > {$label} ({$entry['route']})";
            }

            if (! empty($entry['children']) && is_array($entry['children'])) {
                $this->collectMissingRoutes($entry['children'], "{$path} > {$label}", $missing);
            }
        }
    }
}
