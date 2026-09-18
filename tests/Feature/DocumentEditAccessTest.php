<?php

namespace Tests\Feature;

use App\Actions\Purchasing\ReceiveGoodsAction;
use App\Actions\Sales\CreateInvoiceAction;
use App\Livewire\Purchasing\GoodsReceiptForm;
use App\Livewire\Purchasing\GoodsReceiptIndex;
use App\Livewire\Purchasing\PurchaseOrderForm;
use App\Livewire\Purchasing\PurchaseOrderIndex;
use App\Livewire\Purchasing\VendorBillForm;
use App\Livewire\Purchasing\VendorBillIndex;
use App\Livewire\Sales\InvoiceIndex;
use App\Livewire\Sales\SalesOrderForm;
use App\Livewire\Sales\SalesOrderIndex;
use App\Models\Customer;
use App\Models\GoodsReceipt;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorBill;
use Database\Seeders\ChartOfAccountsSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DocumentEditAccessTest extends TestCase
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

    public function test_invoice_and_sales_order_lists_expose_edit(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 50,
            'average_cost' => 2,
            'sales_price' => 10,
        ]);

        $invoice = app(CreateInvoiceAction::class)->handle([
            'customer_id' => $customer->id,
            'invoice_number' => 'INV-EDIT-UI',
            'invoice_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ], [[
            'item_id' => $item->id,
            'quantity' => 1,
            'rate' => 10,
            'taxable' => false,
        ]]);

        Livewire::test(SalesOrderForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '2')
            ->set('lines.0.rate', '10')
            ->set('lines.0.amount', '20.00')
            ->call('save')
            ->assertRedirect(route('sales-orders.index'));

        $order = SalesOrder::query()->latest('id')->firstOrFail();

        $this->get(route('invoices.edit', $invoice))->assertOk()->assertSee('INV-EDIT-UI');
        $this->get(route('sales-orders.edit', $order))->assertOk()->assertSee($order->number);

        Livewire::test(InvoiceIndex::class)
            ->assertSee('Edit')
            ->call('openEdit', $invoice->id)
            ->assertSet('selectedLineId', $invoice->id);

        Livewire::test(SalesOrderIndex::class)
            ->assertSee('Edit')
            ->call('openEdit', $order->id)
            ->assertSet('selectedLineId', $order->id);
    }

    public function test_purchase_order_receive_and_bill_lists_expose_edit(): void
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

        $openPo = PurchaseOrder::query()->latest('id')->firstOrFail();

        Livewire::test(PurchaseOrderForm::class)
            ->set('vendor_id', (string) $vendor->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '10')
            ->set('lines.0.rate', '5')
            ->set('lines.0.amount', '50.00')
            ->call('save')
            ->assertRedirect(route('purchase-orders.index'));

        $po = PurchaseOrder::query()->with('lines')->latest('id')->firstOrFail();

        app(ReceiveGoodsAction::class)->handle([
            'number' => 'GR-EDIT-UI-1',
            'vendor_id' => $vendor->id,
            'purchase_order_id' => $po->id,
            'receipt_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ], [[
            'item_id' => $item->id,
            'purchase_order_line_id' => $po->lines->first()->id,
            'quantity' => 4,
            'unit_cost' => 5,
        ]]);

        $receipt = GoodsReceipt::query()->where('number', 'GR-EDIT-UI-1')->firstOrFail();

        Livewire::test(VendorBillForm::class)
            ->set('vendor_id', (string) $vendor->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '2')
            ->set('lines.0.rate', '5')
            ->set('lines.0.amount', '10.00')
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $bill = VendorBill::query()->latest('id')->firstOrFail();

        Livewire::test(VendorBillForm::class)
            ->set('docType', 'credit')
            ->set('vendor_id', (string) $vendor->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '1')
            ->set('lines.0.rate', '5')
            ->set('lines.0.amount', '5.00')
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $rtv = VendorBill::query()->latest('id')->firstOrFail();

        $this->get(route('purchase-orders.edit', $openPo))->assertOk()->assertSee($openPo->number);
        $this->get(route('goods-receipts.edit', $receipt))->assertOk()->assertSee($receipt->number);
        $this->get(route('vendor-bills.edit', $bill))->assertOk()->assertSee($bill->bill_number);
        $this->get(route('vendor-returns.edit', $rtv))->assertOk()->assertSee('Edit');

        Livewire::test(PurchaseOrderIndex::class)
            ->assertSee('Edit')
            ->call('openEdit', $openPo->id)
            ->assertSet('selectedLineId', $openPo->id);

        Livewire::test(GoodsReceiptIndex::class)
            ->assertSee('Edit')
            ->call('openEdit', $receipt->id)
            ->assertSet('selectedLineId', $receipt->id);

        Livewire::test(VendorBillIndex::class)
            ->assertSee('Edit')
            ->call('openEdit', $bill->id)
            ->assertSet('selectedLineId', $bill->id)
            ->call('openEdit', $rtv->id)
            ->assertSet('selectedLineId', $rtv->id);
    }

    public function test_editing_purchase_order_updates_on_po_qty(): void
    {
        $this->actingAs($this->owner);

        $vendor = Vendor::factory()->create(['is_active' => true]);
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

        $po = PurchaseOrder::query()->latest('id')->firstOrFail();
        $this->assertSame('10.0000', number_format((float) $item->fresh()->on_po_qty, 4, '.', ''));

        Livewire::test(PurchaseOrderForm::class, ['purchaseOrder' => $po])
            ->assertSet('editingId', $po->id)
            ->set('lines.0.quantity', '7')
            ->set('lines.0.amount', '35.00')
            ->call('save')
            ->assertRedirect(route('purchase-orders.index'));

        $this->assertSame('7.0000', number_format((float) $item->fresh()->on_po_qty, 4, '.', ''));
        $this->assertSame('35.00', number_format((float) $po->fresh()->total, 2, '.', ''));
    }

    public function test_editing_goods_receipt_adjusts_on_hand(): void
    {
        $this->actingAs($this->owner);

        $vendor = Vendor::factory()->create(['is_active' => true]);
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

        app(ReceiveGoodsAction::class)->handle([
            'number' => 'GR-EDIT-QTY-1',
            'vendor_id' => $vendor->id,
            'purchase_order_id' => $po->id,
            'receipt_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ], [[
            'item_id' => $item->id,
            'purchase_order_line_id' => $po->lines->first()->id,
            'quantity' => 6,
            'unit_cost' => 5,
        ]]);

        $receipt = GoodsReceipt::query()->where('number', 'GR-EDIT-QTY-1')->firstOrFail();
        $this->assertSame('6.0000', number_format((float) $item->fresh()->on_hand, 4, '.', ''));

        Livewire::test(GoodsReceiptForm::class, ['goodsReceipt' => $receipt])
            ->assertSet('editingId', $receipt->id)
            ->set('lines.0.quantity', '4')
            ->set('lines.0.amount', '20.00')
            ->call('save')
            ->assertRedirect(route('goods-receipts.index'));

        $this->assertSame('4.0000', number_format((float) $item->fresh()->on_hand, 4, '.', ''));
    }

    public function test_editing_vendor_bill_updates_total_and_balance(): void
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
            ->set('lines.0.quantity', '3')
            ->set('lines.0.amount', '15.00')
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $this->assertSame('15.00', number_format((float) $bill->fresh()->total, 2, '.', ''));
        $this->assertSame('15.00', number_format((float) $vendor->fresh()->balance, 2, '.', ''));
        $this->assertSame(1, VendorBill::query()->count());
    }

    public function test_editing_rtv_adjusts_on_hand(): void
    {
        $this->actingAs($this->owner);

        $vendor = Vendor::factory()->create(['is_active' => true, 'balance' => 100]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 20,
            'average_cost' => 5,
            'purchase_cost' => 5,
            'sales_price' => 10,
        ]);

        Livewire::test(VendorBillForm::class)
            ->set('docType', 'credit')
            ->set('vendor_id', (string) $vendor->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '4')
            ->set('lines.0.rate', '5')
            ->set('lines.0.amount', '20.00')
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $rtv = VendorBill::query()->latest('id')->firstOrFail();
        $this->assertSame('16.0000', number_format((float) $item->fresh()->on_hand, 4, '.', ''));

        Livewire::test(VendorBillForm::class, ['vendorBill' => $rtv])
            ->assertSet('navigatorId', $rtv->id)
            ->assertSet('docType', 'credit')
            ->set('lines.0.quantity', '2')
            ->set('lines.0.amount', '10.00')
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $this->assertSame('18.0000', number_format((float) $item->fresh()->on_hand, 4, '.', ''));
        $this->assertSame('10.00', number_format((float) $rtv->fresh()->total, 2, '.', ''));
    }
}
