<?php

namespace Tests\Feature;

use App\Actions\Purchasing\ReceiveGoodsAction;
use App\Livewire\Items\ItemForm;
use App\Livewire\Purchasing\PurchaseOrderForm;
use App\Livewire\Purchasing\VendorBillForm;
use App\Livewire\Sales\CreditMemoForm;
use App\Livewire\Sales\InvoiceForm;
use App\Livewire\Sales\SalesReceiptForm;
use App\Models\Customer;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Vendor;
use Database\Seeders\ChartOfAccountsSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InventoryDocumentStockTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->seed(ChartOfAccountsSeeder::class);
        $this->owner = User::factory()->create();
        $this->owner->assignRole('owner');
    }

    public function test_item_edit_form_loads_when_nullable_strings_are_null(): void
    {
        $this->actingAs($this->owner);

        $item = Item::factory()->create([
            'barcode' => null,
            'manufacturer_part_number' => null,
            'purchase_description' => null,
            'sales_description' => null,
            'cogs_account' => null,
            'income_account' => null,
            'asset_account' => null,
            'promotion' => null,
        ]);

        $this->get(route('items.edit', $item))
            ->assertOk()
            ->assertSee($item->sku);

        Livewire::test(ItemForm::class, ['item' => $item])
            ->assertSet('manufacturer_part_number', '')
            ->assertSet('barcode', '')
            ->assertSet('promotion', '');
    }

    public function test_invoice_decreases_on_hand_stock(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 100,
            'average_cost' => 2,
            'sales_price' => 10,
        ]);

        Livewire::test(InvoiceForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.item_code', $item->sku)
            ->set('lines.0.quantity', '3')
            ->set('lines.0.rate', '10')
            ->set('lines.0.amount', '30.00')
            ->call('saveAndClose')
            ->assertRedirect(route('invoices.index'));

        $item->refresh();
        $this->assertSame('97.0000', number_format((float) $item->on_hand, 4, '.', ''));
    }

    public function test_sales_receipt_decreases_on_hand_stock(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 40,
            'average_cost' => 1,
            'sales_price' => 5,
        ]);

        Livewire::test(SalesReceiptForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('payment_method', 'cash')
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '4')
            ->set('lines.0.rate', '5')
            ->set('lines.0.amount', '20.00')
            ->call('save')
            ->assertRedirect(route('sales-receipts.index'));

        $item->refresh();
        $this->assertSame('36.0000', number_format((float) $item->on_hand, 4, '.', ''));
    }

    public function test_credit_memo_restores_on_hand_stock(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create([
            'is_active' => true,
            'balance' => 50,
        ]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 10,
            'average_cost' => 1,
            'sales_price' => 8,
        ]);

        Livewire::test(CreditMemoForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '2')
            ->set('lines.0.rate', '8')
            ->set('lines.0.amount', '16.00')
            ->call('save')
            ->assertRedirect(route('credit-memos.index'));

        $item->refresh();
        $customer->refresh();
        $this->assertSame('12.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('34.00', number_format((float) $customer->balance, 2, '.', ''));
    }

    public function test_full_stock_flow_item_po_receive_invoice_and_return(): void
    {
        $this->actingAs($this->owner);

        $vendor = Vendor::factory()->create(['is_active' => true]);
        $customer = Customer::factory()->create(['is_active' => true]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 0,
            'on_po_qty' => 0,
            'average_cost' => 0,
            'purchase_cost' => 5,
            'sales_price' => 12,
        ]);

        // 1) PO for 10 — on hand stays 0, on PO becomes 10
        Livewire::test(PurchaseOrderForm::class)
            ->set('vendor_id', (string) $vendor->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '10')
            ->set('lines.0.rate', '5')
            ->set('lines.0.amount', '50.00')
            ->call('save')
            ->assertRedirect(route('purchase-orders.index'));

        $item->refresh();
        $this->assertSame('0.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('10.0000', number_format((float) $item->on_po_qty, 4, '.', ''));

        $po = PurchaseOrder::query()->with('lines')->latest('id')->firstOrFail();

        // 2) Receive 10 against PO — on hand 10, on PO 0
        app(ReceiveGoodsAction::class)->handle([
            'number' => 'GR-STOCK-1',
            'vendor_id' => $vendor->id,
            'purchase_order_id' => $po->id,
            'receipt_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ], [[
            'item_id' => $item->id,
            'purchase_order_line_id' => $po->lines->first()->id,
            'quantity' => 10,
            'unit_cost' => 5,
        ]]);

        $item->refresh();
        $this->assertSame('10.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('0.0000', number_format((float) $item->on_po_qty, 4, '.', ''));
        $this->assertSame('received', $po->fresh()->status);

        // 3) Invoice 3 — on hand 7
        Livewire::test(InvoiceForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.item_code', $item->sku)
            ->set('lines.0.quantity', '3')
            ->set('lines.0.rate', '12')
            ->set('lines.0.amount', '36.00')
            ->call('saveAndClose')
            ->assertRedirect(route('invoices.index'));

        $item->refresh();
        $this->assertSame('7.0000', number_format((float) $item->on_hand, 4, '.', ''));

        // 4) Credit memo / return 1 — on hand 8
        Livewire::test(CreditMemoForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '1')
            ->set('lines.0.rate', '12')
            ->set('lines.0.amount', '12.00')
            ->call('save')
            ->assertRedirect(route('credit-memos.index'));

        $item->refresh();
        $this->assertSame('8.0000', number_format((float) $item->on_hand, 4, '.', ''));
    }

    public function test_vendor_credit_return_decreases_on_hand_stock(): void
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

        $item->refresh();
        $vendor->refresh();
        $this->assertSame('16.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('80.00', number_format((float) $vendor->balance, 2, '.', ''));
    }

    public function test_po_partial_receive_then_vendor_return_adjusts_stock_and_po(): void
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

        // PO ordered 10
        Livewire::test(PurchaseOrderForm::class)
            ->set('vendor_id', (string) $vendor->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '10')
            ->set('lines.0.rate', '5')
            ->set('lines.0.amount', '50.00')
            ->call('save')
            ->assertRedirect(route('purchase-orders.index'));

        $po = PurchaseOrder::query()->with('lines')->latest('id')->firstOrFail();
        $poLine = $po->lines->first();

        // Receive only 6
        app(ReceiveGoodsAction::class)->handle([
            'number' => 'GR-PARTIAL-1',
            'vendor_id' => $vendor->id,
            'purchase_order_id' => $po->id,
            'receipt_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ], [[
            'item_id' => $item->id,
            'purchase_order_line_id' => $poLine->id,
            'quantity' => 6,
            'unit_cost' => 5,
        ]]);

        $item->refresh();
        $this->assertSame('6.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('4.0000', number_format((float) $item->on_po_qty, 4, '.', ''));
        $this->assertSame('partial', $po->fresh()->status);

        // Return 2 of the received items to vendor against the PO
        Livewire::test(VendorBillForm::class)
            ->set('docType', 'credit')
            ->set('vendor_id', (string) $vendor->id)
            ->set('purchase_order_id', (string) $po->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '2')
            ->set('lines.0.rate', '5')
            ->set('lines.0.amount', '10.00')
            ->set('lines.0.purchase_order_line_id', (string) $poLine->id)
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $item->refresh();
        $poLine->refresh();
        $po->refresh();

        // OH 6-2=4; returned qty goes back On PO → 4+2=6; received 6-2=4; still partial
        $this->assertSame('4.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('6.0000', number_format((float) $item->on_po_qty, 4, '.', ''));
        $this->assertSame('4.0000', number_format((float) $poLine->qty_received, 4, '.', ''));
        $this->assertSame('partial', $po->status);
    }

    public function test_rtv_menu_route_opens_return_to_vendor_form(): void
    {
        $this->actingAs($this->owner);

        $this->get(route('vendor-returns.create'))
            ->assertOk()
            ->assertSee('Return to Vendor (RTV)');
    }

    public function test_rtv_selecting_received_po_autoload_items_then_save_reduces_stock(): void
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
        $poLine = $po->lines->first();

        app(ReceiveGoodsAction::class)->handle([
            'number' => 'GR-RTV-AUTO-1',
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

        $this->assertSame('10.0000', number_format((float) $item->fresh()->on_hand, 4, '.', ''));

        // Select vendor + received PO → items auto-load; change qty to 2; save RTV
        Livewire::test(VendorBillForm::class)
            ->set('isRtv', true)
            ->set('docType', 'credit')
            ->set('vendor_id', (string) $vendor->id)
            ->set('purchase_order_id', (string) $po->id)
            ->assertSet('lines.0.item_id', (string) $item->id)
            ->assertSet('lines.0.quantity', '10.00')
            ->assertSet('lines.0.purchase_order_line_id', (string) $poLine->id)
            ->set('lines.0.quantity', '2')
            ->set('lines.0.amount', '10.00')
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $item->refresh();
        $this->assertSame('8.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('2.0000', number_format((float) $item->on_po_qty, 4, '.', ''));
        $this->assertSame('8.0000', number_format((float) $poLine->fresh()->qty_received, 4, '.', ''));
    }

    public function test_po_receive_all_then_later_vendor_return_reopens_po_qty(): void
    {
        $this->actingAs($this->owner);

        $vendor = Vendor::factory()->create(['is_active' => true, 'balance' => 0]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 0,
            'on_po_qty' => 0,
            'average_cost' => 0,
            'purchase_cost' => 4,
            'sales_price' => 9,
        ]);

        Livewire::test(PurchaseOrderForm::class)
            ->set('vendor_id', (string) $vendor->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '10')
            ->set('lines.0.rate', '4')
            ->set('lines.0.amount', '40.00')
            ->call('save')
            ->assertRedirect(route('purchase-orders.index'));

        $po = PurchaseOrder::query()->with('lines')->latest('id')->firstOrFail();
        $poLine = $po->lines->first();

        app(ReceiveGoodsAction::class)->handle([
            'number' => 'GR-FULL-1',
            'vendor_id' => $vendor->id,
            'purchase_order_id' => $po->id,
            'receipt_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ], [[
            'item_id' => $item->id,
            'purchase_order_line_id' => $poLine->id,
            'quantity' => 10,
            'unit_cost' => 4,
        ]]);

        $item->refresh();
        $this->assertSame('10.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('0.0000', number_format((float) $item->on_po_qty, 4, '.', ''));
        $this->assertSame('received', $po->fresh()->status);

        Livewire::test(VendorBillForm::class)
            ->set('docType', 'credit')
            ->set('isRtv', true)
            ->set('vendor_id', (string) $vendor->id)
            ->set('purchase_order_id', (string) $po->id)
            ->call('selectPurchaseOrder')
            ->assertSet('lines.0.item_id', (string) $item->id)
            ->assertSet('lines.0.quantity', '10.00')
            ->set('lines.0.quantity', '3')
            ->set('lines.0.amount', '12.00')
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $item->refresh();
        $poLine->refresh();
        $po->refresh();

        $this->assertSame('7.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('3.0000', number_format((float) $item->on_po_qty, 4, '.', ''));
        $this->assertSame('7.0000', number_format((float) $poLine->qty_received, 4, '.', ''));
        $this->assertSame('partial', $po->status);
    }
}
