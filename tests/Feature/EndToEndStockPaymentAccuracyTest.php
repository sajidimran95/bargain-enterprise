<?php

namespace Tests\Feature;

use App\Actions\Purchasing\ReceiveGoodsAction;
use App\Actions\Sales\ReceivePaymentAction;
use App\Livewire\Purchasing\PurchaseOrderForm;
use App\Livewire\Purchasing\VendorBillForm;
use App\Livewire\Reports\InventoryStockReport;
use App\Livewire\Reports\InventoryValuationReport;
use App\Livewire\Reports\SalesByItemReport;
use App\Livewire\Sales\InvoiceForm;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Payment;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorBill;
use Database\Seeders\ChartOfAccountsSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EndToEndStockPaymentAccuracyTest extends TestCase
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

    public function test_full_chain_po_receive_bill_pay_invoice_payment_rtv_and_reports(): void
    {
        $this->actingAs($this->owner);

        $vendor = Vendor::factory()->create(['is_active' => true, 'balance' => 0]);
        $customer = Customer::factory()->create(['is_active' => true, 'balance' => 0]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 0,
            'on_po_qty' => 0,
            'average_cost' => 0,
            'purchase_cost' => 5,
            'sales_price' => 12,
        ]);

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
        $poLine = $po->lines->firstOrFail();

        app(ReceiveGoodsAction::class)->handle([
            'number' => 'GR-E2E-1',
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

        $item->refresh();
        $this->assertSame('10.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('0.0000', number_format((float) $item->on_po_qty, 4, '.', ''));

        Livewire::test(VendorBillForm::class)
            ->set('vendor_id', (string) $vendor->id)
            ->set('purchase_order_id', (string) $po->id)
            ->set('bill_received', false)
            ->set('pay_bill_now', true)
            ->set('payment_amount', '50.00')
            ->set('payment_method', 'check')
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '10')
            ->set('lines.0.rate', '5')
            ->set('lines.0.amount', '50.00')
            ->set('lines.0.purchase_order_line_id', (string) $poLine->id)
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $item->refresh();
        $vendor->refresh();
        $bill = VendorBill::query()->latest('id')->firstOrFail();

        $this->assertSame('10.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('paid', $bill->status);
        $this->assertSame('0.00', number_format((float) $bill->balance_due, 2, '.', ''));
        $this->assertSame('0.00', number_format((float) $vendor->balance, 2, '.', ''));

        Livewire::test(InvoiceForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('tax_code_id', '')
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '3')
            ->set('lines.0.rate', '12')
            ->set('lines.0.amount', '36.00')
            ->set('lines.0.taxable', false)
            ->call('saveAndClose')
            ->assertRedirect(route('invoices.index'));

        $item->refresh();
        $customer->refresh();
        $invoice = Invoice::query()->latest('id')->firstOrFail();

        $this->assertSame('7.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('36.00', number_format((float) $invoice->balance_due, 2, '.', ''));
        $this->assertSame('36.00', number_format((float) $customer->balance, 2, '.', ''));

        app(ReceivePaymentAction::class)->handle([
            'customer_id' => $customer->id,
            'payment_number' => 'PMT-E2E-1',
            'payment_date' => now()->toDateString(),
            'amount' => 36,
            'method' => 'cash',
            'created_by' => $this->owner->id,
        ], [[
            'invoice_id' => $invoice->id,
            'amount' => 36,
        ]]);

        $invoice->refresh();
        $customer->refresh();
        $this->assertSame('paid', $invoice->status);
        $this->assertSame('0.00', number_format((float) $invoice->balance_due, 2, '.', ''));
        $this->assertSame('0.00', number_format((float) $customer->balance, 2, '.', ''));
        $this->assertSame(1, Payment::query()->where('payment_number', 'PMT-E2E-1')->count());

        Livewire::test(VendorBillForm::class)
            ->set('isRtv', true)
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
        $this->assertSame('5.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('2.0000', number_format((float) $item->on_po_qty, 4, '.', ''));
        $this->assertSame('8.0000', number_format((float) $poLine->qty_received, 4, '.', ''));

        Livewire::test(InventoryStockReport::class)
            ->assertOk()
            ->assertSee($item->sku)
            ->assertSee('5');

        $valuation = Livewire::test(InventoryValuationReport::class);
        $valuation->assertOk();
        $rows = (new \ReflectionClass($valuation->instance()))
            ->getMethod('rows');
        $rows->setAccessible(true);
        $valuationRows = $rows->invoke($valuation->instance());
        $row = $valuationRows->firstWhere('sku', $item->sku);
        $this->assertNotNull($row);
        $this->assertSame(5.0, $row['qty']);
        $this->assertSame(25.0, $row['asset_value']);
    }

    public function test_bill_received_after_goods_receipt_does_not_double_stock(): void
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
            ->set('lines.0.quantity', '8')
            ->set('lines.0.rate', '4')
            ->set('lines.0.amount', '32.00')
            ->call('save')
            ->assertRedirect(route('purchase-orders.index'));

        $po = PurchaseOrder::query()->with('lines')->latest('id')->firstOrFail();
        $poLine = $po->lines->firstOrFail();

        app(ReceiveGoodsAction::class)->handle([
            'number' => 'GR-NO-DOUBLE',
            'vendor_id' => $vendor->id,
            'purchase_order_id' => $po->id,
            'receipt_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ], [[
            'item_id' => $item->id,
            'purchase_order_line_id' => $poLine->id,
            'quantity' => 8,
            'unit_cost' => 4,
        ]]);

        $this->assertSame('8.0000', number_format((float) $item->fresh()->on_hand, 4, '.', ''));

        Livewire::test(VendorBillForm::class)
            ->set('vendor_id', (string) $vendor->id)
            ->set('purchase_order_id', (string) $po->id)
            ->set('bill_received', true)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '8')
            ->set('lines.0.rate', '4')
            ->set('lines.0.amount', '32.00')
            ->set('lines.0.purchase_order_line_id', (string) $poLine->id)
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $item->refresh();
        $vendor->refresh();
        $bill = VendorBill::query()->latest('id')->firstOrFail();

        $this->assertSame('8.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('0.0000', number_format((float) $item->on_po_qty, 4, '.', ''));
        $this->assertSame('32.00', number_format((float) $bill->balance_due, 2, '.', ''));
        $this->assertSame('32.00', number_format((float) $vendor->balance, 2, '.', ''));
    }

    public function test_bill_received_without_prior_receipt_posts_stock_and_clears_on_po(): void
    {
        $this->actingAs($this->owner);

        $vendor = Vendor::factory()->create(['is_active' => true, 'balance' => 0]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 0,
            'on_po_qty' => 0,
            'average_cost' => 0,
            'purchase_cost' => 6,
            'sales_price' => 11,
        ]);

        Livewire::test(PurchaseOrderForm::class)
            ->set('vendor_id', (string) $vendor->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '5')
            ->set('lines.0.rate', '6')
            ->set('lines.0.amount', '30.00')
            ->call('save')
            ->assertRedirect(route('purchase-orders.index'));

        $po = PurchaseOrder::query()->with('lines')->latest('id')->firstOrFail();
        $poLine = $po->lines->firstOrFail();
        $this->assertSame('5.0000', number_format((float) $item->fresh()->on_po_qty, 4, '.', ''));

        Livewire::test(VendorBillForm::class)
            ->set('vendor_id', (string) $vendor->id)
            ->set('purchase_order_id', (string) $po->id)
            ->set('bill_received', true)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '5')
            ->set('lines.0.rate', '6')
            ->set('lines.0.amount', '30.00')
            ->set('lines.0.purchase_order_line_id', (string) $poLine->id)
            ->call('saveAndClose')
            ->assertRedirect(route('vendor-bills.index'));

        $item->refresh();
        $poLine->refresh();
        $po->refresh();

        $this->assertSame('5.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('0.0000', number_format((float) $item->on_po_qty, 4, '.', ''));
        $this->assertSame('5.0000', number_format((float) $poLine->qty_received, 4, '.', ''));
        $this->assertSame('received', $po->status);
    }

    public function test_sales_by_item_balance_uses_line_share_not_full_invoice_per_line(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true, 'balance' => 0]);
        $itemA = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'sku' => 'AUDIT-A',
            'barcode' => 'AUDIT-A-BC',
            'on_hand' => 50,
            'average_cost' => 1,
            'sales_price' => 10,
        ]);
        $itemB = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'sku' => 'AUDIT-B',
            'barcode' => 'AUDIT-B-BC',
            'on_hand' => 50,
            'average_cost' => 1,
            'sales_price' => 30,
        ]);

        Livewire::test(InvoiceForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('tax_code_id', '')
            ->set('lines.0.item_id', (string) $itemA->id)
            ->set('lines.0.quantity', '1')
            ->set('lines.0.rate', '10')
            ->set('lines.0.amount', '10.00')
            ->set('lines.0.taxable', false)
            ->call('addLine')
            ->set('lines.1.item_id', (string) $itemB->id)
            ->set('lines.1.quantity', '1')
            ->set('lines.1.rate', '30')
            ->set('lines.1.amount', '30.00')
            ->set('lines.1.taxable', false)
            ->call('saveAndClose')
            ->assertRedirect(route('invoices.index'));

        $invoice = Invoice::query()->with('lines')->latest('id')->firstOrFail();
        $this->assertSame('40.00', number_format((float) $invoice->balance_due, 2, '.', ''));
        $this->assertCount(2, $invoice->lines);

        $component = Livewire::test(SalesByItemReport::class)
            ->set('datePreset', 'all')
            ->call('applyDatePreset', 'all');

        $lineA = $invoice->lines->firstWhere('item_id', $itemA->id);
        $lineB = $invoice->lines->firstWhere('item_id', $itemB->id);
        $this->assertSame('10.00', $component->instance()->lineBalanceShare($lineA));
        $this->assertSame('30.00', $component->instance()->lineBalanceShare($lineB));

        $grouped = (new \ReflectionClass($component->instance()))->getMethod('groupedRows');
        $grouped->setAccessible(true);
        $groups = $grouped->invoke($component->instance());

        $groupA = $groups->first(fn (array $g) => (int) $g['lines']->first()->item_id === (int) $itemA->id);
        $groupB = $groups->first(fn (array $g) => (int) $g['lines']->first()->item_id === (int) $itemB->id);

        $this->assertNotNull($groupA);
        $this->assertNotNull($groupB);
        $this->assertSame('10.00', $groupA['balance']);
        $this->assertSame('30.00', $groupB['balance']);

        $grand = '0.00';
        foreach ($groups as $group) {
            $grand = bcadd($grand, $group['balance'], 2);
        }
        $this->assertSame('40.00', $grand);
    }
}
