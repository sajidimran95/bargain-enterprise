<?php

namespace Tests\Feature;

use App\Livewire\Accounting\ChartOfAccounts;
use App\Livewire\Inventory\InventoryAdjustmentIndex;
use App\Livewire\Sales\InvoiceForm;
use App\Livewire\Sales\InvoiceIndex;
use App\Models\Account;
use App\Models\Customer;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\ChartOfAccountsSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ErpNavigationAndToolbarTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->seed(ChartOfAccountsSeeder::class);

        $this->owner = User::factory()->create([
            'email' => 'owner-nav@bargain.local',
        ]);
        $this->owner->assignRole('owner');
    }

    public function test_sidebar_module_routes_are_reachable(): void
    {
        $routes = [
            'dashboard',
            'dashboard.snapshots',
            'customers.index',
            'vendors.index',
            'items.index',
            'lookups.index',
            'inventory.index',
            'inventory.adjustments',
            'invoices.index',
            'payments.index',
            'quotes.index',
            'sales-orders.index',
            'credit-memos.index',
            'sales-receipts.index',
            'sales-receipts.create',
            'purchase-orders.index',
            'purchase-orders.create',
            'goods-receipts.index',
            'goods-receipts.create',
            'vendor-bills.index',
            'vendor-bills.create',
            'vendor-payments.index',
            'vendor-payments.create',
            'banking.index',
            'banking.create',
            'deposits.index',
            'deposits.create',
            'checks.index',
            'checks.create',
            'accounting.chart',
            'accounting.journals',
            'invoices.create',
            'quotes.create',
            'sales-orders.create',
            'credit-memos.create',
            'payments.create',
            'reports.index',
            'settings.index',
            'audit.index',
        ];

        foreach ($routes as $route) {
            $params = $route === 'dashboard' ? [] : ['embed' => 1];
            $this->actingAs($this->owner)
                ->get(route($route, $params))
                ->assertOk();
        }
    }

    public function test_accounting_chart_has_working_new(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(ChartOfAccounts::class)
            ->call('newAccount')
            ->assertSet('showForm', true)
            ->set('form.number', '1999')
            ->set('form.name', 'Test Clearing')
            ->set('form.type', 'asset')
            ->call('saveAccount')
            ->assertSet('showForm', false);

        $this->assertDatabaseHas('accounts', [
            'number' => '1999',
            'name' => 'Test Clearing',
        ]);
    }

    public function test_invoice_list_find_action_dispatches_focus(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(InvoiceIndex::class)
            ->call('focusSearch')
            ->assertDispatched('be-focus-list-search');
    }

    public function test_new_opens_create_pages_not_auto_draft(): void
    {
        $this->actingAs($this->owner);

        $this->get(route('invoices.create'))->assertOk()->assertSee('Create Invoice');
        $this->get(route('quotes.create'))->assertOk()->assertSee('Create Quote');
        $this->get(route('purchase-orders.create'))->assertOk()->assertSee('Create Purchase Order');
        $this->get(route('payments.create'))->assertOk()->assertSee('Receive Payment');
        $this->get(route('banking.create'))->assertOk()->assertSee('New Bank Account');

        $this->assertDatabaseCount('purchase_orders', 0);
        $this->assertDatabaseCount('quotes', 0);
    }

    public function test_invoice_create_form_saves_document(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true]);
        $item = Item::factory()->create([
            'is_active' => true,
            'on_hand' => 100,
            'average_cost' => 1,
            'sales_price' => 10,
        ]);

        Livewire::test(InvoiceForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.item_code', $item->sku)
            ->set('lines.0.quantity', '2')
            ->set('lines.0.rate', '10')
            ->set('lines.0.amount', '20.00')
            ->call('saveAndClose')
            ->assertRedirect(route('invoices.index'));

        $this->assertDatabaseHas('invoices', [
            'customer_id' => $customer->id,
            'status' => 'open',
        ]);
    }

    public function test_invoice_scan_adds_item_by_barcode(): void
    {
        $this->actingAs($this->owner);

        $item = Item::factory()->create([
            'is_active' => true,
            'sku' => 'SKU-SCAN-1',
            'barcode' => '012345678905',
            'sales_price' => 5.50,
            'on_hand' => 50,
        ]);

        Livewire::test(InvoiceForm::class)
            ->set('scanCode', '012345678905')
            ->call('scanItem')
            ->assertSet('lines.0.item_id', (string) $item->id)
            ->assertSet('lines.0.item_code', '012345678905');
    }

    public function test_inventory_adjustment_page_loads_and_posts(): void
    {
        $this->actingAs($this->owner);

        $item = Item::factory()->create([
            'on_hand' => 10,
            'average_cost' => 1.5,
            'is_active' => true,
        ]);

        Livewire::test(InventoryAdjustmentIndex::class)
            ->call('newAdjustment')
            ->assertSet('showForm', true)
            ->set('form.item_id', $item->id)
            ->set('form.direction', 'in')
            ->set('form.qty', '2')
            ->set('form.unit_cost', '1.5')
            ->set('form.memo', 'Test adj')
            ->call('saveAdjustment')
            ->assertSet('showForm', false);

        $item->refresh();
        $this->assertSame('12.0000', number_format((float) $item->on_hand, 4, '.', ''));
    }

    public function test_chart_of_accounts_page_is_not_placeholder(): void
    {
        $this->actingAs($this->owner)
            ->get(route('accounting.chart'))
            ->assertOk()
            ->assertDontSee('Coming in Phase 8')
            ->assertSee('Chart of Accounts');

        $this->assertTrue(Account::query()->exists());
    }
}
