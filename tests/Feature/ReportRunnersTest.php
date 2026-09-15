<?php

namespace Tests\Feature;

use App\Livewire\Reports\CustomerOpenBalanceReport;
use App\Livewire\Reports\SalesByItemReport;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReportRunnersTest extends TestCase
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

    public function test_report_center_and_runners_load(): void
    {
        $this->actingAs($this->owner);

        $this->get(route('reports.index'))->assertOk()->assertSee('Run Report');
        $this->get(route('reports.customers'))->assertOk()->assertSee('Customer Contact List');
        $this->get(route('reports.inventory'))->assertOk()->assertSee('Inventory / Stock');
        $this->get(route('reports.sales-by-item'))->assertOk()->assertSee('Sales by Item');
        $this->get(route('reports.open-balance'))->assertOk()->assertSee('Customer Open Balance');
    }

    public function test_open_balance_report_groups_open_invoices(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create([
            'customer_number' => '2084752',
            'display_name' => 'Stop & Go',
            'is_active' => true,
        ]);

        Invoice::query()->create([
            'invoice_number' => 'INV-RPT-1',
            'customer_id' => $customer->id,
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'status' => 'open',
            'subtotal' => 100,
            'tax_total' => 0,
            'total' => 100,
            'amount_paid' => 0,
            'balance_due' => 100,
            'created_by' => $this->owner->id,
        ]);

        $this->get(route('reports.open-balance'))
            ->assertOk()
            ->assertSee('Stop & Go')
            ->assertSee('INV-RPT-1')
            ->assertSee('100.00')
            ->assertSee('Customize Report')
            ->assertSee('Sort By')
            ->assertSee('Accrual Basis')
            ->assertSee('TOTAL');
    }

    public function test_open_balance_report_hides_header(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(CustomerOpenBalanceReport::class)
            ->assertSee('Hide Header')
            ->call('toggleHideHeader')
            ->assertSet('hideHeader', true)
            ->assertSee('Show Header');
    }

    public function test_sales_by_item_report_shows_grouped_lines(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true]);
        $item = Item::factory()->create([
            'sku' => 'SODA-COKE',
            'name' => 'Coke 24ct',
            'is_active' => true,
        ]);

        $invoice = Invoice::query()->create([
            'invoice_number' => 'INV-SALES-1',
            'customer_id' => $customer->id,
            'invoice_date' => now()->toDateString(),
            'status' => 'open',
            'subtotal' => 20,
            'tax_total' => 0,
            'total' => 20,
            'amount_paid' => 0,
            'balance_due' => 20,
            'created_by' => $this->owner->id,
        ]);

        InvoiceLine::query()->create([
            'invoice_id' => $invoice->id,
            'item_id' => $item->id,
            'description' => 'Coke 24ct',
            'quantity' => 2,
            'rate' => 10,
            'amount' => 20,
            'taxable' => true,
            'tax_amount' => 0,
            'line_order' => 0,
        ]);

        Livewire::test(SalesByItemReport::class)
            ->set('datePreset', 'this_month')
            ->assertSee('SODA-COKE')
            ->assertSee('INV-SALES-1');
    }
}
