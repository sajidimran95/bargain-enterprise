<?php

namespace Tests\Feature;

use App\Livewire\Purchasing\VendorBillForm;
use App\Models\Item;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorBill;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EnterBillsFormTest extends TestCase
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

    public function test_enter_bills_matches_qb_layout_chrome(): void
    {
        $this->actingAs($this->owner);

        $vendor = Vendor::factory()->create([
            'display_name' => 'Layout Vendor',
            'notes' => 'Vendor note',
        ]);

        Livewire::test(VendorBillForm::class)
            ->assertSee('Bill')
            ->assertSee('Credit')
            ->assertSee('Bill Received')
            ->assertSee('Vendor')
            ->assertSee('Address')
            ->assertSee('Amount Due')
            ->assertSee('Bill Due')
            ->assertSee('Expenses')
            ->assertSee('Items')
            ->assertSee('Select PO')
            ->assertSee('Pay Bill')
            ->assertSee('Save & Close')
            ->assertSee('Save & New')
            ->assertSee('Clear')
            ->assertSee('Receive All')
            ->set('vendor_id', (string) $vendor->id)
            ->assertSee('Summary')
            ->assertSee('Recent Transactions')
            ->assertSee('Notes')
            ->assertSee('Vendor note');
    }

    public function test_enter_bills_save_and_new_posts_vendor_bill(): void
    {
        $this->actingAs($this->owner);

        $vendor = Vendor::factory()->create(['display_name' => 'Acme Supply']);
        $item = Item::factory()->create([
            'sku' => 'SKU-BILL-1',
            'purchase_cost' => 2.50,
            'is_active' => true,
        ]);

        Livewire::test(VendorBillForm::class)
            ->set('vendor_id', (string) $vendor->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '4')
            ->set('lines.0.rate', '2.50')
            ->call('saveAndNew')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('vendor_bills', [
            'vendor_id' => $vendor->id,
            'total' => '10.00',
            'status' => 'open',
        ]);

        $this->assertSame(1, VendorBill::query()->count());
    }
}
