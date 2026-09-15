<?php

namespace Tests\Feature;

use App\Actions\Sales\ApplyCreditMemoAction;
use App\Actions\Sales\CreateCreditMemoAction;
use App\Actions\Sales\RefundCreditMemoAction;
use App\Livewire\Sales\CreditMemoApply;
use App\Models\Account;
use App\Models\CreditMemo;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\JournalEntry;
use App\Models\User;
use Database\Seeders\ChartOfAccountsSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CreditMemoApplyRefundTest extends TestCase
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

    public function test_apply_credit_reduces_invoice_balance_and_remaining_credit(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['balance' => 100, 'is_active' => true]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'on_hand' => 20,
            'average_cost' => 1,
            'sales_price' => 40,
            'is_active' => true,
        ]);

        $memo = app(CreateCreditMemoAction::class)->handle([
            'credit_number' => 'CM-APPLY-1',
            'customer_id' => $customer->id,
            'credit_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ], [
            ['item_id' => $item->id, 'quantity' => 1, 'rate' => 40],
        ]);

        $invoice = Invoice::query()->create([
            'invoice_number' => 'INV-APPLY-1',
            'customer_id' => $customer->id,
            'invoice_date' => now()->toDateString(),
            'status' => 'open',
            'subtotal' => 40,
            'tax_total' => 0,
            'total' => 40,
            'amount_paid' => 0,
            'balance_due' => 40,
            'created_by' => $this->owner->id,
        ]);

        app(ApplyCreditMemoAction::class)->handle($memo, [
            ['invoice_id' => $invoice->id, 'amount' => 40],
        ]);

        $this->assertSame('0.00', number_format((float) $memo->fresh()->remaining_credit, 2, '.', ''));
        $this->assertSame('applied', $memo->fresh()->status);
        $this->assertSame('0.00', number_format((float) $invoice->fresh()->balance_due, 2, '.', ''));
        $this->assertSame('paid', $invoice->fresh()->status);
        $this->assertTrue(JournalEntry::query()->where('entry_number', 'JE-CM-CM-APPLY-1')->exists());
    }

    public function test_give_refund_posts_cash_and_clears_remaining_credit(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['balance' => 50, 'is_active' => true]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'on_hand' => 5,
            'average_cost' => 1,
            'sales_price' => 25,
            'is_active' => true,
        ]);

        $memo = app(CreateCreditMemoAction::class)->handle([
            'credit_number' => 'CM-REF-1',
            'customer_id' => $customer->id,
            'credit_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ], [
            ['item_id' => $item->id, 'quantity' => 1, 'rate' => 25],
        ]);

        $cash = Account::query()->where('number', '1000')->firstOrFail();

        app(RefundCreditMemoAction::class)->handle($memo, [
            'refund_date' => now()->toDateString(),
            'amount' => 25,
            'method' => 'cash',
            'account_id' => $cash->id,
            'created_by' => $this->owner->id,
        ]);

        $this->assertSame('0.00', number_format((float) $memo->fresh()->remaining_credit, 2, '.', ''));
        $this->assertSame('refunded', $memo->fresh()->status);
        $this->assertTrue(JournalEntry::query()->where('entry_number', 'like', 'JE-CMR-CM-REF-1-%')->exists());
    }

    public function test_apply_page_loads_for_open_credit_memo(): void
    {
        $this->actingAs($this->owner);

        $memo = CreditMemo::query()->create([
            'credit_number' => 'CM-UI-1',
            'customer_id' => Customer::factory()->create()->id,
            'credit_date' => now()->toDateString(),
            'status' => 'open',
            'subtotal' => 10,
            'tax_total' => 0,
            'total' => 10,
            'remaining_credit' => 10,
        ]);

        $this->get(route('credit-memos.apply', $memo))
            ->assertOk()
            ->assertSee('Apply / Refund Credit')
            ->assertSee('CM-UI-1');

        Livewire::test(CreditMemoApply::class, ['creditMemo' => $memo])
            ->assertSet('mode', 'apply')
            ->set('mode', 'refund')
            ->assertSet('mode', 'refund');
    }
}
