<?php

namespace Tests\Feature;

use App\Actions\Purchasing\ReceiveGoodsAction;
use App\Actions\Sales\CreateInvoiceAction;
use App\Actions\Sales\UpdateInvoiceAction;
use App\Livewire\Purchasing\PurchaseOrderForm;
use App\Livewire\Purchasing\VendorBillForm;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorBill;
use Database\Seeders\ChartOfAccountsSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class VendorBillEditBalanceAccuracyTest extends TestCase
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

    public function test_converting_bill_to_credit_same_amount_flips_vendor_balance(): void
    {
        $this->actingAs($this->owner);

        $vendor = Vendor::factory()->create(['is_active' => true, 'balance' => 0]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 20,
            'average_cost' => 5,
            'purchase_cost' => 5,
            'sales_price' => 10,
        ]);

        Livewire::test(VendorBillForm::class)
            ->set('vendor_id', (string) $vendor->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '2')
            ->set('lines.0.rate', '5')
            ->set('lines.0.amount', '10.00')
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $bill = VendorBill::query()->latest('id')->firstOrFail();
        $this->assertSame('10.00', number_format((float) $vendor->fresh()->balance, 2, '.', ''));

        Livewire::test(VendorBillForm::class, ['vendorBill' => $bill])
            ->assertSet('navigatorId', $bill->id)
            ->set('docType', 'credit')
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $this->assertSame('-10.00', number_format((float) $vendor->fresh()->balance, 2, '.', ''));
        $this->assertTrue($bill->fresh()->isCreditDocument());
    }

    public function test_changing_vendor_on_bill_edit_moves_ap_balance(): void
    {
        $this->actingAs($this->owner);

        $vendorA = Vendor::factory()->create(['is_active' => true, 'balance' => 0]);
        $vendorB = Vendor::factory()->create(['is_active' => true, 'balance' => 0]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 10,
            'average_cost' => 4,
            'purchase_cost' => 4,
            'sales_price' => 8,
        ]);

        Livewire::test(VendorBillForm::class)
            ->set('vendor_id', (string) $vendorA->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '1')
            ->set('lines.0.rate', '4')
            ->set('lines.0.amount', '4.00')
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $bill = VendorBill::query()->latest('id')->firstOrFail();
        $this->assertSame('4.00', number_format((float) $vendorA->fresh()->balance, 2, '.', ''));

        Livewire::test(VendorBillForm::class, ['vendorBill' => $bill])
            ->set('vendor_id', (string) $vendorB->id)
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $this->assertSame('0.00', number_format((float) $vendorA->fresh()->balance, 2, '.', ''));
        $this->assertSame('4.00', number_format((float) $vendorB->fresh()->balance, 2, '.', ''));
        $this->assertSame($vendorB->id, (int) $bill->fresh()->vendor_id);
    }

    public function test_rtv_edit_quantity_resyncs_po_received_and_on_po(): void
    {
        $this->actingAs($this->owner);

        $vendor = Vendor::factory()->create(['is_active' => true, 'balance' => 0]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 0,
            'on_po_qty' => 0,
            'average_cost' => 0,
            'purchase_cost' => 5,
            'sales_price' => 10,
        ]);

        Livewire::test(PurchaseOrderForm::class)
            ->set('vendor_id', (string) $vendor->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '10')
            ->set('lines.0.rate', '5')
            ->set('lines.0.amount', '50.00')
            ->call('save')
            ->assertRedirect(route('purchase-orders.index'));

        $po = PurchaseOrder::query()->with('lines')->latest('id')->firstOrFail();
        $poLine = $po->lines->firstOrFail();

        app(ReceiveGoodsAction::class)->handle([
            'number' => 'GR-RTV-EDIT',
            'vendor_id' => $vendor->id,
            'purchase_order_id' => $po->id,
            'receipt_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ], [[
            'item_id' => $item->id,
            'purchase_order_line_id' => $poLine->id,
            'quantity' => 10,
            'unit_cost' => 5,
        ]]);

        Livewire::test(VendorBillForm::class)
            ->set('isRtv', true)
            ->set('docType', 'credit')
            ->set('vendor_id', (string) $vendor->id)
            ->set('purchase_order_id', (string) $po->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '4')
            ->set('lines.0.rate', '5')
            ->set('lines.0.amount', '20.00')
            ->set('lines.0.purchase_order_line_id', (string) $poLine->id)
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $rtv = VendorBill::query()->latest('id')->firstOrFail();
        $this->assertSame($po->id, (int) $rtv->purchase_order_id);
        $this->assertSame('6.0000', number_format((float) $item->fresh()->on_hand, 4, '.', ''));
        $this->assertSame('4.0000', number_format((float) $item->fresh()->on_po_qty, 4, '.', ''));
        $this->assertSame('6.0000', number_format((float) $poLine->fresh()->qty_received, 4, '.', ''));

        Livewire::test(VendorBillForm::class, ['vendorBill' => $rtv])
            ->assertSet('purchase_order_id', (string) $po->id)
            ->assertSet('lines.0.purchase_order_line_id', (string) $poLine->id)
            ->set('lines.0.quantity', '2')
            ->set('lines.0.amount', '10.00')
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $item->refresh();
        $poLine->refresh();
        $this->assertSame('8.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('2.0000', number_format((float) $item->on_po_qty, 4, '.', ''));
        $this->assertSame('8.0000', number_format((float) $poLine->qty_received, 4, '.', ''));
    }

    public function test_changing_customer_on_invoice_edit_moves_ar_balance(): void
    {
        $this->actingAs($this->owner);

        $customerA = Customer::factory()->create(['is_active' => true, 'balance' => 0]);
        $customerB = Customer::factory()->create(['is_active' => true, 'balance' => 0]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 20,
            'average_cost' => 2,
            'sales_price' => 10,
        ]);

        $invoice = app(CreateInvoiceAction::class)->handle([
            'customer_id' => $customerA->id,
            'invoice_number' => 'INV-MOVE-1',
            'invoice_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ], [[
            'item_id' => $item->id,
            'quantity' => 1,
            'rate' => 10,
            'taxable' => false,
        ]]);

        $this->assertSame('10.00', number_format((float) $customerA->fresh()->balance, 2, '.', ''));

        app(UpdateInvoiceAction::class)->handle($invoice, [
            'customer_id' => $customerB->id,
            'invoice_number' => $invoice->invoice_number,
            'invoice_date' => $invoice->invoice_date->toDateString(),
            'updated_by' => $this->owner->id,
        ], [[
            'item_id' => $item->id,
            'quantity' => 1,
            'rate' => 10,
            'taxable' => false,
        ]]);

        $this->assertSame('0.00', number_format((float) $customerA->fresh()->balance, 2, '.', ''));
        $this->assertSame('10.00', number_format((float) $customerB->fresh()->balance, 2, '.', ''));
        $this->assertSame($customerB->id, (int) Invoice::query()->findOrFail($invoice->id)->customer_id);
    }
}
