<?php

namespace Tests\Feature;

use App\Support\ErpReportsCatalog;
use Tests\TestCase;

class ErpReportsMenuTest extends TestCase
{
    public function test_reports_menubar_has_nested_categories_and_report_center(): void
    {
        $reports = config('erp_menubar.Reports');

        $this->assertIsArray($reports);
        $this->assertTrue(collect($reports)->contains(fn ($item) => ($item['route'] ?? null) === 'reports.index'));

        $customers = collect($reports)->firstWhere('label', 'Customers & Receivables');
        $this->assertIsArray($customers);
        $this->assertNotEmpty($customers['children'] ?? []);
        $this->assertTrue(
            collect($customers['children'])->contains(fn ($item) => ($item['route'] ?? null) === 'reports.customers')
        );

        $memorized = collect($reports)->firstWhere('label', 'Memorized Reports');
        $this->assertIsArray($memorized);
        $company = collect($memorized['children'] ?? [])->firstWhere('label', 'Company');
        $this->assertIsArray($company);
        $this->assertNotEmpty($company['children'] ?? []);
    }

    public function test_mfg_menu_includes_manufacturing_reports_flyout(): void
    {
        $mfg = config('erp_menubar')['Mfg & Whsle'];
        $reportsItem = collect($mfg)->firstWhere('label', 'Manufacturing and Wholesale Reports');

        $this->assertIsArray($reportsItem);
        $this->assertNotEmpty($reportsItem['children'] ?? []);
        $this->assertSame(
            ErpReportsCatalog::manufacturingWholesaleMenu(),
            $reportsItem['children']
        );
    }

    public function test_report_catalog_sidebar_categories_match_qb_order(): void
    {
        $ids = collect(ErpReportsCatalog::sidebarCategories())->pluck('id')->all();

        $this->assertSame([
            'mfg-wholesale',
            'company-financial',
            'customers-receivables',
            'sales',
            'jobs-time',
            'vendors-payables',
            'purchases',
            'inventory',
            'employees-payroll',
            'banking',
            'accountant-taxes',
            'budgets-forecasts',
            'list',
        ], $ids);
    }
}
