<?php

namespace Tests\Feature;

use App\Livewire\Purchasing\GoodsReceiptIndex;
use App\Livewire\Purchasing\PurchaseOrderIndex;
use App\Livewire\Purchasing\VendorBillIndex;
use App\Livewire\Sales\InvoiceIndex;
use App\Models\Customer;
use App\Models\GoodsReceipt;
use App\Models\Invoice;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorBill;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ListSelectedPrintTest extends TestCase
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

    public function test_list_print_requires_selected_row(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(InvoiceIndex::class)
            ->call('printSelected')
            ->assertDispatched('be-toast');
    }

    public function test_invoice_list_print_opens_letter_print_view(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true]);
        $invoice = Invoice::query()->create([
            'customer_id' => $customer->id,
            'invoice_number' => 'INV-PRINT-1',
            'invoice_date' => now()->toDateString(),
            'status' => 'open',
            'subtotal' => 10,
            'tax_total' => 0,
            'total' => 10,
            'amount_paid' => 0,
            'balance_due' => 10,
        ]);

        Livewire::test(InvoiceIndex::class)
            ->call('selectLine', $invoice->id)
            ->call('printSelected')
            ->assertOk();

        $this->get(route('invoices.print', $invoice))
            ->assertOk()
            ->assertSee('Invoice #')
            ->assertSee('INV-PRINT-1')
            ->assertSee('Letter Print');
    }

    public function test_po_receive_and_rtv_list_print_open_letter_views(): void
    {
        $this->actingAs($this->owner);

        $vendor = Vendor::factory()->create(['display_name' => 'Print Vendor Co']);
        $po = PurchaseOrder::query()->create([
            'number' => 'PO-PRINT-1',
            'vendor_id' => $vendor->id,
            'order_date' => now()->toDateString(),
            'status' => 'open',
            'subtotal' => 10,
            'total' => 10,
        ]);
        $receipt = GoodsReceipt::query()->create([
            'number' => 'GR-PRINT-1',
            'vendor_id' => $vendor->id,
            'purchase_order_id' => $po->id,
            'receipt_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ]);
        $rtv = VendorBill::query()->create([
            'bill_number' => 'RTV-PRINT-1',
            'vendor_id' => $vendor->id,
            'bill_date' => now()->toDateString(),
            'status' => 'open',
            'subtotal' => 5,
            'total' => 5,
            'amount_paid' => 0,
            'balance_due' => 5,
            'memo' => 'CREDIT · Return',
        ]);

        Livewire::test(PurchaseOrderIndex::class)
            ->call('selectLine', $po->id)
            ->call('printSelected')
            ->assertOk();

        Livewire::test(GoodsReceiptIndex::class)
            ->call('selectLine', $receipt->id)
            ->call('printSelected')
            ->assertOk();

        Livewire::test(VendorBillIndex::class)
            ->call('selectLine', $rtv->id)
            ->call('printSelected')
            ->assertOk();

        $this->get(route('purchase-orders.print', $po))
            ->assertOk()
            ->assertSee('PO-PRINT-1')
            ->assertSee('Purchase Order');

        $this->get(route('goods-receipts.print', $receipt))
            ->assertOk()
            ->assertSee('GR-PRINT-1')
            ->assertSee('Receive Inventory');

        $this->get(route('vendor-bills.print', $rtv))
            ->assertOk()
            ->assertSee('RTV-PRINT-1')
            ->assertSee('Return to Vendor');
    }
}
