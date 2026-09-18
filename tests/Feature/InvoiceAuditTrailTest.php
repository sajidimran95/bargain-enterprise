<?php

namespace Tests\Feature;

use App\Actions\Sales\ReceivePaymentAction;
use App\Livewire\Sales\InvoiceForm;
use App\Models\AuditLog;
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

class InvoiceAuditTrailTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->seed(ChartOfAccountsSeeder::class);
        $this->owner = User::factory()->create(['name' => 'Owner User']);
        $this->owner->assignRole('owner');
    }

    public function test_invoice_stores_created_and_updated_by_and_shows_history(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create([
            'is_active' => true,
            'balance' => 0,
        ]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 50,
            'average_cost' => 2,
            'sales_price' => 10,
        ]);

        Livewire::test(InvoiceForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('tax_code_id', '')
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '2')
            ->set('lines.0.rate', '10')
            ->set('lines.0.amount', '20.00')
            ->set('lines.0.taxable', false)
            ->call('saveAndClose')
            ->assertRedirect(route('invoices.index'));

        $invoice = Invoice::query()->latest('id')->firstOrFail();
        $this->assertSame($this->owner->id, (int) $invoice->created_by);
        $this->assertSame($this->owner->id, (int) $invoice->updated_by);
        $this->assertNotNull($invoice->created_at);
        $this->assertNotNull($invoice->updated_at);

        $this->assertDatabaseHas('audit_logs', [
            'model_type' => Invoice::class,
            'model_id' => $invoice->id,
            'action' => 'created',
            'user_id' => $this->owner->id,
        ]);

        Livewire::test(InvoiceForm::class)
            ->call('findPreviousDocument')
            ->assertSet('navigatorId', $invoice->id)
            ->assertSee('Created:')
            ->assertSee('Last edit:')
            ->assertSee('Owner User')
            ->set('inspectorTab', 'history')
            ->assertSee('Who / When')
            ->assertSee('Activity');
    }

    public function test_payment_updates_invoice_editor_and_audit_history(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create([
            'is_active' => true,
            'balance' => 0,
        ]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 50,
            'average_cost' => 2,
            'sales_price' => 10,
        ]);

        Livewire::test(InvoiceForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('tax_code_id', '')
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '1')
            ->set('lines.0.rate', '10')
            ->set('lines.0.amount', '10.00')
            ->set('lines.0.taxable', false)
            ->call('saveAndClose')
            ->assertRedirect(route('invoices.index'));

        $invoice = Invoice::query()->latest('id')->firstOrFail();
        $editor = User::factory()->create(['name' => 'Payment Clerk']);
        $editor->assignRole('owner');
        $this->actingAs($editor);

        app(ReceivePaymentAction::class)->handle([
            'customer_id' => (int) $invoice->customer_id,
            'payment_number' => DocumentNumbers::next(Payment::class, 'payment_number', 'PMT-'),
            'payment_date' => now()->toDateString(),
            'amount' => '10.00',
            'method' => 'cash',
            'created_by' => $editor->id,
        ], [[
            'invoice_id' => $invoice->id,
            'amount' => '10.00',
        ]]);

        $invoice->refresh();
        $this->assertSame($this->owner->id, (int) $invoice->created_by);
        $this->assertSame($editor->id, (int) $invoice->updated_by);
        $this->assertSame('paid', $invoice->status);

        $this->assertTrue(
            AuditLog::query()
                ->where('model_type', Invoice::class)
                ->where('model_id', $invoice->id)
                ->where('action', 'updated')
                ->where('user_id', $editor->id)
                ->exists()
        );

        Livewire::test(InvoiceForm::class)
            ->call('findPreviousDocument')
            ->assertSee('Payment Clerk')
            ->set('inspectorTab', 'history')
            ->assertSee('Updated')
            ->assertSee('Payment Clerk');
    }
}
