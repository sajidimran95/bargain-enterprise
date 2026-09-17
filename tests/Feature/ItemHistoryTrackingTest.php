<?php

namespace Tests\Feature;

use App\Livewire\Items\ItemForm;
use App\Livewire\Purchasing\PurchaseOrderForm;
use App\Models\Item;
use App\Models\ItemHistory;
use App\Models\User;
use App\Models\Vendor;
use App\Services\InventoryService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ItemHistoryTrackingTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->owner = User::factory()->create();
        $this->owner->assignRole('owner');
    }

    public function test_stock_post_writes_item_history(): void
    {
        $this->actingAs($this->owner);

        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 5,
            'average_cost' => 2,
        ]);

        app(InventoryService::class)->post($item, [
            'type' => 'adjustment',
            'qty_in' => 3,
            'unit_cost' => 2,
            'memo' => 'Adj in',
            'created_by' => $this->owner->id,
        ]);

        $this->assertDatabaseHas('item_histories', [
            'item_id' => $item->id,
            'event' => ItemHistory::EVENT_STOCK_IN,
            'memo' => 'Adj in',
        ]);

        $item->refresh();
        $this->assertSame('8.0000', number_format((float) $item->on_hand, 4, '.', ''));
    }

    public function test_po_updates_purchase_cost_and_alerts_sales_price(): void
    {
        $this->actingAs($this->owner);

        $vendor = Vendor::factory()->create(['is_active' => true]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'sku' => 'COST-1',
            'purchase_cost' => 10,
            'sales_price' => 20,
            'on_hand' => 0,
            'on_po_qty' => 0,
        ]);

        Livewire::test(PurchaseOrderForm::class)
            ->set('vendor_id', (string) $vendor->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '4')
            ->set('lines.0.rate', '12.00')
            ->set('lines.0.amount', '48.00')
            ->call('save')
            ->assertRedirect(route('purchase-orders.index'));

        $item->refresh();
        $this->assertSame('12.00', number_format((float) $item->purchase_cost, 2, '.', ''));
        $this->assertSame('4.0000', number_format((float) $item->on_po_qty, 4, '.', ''));

        $this->assertDatabaseHas('item_histories', [
            'item_id' => $item->id,
            'event' => ItemHistory::EVENT_PURCHASE_COST,
        ]);

        $this->assertTrue(session()->has('item_cost_alerts'));
        $alerts = session('item_cost_alerts');
        $this->assertSame('12.00', $alerts[0]['new_cost']);
        $this->assertSame('24.00', $alerts[0]['suggested_sales_price']);
    }

    public function test_item_form_can_apply_suggested_sales_price_from_history(): void
    {
        $this->actingAs($this->owner);

        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'purchase_cost' => 12,
            'sales_price' => 20,
        ]);

        $history = ItemHistory::query()->create([
            'item_id' => $item->id,
            'event' => ItemHistory::EVENT_PURCHASE_COST,
            'old_value' => 10,
            'new_value' => 12,
            'suggested_sales_price' => 24,
            'memo' => 'Cost up',
            'occurred_at' => now(),
            'created_by' => $this->owner->id,
        ]);

        Livewire::test(ItemForm::class, ['item' => $item])
            ->call('applySuggestedSalesPrice', $history->id)
            ->assertSet('sales_price', '24.00');
    }
}
