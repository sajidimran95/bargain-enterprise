<?php

namespace Tests\Feature;

use App\Actions\Sales\CreateInvoiceAction;
use App\Actions\Sales\ReceivePaymentAction;
use App\Actions\Sales\UpdateInvoiceAction;
use App\Livewire\Sales\InvoiceForm;
use App\Models\CreditMemo;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Payment;
use App\Models\User;
use App\Support\DocumentNumbers;
use Database\Seeders\ChartOfAccountsSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InvoiceEditStockPaymentTest extends TestCase
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

    public function test_editing_invoice_adjusts_stock_when_lines_change(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true, 'balance' => 0]);
        $itemA = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 50,
            'average_cost' => 2,
            'sales_price' => 10,
        ]);
        $itemB = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 50,
            'average_cost' => 3,
            'sales_price' => 15,
        ]);

        $invoice = app(CreateInvoiceAction::class)->handle([
            'customer_id' => $customer->id,
            'invoice_number' => 'INV-EDIT-1',
            'invoice_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ], [[
            'item_id' => $itemA->id,
            'quantity' => 2,
            'rate' => 10,
            'taxable' => false,
        ]]);

        $this->assertSame('48.0000', number_format((float) $itemA->fresh()->on_hand, 4, '.', ''));

        Livewire::test(InvoiceForm::class)
            ->call('findPreviousDocument')
            ->assertSet('navigatorId', $invoice->id)
            ->assertSet('invoice_number', 'INV-EDIT-1')
            ->set('lines.0.item_id', (string) $itemB->id)
            ->set('lines.0.quantity', '1')
            ->set('lines.0.rate', '15')
            ->set('lines.0.amount', '15.00')
            ->set('lines.0.taxable', false)
            ->set('tax_code_id', '')
            ->call('saveAndClose')
            ->assertRedirect(route('invoices.index'));

        $this->assertSame('50.0000', number_format((float) $itemA->fresh()->on_hand, 4, '.', ''));
        $this->assertSame('49.0000', number_format((float) $itemB->fresh()->on_hand, 4, '.', ''));
        $this->assertSame(1, Invoice::query()->count());
        $this->assertSame('15.00', number_format((float) $invoice->fresh()->total, 2, '.', ''));
    }

    public function test_editing_invoice_qty_increase_decrease_add_and_remove_recalculates_stock_and_totals(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true, 'balance' => 0]);
        $itemA = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 100,
            'average_cost' => 2,
            'sales_price' => 10,
        ]);
        $itemB = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 50,
            'average_cost' => 3,
            'sales_price' => 20,
        ]);

        $invoice = app(CreateInvoiceAction::class)->handle([
            'customer_id' => $customer->id,
            'invoice_number' => 'INV-QTY-EDIT',
            'invoice_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ], [[
            'item_id' => $itemA->id,
            'quantity' => 2,
            'rate' => 10,
            'taxable' => false,
        ]]);

        $this->assertSame('98.0000', number_format((float) $itemA->fresh()->on_hand, 4, '.', ''));
        $this->assertSame('20.00', number_format((float) $invoice->fresh()->total, 2, '.', ''));

        // Increase qty 2 → 5 (stock out +3, total 50)
        Livewire::test(InvoiceForm::class, ['invoice' => $invoice])
            ->assertSet('navigatorId', $invoice->id)
            ->set('lines.0.quantity', '5')
            ->assertSet('lines.0.amount', '50.00')
            ->call('saveAndClose')
            ->assertRedirect(route('invoices.index'));

        $this->assertSame('95.0000', number_format((float) $itemA->fresh()->on_hand, 4, '.', ''));
        $this->assertSame('50.00', number_format((float) $invoice->fresh()->total, 2, '.', ''));

        // Decrease qty 5 → 3 (stock in +2, total 30)
        Livewire::test(InvoiceForm::class, ['invoice' => $invoice->fresh()])
            ->set('lines.0.quantity', '3')
            ->assertSet('lines.0.amount', '30.00')
            ->call('saveAndClose')
            ->assertRedirect(route('invoices.index'));

        $this->assertSame('97.0000', number_format((float) $itemA->fresh()->on_hand, 4, '.', ''));
        $this->assertSame('30.00', number_format((float) $invoice->fresh()->total, 2, '.', ''));

        // Add second item line
        Livewire::test(InvoiceForm::class, ['invoice' => $invoice->fresh()])
            ->call('addLine')
            ->set('lines.1.item_id', (string) $itemB->id)
            ->set('lines.1.quantity', '2')
            ->set('lines.1.rate', '20')
            ->set('lines.1.taxable', false)
            ->assertSet('lines.1.amount', '40.00')
            ->call('saveAndClose')
            ->assertRedirect(route('invoices.index'));

        $this->assertSame('97.0000', number_format((float) $itemA->fresh()->on_hand, 4, '.', ''));
        $this->assertSame('48.0000', number_format((float) $itemB->fresh()->on_hand, 4, '.', ''));
        $this->assertSame('70.00', number_format((float) $invoice->fresh()->total, 2, '.', ''));
        $this->assertSame(2, $invoice->fresh()->lines()->count());

        // Remove first line (item A qty 3 restored)
        Livewire::test(InvoiceForm::class, ['invoice' => $invoice->fresh()])
            ->call('removeLine', 0)
            ->call('saveAndClose')
            ->assertRedirect(route('invoices.index'));

        $this->assertSame('100.0000', number_format((float) $itemA->fresh()->on_hand, 4, '.', ''));
        $this->assertSame('48.0000', number_format((float) $itemB->fresh()->on_hand, 4, '.', ''));
        $this->assertSame('40.00', number_format((float) $invoice->fresh()->total, 2, '.', ''));
        $this->assertSame(1, $invoice->fresh()->lines()->count());
        $this->assertSame(1, Invoice::query()->count());
    }

    public function test_paid_invoice_total_increase_leaves_extra_balance_due(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true, 'balance' => 0]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 50,
            'average_cost' => 2,
            'sales_price' => 10,
        ]);

        $invoice = app(CreateInvoiceAction::class)->handle([
            'customer_id' => $customer->id,
            'invoice_number' => 'INV-EDIT-UP',
            'invoice_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ], [[
            'item_id' => $item->id,
            'quantity' => 1,
            'rate' => 10,
            'taxable' => false,
        ]]);

        app(ReceivePaymentAction::class)->handle([
            'customer_id' => $customer->id,
            'payment_number' => DocumentNumbers::next(Payment::class, 'payment_number', 'PMT-'),
            'payment_date' => now()->toDateString(),
            'amount' => '10.00',
            'method' => 'cash',
            'created_by' => $this->owner->id,
        ], [['invoice_id' => $invoice->id, 'amount' => '10.00']]);

        $invoice->refresh();
        $this->assertSame('paid', $invoice->status);

        $result = app(UpdateInvoiceAction::class)->handle($invoice, [
            'customer_id' => $customer->id,
            'invoice_number' => 'INV-EDIT-UP',
            'invoice_date' => now()->toDateString(),
            'updated_by' => $this->owner->id,
            'is_pending' => false,
        ], [[
            'item_id' => $item->id,
            'quantity' => 2,
            'rate' => 10,
            'taxable' => false,
        ]]);

        $updated = $result['invoice'];
        $this->assertSame('20.00', number_format((float) $updated->total, 2, '.', ''));
        $this->assertSame('10.00', number_format((float) $updated->amount_paid, 2, '.', ''));
        $this->assertSame('10.00', number_format((float) $updated->balance_due, 2, '.', ''));
        $this->assertSame('partial', $updated->status);
        $this->assertSame('10.00', $result['amount_still_due']);
        $this->assertSame('0.00', $result['overpayment']);
        $this->assertSame('48.0000', number_format((float) $item->fresh()->on_hand, 4, '.', ''));
    }

    public function test_paid_invoice_total_decrease_creates_overpayment_credit(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true, 'balance' => 0]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 50,
            'average_cost' => 2,
            'sales_price' => 10,
        ]);
        Item::factory()->create([
            'type' => 'service',
            'is_active' => true,
            'name' => 'Service Fee',
            'sales_price' => 1,
        ]);

        $invoice = app(CreateInvoiceAction::class)->handle([
            'customer_id' => $customer->id,
            'invoice_number' => 'INV-EDIT-DOWN',
            'invoice_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ], [[
            'item_id' => $item->id,
            'quantity' => 2,
            'rate' => 10,
            'taxable' => false,
        ]]);

        app(ReceivePaymentAction::class)->handle([
            'customer_id' => $customer->id,
            'payment_number' => DocumentNumbers::next(Payment::class, 'payment_number', 'PMT-'),
            'payment_date' => now()->toDateString(),
            'amount' => '20.00',
            'method' => 'cash',
            'created_by' => $this->owner->id,
        ], [['invoice_id' => $invoice->id, 'amount' => '20.00']]);

        $result = app(UpdateInvoiceAction::class)->handle($invoice->fresh(), [
            'customer_id' => $customer->id,
            'invoice_number' => 'INV-EDIT-DOWN',
            'invoice_date' => now()->toDateString(),
            'updated_by' => $this->owner->id,
            'is_pending' => false,
        ], [[
            'item_id' => $item->id,
            'quantity' => 1,
            'rate' => 10,
            'taxable' => false,
        ]]);

        $updated = $result['invoice'];
        $this->assertSame('10.00', number_format((float) $updated->total, 2, '.', ''));
        $this->assertSame('10.00', number_format((float) $updated->amount_paid, 2, '.', ''));
        $this->assertSame('0.00', number_format((float) $updated->balance_due, 2, '.', ''));
        $this->assertSame('paid', $updated->status);
        $this->assertSame('10.00', $result['overpayment']);
        $this->assertNotNull($result['overpayment_credit']);
        $this->assertDatabaseHas('credit_memos', [
            'id' => $result['overpayment_credit']->id,
            'customer_id' => $customer->id,
            'remaining_credit' => '10.00',
        ]);
        $this->assertSame(1, CreditMemo::query()->count());
        $this->assertSame('49.0000', number_format((float) $item->fresh()->on_hand, 4, '.', ''));
    }
}
