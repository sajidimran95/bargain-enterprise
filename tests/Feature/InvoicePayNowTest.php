<?php

namespace Tests\Feature;

use App\Livewire\Sales\InvoiceForm;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Payment;
use App\Models\User;
use Database\Seeders\ChartOfAccountsSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InvoicePayNowTest extends TestCase
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

    public function test_invoice_can_receive_payment_when_saving(): void
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
            ->set('receive_payment_now', true)
            ->set('payment_amount', '20.00')
            ->set('payment_method', 'cash')
            ->call('saveAndClose')
            ->assertRedirect(route('invoices.index'));

        $invoice = Invoice::query()->latest('id')->firstOrFail();
        $this->assertSame('paid', $invoice->status);
        $this->assertSame('0.00', number_format((float) $invoice->balance_due, 2, '.', ''));
        $this->assertSame('20.00', number_format((float) $invoice->amount_paid, 2, '.', ''));

        $this->assertDatabaseHas('payments', [
            'customer_id' => $customer->id,
            'amount' => '20.00',
            'method' => 'cash',
        ]);

        $payment = Payment::query()->latest('id')->firstOrFail();
        $this->assertDatabaseHas('payment_allocations', [
            'payment_id' => $payment->id,
            'invoice_id' => $invoice->id,
            'amount' => '20.00',
        ]);

        $item->refresh();
        $this->assertSame('48.0000', number_format((float) $item->on_hand, 4, '.', ''));
    }
}
