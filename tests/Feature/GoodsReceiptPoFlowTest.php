<?php

namespace Tests\Feature;

use App\Livewire\Purchasing\GoodsReceiptForm;
use App\Livewire\Purchasing\PurchaseOrderForm;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Vendor;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GoodsReceiptPoFlowTest extends TestCase
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

    public function test_receive_requires_vendor_then_po_then_loads_remaining_lines(): void
    {
        $this->actingAs($this->owner);

        $vendor = Vendor::factory()->create(['is_active' => true]);
        $otherVendor = Vendor::factory()->create(['is_active' => true]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 0,
            'on_po_qty' => 0,
            'purchase_cost' => 4.5,
            'sales_price' => 9,
        ]);

        Livewire::test(PurchaseOrderForm::class)
            ->set('vendor_id', (string) $vendor->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '10')
            ->set('lines.0.rate', '4.50')
            ->set('lines.0.amount', '45.00')
            ->call('save')
            ->assertRedirect(route('purchase-orders.index'));

        $po = PurchaseOrder::query()->with('lines')->latest('id')->firstOrFail();

        Livewire::test(GoodsReceiptForm::class)
            ->assertSet('purchase_order_id', '')
            ->set('vendor_id', (string) $otherVendor->id)
            ->assertSet('purchase_order_id', '')
            ->assertViewHas('poOptions', function (array $options) {
                return count($options) === 1; // placeholder only
            })
            ->set('vendor_id', (string) $vendor->id)
            ->assertViewHas('poOptions', function (array $options) use ($po) {
                return isset($options[$po->id]);
            })
            ->set('purchase_order_id', (string) $po->id)
            ->assertSet('lines.0.item_id', (string) $item->id)
            ->assertSet('lines.0.quantity', '10.00')
            ->assertSet('lines.0.purchase_order_line_id', (string) $po->lines->first()->id)
            ->set('lines.0.quantity', '6')
            ->call('save')
            ->assertRedirect(route('goods-receipts.index'));

        $item->refresh();
        $po->refresh()->load('lines');

        $this->assertSame('6.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('4.0000', number_format((float) $item->on_po_qty, 4, '.', ''));
        $this->assertSame('6.0000', number_format((float) $po->lines->first()->qty_received, 4, '.', ''));
        $this->assertSame('partial', $po->status);
    }

    public function test_receive_without_po_is_rejected(): void
    {
        $this->actingAs($this->owner);

        $vendor = Vendor::factory()->create(['is_active' => true]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 0,
        ]);

        Livewire::test(GoodsReceiptForm::class)
            ->set('vendor_id', (string) $vendor->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '2')
            ->set('lines.0.rate', '1')
            ->call('save')
            ->assertHasErrors(['purchase_order_id']);
    }
}
