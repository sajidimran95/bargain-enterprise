<?php

namespace Tests\Feature;

use App\Livewire\Sales\SalesOrderFulfillmentWorksheet;
use App\Models\Customer;
use App\Models\Item;
use App\Models\SalesOrder;
use App\Models\SalesOrderLine;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class QbStyleFormsWaveTest extends TestCase
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

    public function test_qb_style_money_and_entity_forms_load(): void
    {
        $this->actingAs($this->owner);

        $this->get(route('vendor-payments.create'))->assertOk()->assertSee('Pay Bills');
        $this->get(route('checks.create'))->assertOk()->assertSee('Write Check');
        $this->get(route('deposits.create'))->assertOk()->assertSee('Make Deposits');
        $this->get(route('customers.create'))->assertOk()->assertSee('New Customer');
        $this->get(route('vendors.create'))->assertOk()->assertSee('New Vendor');
        $this->get(route('reconciliation.index'))->assertOk()->assertSee('Reconcile');
        $this->get(route('sales-orders.fulfillment'))->assertOk()->assertSee('Sales Order Fulfillment');
    }

    public function test_sales_order_fulfillment_lists_open_orders(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['display_name' => 'Fulfill Co', 'is_active' => true]);
        $item = Item::factory()->create(['sku' => 'FUL-1', 'on_hand' => 10, 'is_active' => true]);
        $order = SalesOrder::query()->create([
            'number' => 'SO-FUL-1',
            'customer_id' => $customer->id,
            'order_date' => now()->toDateString(),
            'status' => 'open',
            'subtotal' => 25,
            'tax_total' => 0,
            'total' => 25,
        ]);
        SalesOrderLine::query()->create([
            'sales_order_id' => $order->id,
            'item_id' => $item->id,
            'description' => $item->name,
            'quantity' => 2,
            'rate' => 12.5,
            'amount' => 25,
            'taxable' => true,
            'line_order' => 0,
        ]);

        Livewire::test(SalesOrderFulfillmentWorksheet::class)
            ->assertSee('SO-FUL-1')
            ->assertSee('Fulfill Co')
            ->assertSee('OK');
    }

    public function test_mfg_menu_points_to_fulfillment_worksheet(): void
    {
        $mfg = config('erp_menubar')['Mfg & Whsle'];
        $item = collect($mfg)->firstWhere('label', 'Sales Order Fulfillment Worksheet');

        $this->assertSame('sales-orders.fulfillment', $item['route'] ?? null);
    }
}
