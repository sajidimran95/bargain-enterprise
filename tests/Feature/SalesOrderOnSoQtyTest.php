<?php

namespace Tests\Feature;

use App\Livewire\Sales\SalesOrderForm;
use App\Livewire\Sales\SalesOrderFulfillmentWorksheet;
use App\Models\Customer;
use App\Models\Item;
use App\Models\SalesOrder;
use App\Models\User;
use Database\Seeders\ChartOfAccountsSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SalesOrderOnSoQtyTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->seed(ChartOfAccountsSeeder::class);
        $this->owner = User::factory()->create();
        $this->owner->assignRole('owner');
    }

    public function test_sales_order_create_and_edit_adjust_on_so_qty_not_on_hand(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 40,
            'on_so_qty' => 0,
            'sales_price' => 10,
        ]);

        Livewire::test(SalesOrderForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '5')
            ->set('lines.0.rate', '10')
            ->set('lines.0.amount', '50.00')
            ->call('save')
            ->assertRedirect(route('sales-orders.index'));

        $item->refresh();
        $this->assertSame('40.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('5.0000', number_format((float) $item->on_so_qty, 4, '.', ''));

        $order = SalesOrder::query()->firstOrFail();

        Livewire::test(SalesOrderForm::class, ['salesOrder' => $order])
            ->assertSet('editingId', $order->id)
            ->set('lines.0.quantity', '3')
            ->assertSet('lines.0.amount', '30.00')
            ->call('save')
            ->assertRedirect(route('sales-orders.index'));

        $item->refresh();
        $this->assertSame('40.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('3.0000', number_format((float) $item->on_so_qty, 4, '.', ''));
        $this->assertSame('30.00', number_format((float) $order->fresh()->total, 2, '.', ''));

        Livewire::test(SalesOrderForm::class, ['salesOrder' => $order->fresh()])
            ->set('lines.0.quantity', '8')
            ->assertSet('lines.0.amount', '80.00')
            ->call('save')
            ->assertRedirect(route('sales-orders.index'));

        $item->refresh();
        $this->assertSame('40.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('8.0000', number_format((float) $item->on_so_qty, 4, '.', ''));
        $this->assertSame('80.00', number_format((float) $order->fresh()->total, 2, '.', ''));
    }

    public function test_fulfilling_sales_order_releases_on_so_qty_and_invoices_stock(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true, 'balance' => 0]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 40,
            'on_so_qty' => 0,
            'average_cost' => 2,
            'sales_price' => 10,
        ]);

        Livewire::test(SalesOrderForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '4')
            ->set('lines.0.rate', '10')
            ->set('lines.0.amount', '40.00')
            ->set('lines.0.taxable', false)
            ->call('save')
            ->assertRedirect(route('sales-orders.index'));

        $order = SalesOrder::query()->firstOrFail();
        $this->assertSame('4.0000', number_format((float) $item->fresh()->on_so_qty, 4, '.', ''));

        Livewire::test(SalesOrderFulfillmentWorksheet::class)
            ->set('selectedOrders.'.$order->id, true)
            ->call('createInvoices')
            ->assertDispatched('be-toast');

        $item->refresh();
        $this->assertSame('0.0000', number_format((float) $item->on_so_qty, 4, '.', ''));
        $this->assertSame('36.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('invoiced', $order->fresh()->status);
    }
}
