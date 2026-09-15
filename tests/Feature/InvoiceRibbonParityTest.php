<?php

namespace Tests\Feature;

use App\Livewire\Dashboard\CompanySnapshot;
use App\Livewire\Sales\CreditMemoForm;
use App\Livewire\Sales\InvoiceForm;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\ChartOfAccountsSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InvoiceRibbonParityTest extends TestCase
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

    public function test_invoice_form_shows_class_template_and_main_ribbon_actions(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(InvoiceForm::class)
            ->assertSee('Class')
            ->assertSee('Template')
            ->assertSee('Create a Copy')
            ->assertSee('Memorize')
            ->assertSee('Mark As Pending')
            ->assertSee('Print Later')
            ->assertSee('Email Later')
            ->assertSee('Attach File')
            ->assertSee('Add Time/Costs')
            ->assertSee('Create a Batch')
            ->assertSee('Save & Close')
            ->assertSee('Save & New')
            ->assertSee('Balance Due')
            ->call('toggleInspector')
            ->assertSet('inspectorOpen', false)
            ->call('toggleInspector')
            ->assertSet('inspectorOpen', true)
            ->assertSee('Customer')
            ->assertSee('Transaction')
            ->call('togglePending')
            ->assertSet('is_pending', true)
            ->call('memorize')
            ->assertSet('class', '')
            ->set('class', 'Wholesale')
            ->set('template', 'Copy of Intuit Product Invoice')
            ->set('print_later', true)
            ->assertSet('print_later', true);
    }

    public function test_invoice_persists_presentation_fields_and_pending_skips_stock(): void
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

        Livewire::test(InvoiceForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('class', 'Retail')
            ->set('template', 'Intuit Product Invoice')
            ->set('print_later', true)
            ->set('email_later', true)
            ->set('is_pending', true)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.item_code', $item->sku)
            ->set('lines.0.quantity', '2')
            ->set('lines.0.rate', '10')
            ->set('lines.0.amount', '20.00')
            ->call('saveAndClose')
            ->assertRedirect(route('invoices.index'));

        $invoice = Invoice::query()->first();
        $this->assertNotNull($invoice);
        $this->assertSame('Retail', $invoice->class);
        $this->assertSame('Intuit Product Invoice', $invoice->template);
        $this->assertTrue($invoice->print_later);
        $this->assertTrue($invoice->email_later);
        $this->assertTrue($invoice->is_pending);
        $this->assertSame('pending', $invoice->status);
        $this->assertSame('50.0000', number_format((float) $item->fresh()->on_hand, 4, '.', ''));
        $this->assertSame('0.00', number_format((float) $customer->fresh()->balance, 2, '.', ''));
    }

    public function test_credit_memo_form_shows_class_template_and_po_number(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(CreditMemoForm::class)
            ->assertSee('Class')
            ->assertSee('Template')
            ->assertSee('P.O. No.')
            ->assertSee('Create a Copy')
            ->assertSee('Memorize')
            ->assertSee('Apply Credits')
            ->assertSee('Remaining Credit')
            ->assertSee('Save & Close')
            ->assertSee('Save & New')
            ->assertSee('Customer')
            ->assertSee('Transaction')
            ->set('class', 'Returns')
            ->set('po_number', 'PO-99')
            ->assertSet('po_number', 'PO-99');
    }

    public function test_invoice_ribbon_actions_are_wired(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 50,
            'sales_price' => 10,
        ]);

        Invoice::query()->create([
            'invoice_number' => 'INV-RIBBON-1',
            'customer_id' => $customer->id,
            'invoice_date' => now()->toDateString(),
            'status' => 'open',
            'subtotal' => 10,
            'tax_total' => 0,
            'total' => 10,
            'amount_paid' => 0,
            'balance_due' => 10,
        ]);

        Livewire::test(InvoiceForm::class)
            ->call('findPreviousDocument')
            ->assertSet('customer_id', (string) $customer->id)
            ->call('openTimeCostsModal')
            ->assertSet('showTimeCostsModal', true)
            ->set('timeCostDescription', 'Delivery fee')
            ->set('timeCostAmount', '5.00')
            ->call('applyTimeCosts')
            ->assertSet('showTimeCostsModal', false)
            ->call('togglePending')
            ->assertSet('is_pending', true)
            ->call('memorize')
            ->assertSet('class', '')
            ->call('openAttachModal')
            ->assertSet('showAttachModal', true);
    }

    public function test_snapshot_pop_and_widget_config(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(CompanySnapshot::class)
            ->assertSee('Prev Year Income Comparison')
            ->assertSee('Add Content')
            ->call('toggleAddContent')
            ->assertSet('showAddContent', true)
            ->assertSee('Income and Expense Trend')
            ->call('toggleWidget', 'pop')
            ->assertSet('widgets.pop', false)
            ->call('restoreDefaultWidgets')
            ->assertSet('widgets.pop', true)
            ->assertSet('showAddContent', false);
    }
}
