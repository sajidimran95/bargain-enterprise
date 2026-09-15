<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialReportsTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->owner = User::factory()->create();
        $this->owner->assignRole('owner');
    }

    public function test_core_financial_reports_load(): void
    {
        $this->actingAs($this->owner);

        foreach ([
            'reports.ar-aging' => 'A/R Aging Summary',
            'reports.ap-aging' => 'A/P Aging Summary',
            'reports.inventory-valuation' => 'Inventory Valuation Summary',
            'reports.profit-loss' => 'Profit & Loss',
            'reports.balance-sheet' => 'Balance Sheet',
            'reports.trial-balance' => 'Trial Balance',
            'reports.general-ledger' => 'General Ledger',
            'reports.cash-flow' => 'Statement of Cash Flows',
            'reports.vendor-balance' => 'Vendor Balance Summary',
            'reports.purchase-by-item' => 'Purchases by Item',
        ] as $route => $title) {
            $this->get(route($route))->assertOk()->assertSee($title);
        }
    }

    public function test_reports_menu_has_no_coming_soon_toasts_for_core_categories(): void
    {
        $customers = collect(config('erp_menubar.Reports'))
            ->firstWhere('label', 'Customers & Receivables');

        $this->assertIsArray($customers);
        foreach ($customers['children'] as $child) {
            if (! empty($child['separator'])) {
                continue;
            }
            $this->assertArrayHasKey('route', $child, $child['label'] ?? 'unknown');
            $this->assertArrayNotHasKey('action', $child);
        }
    }
}
