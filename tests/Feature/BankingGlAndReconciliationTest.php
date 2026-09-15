<?php

namespace Tests\Feature;

use App\Actions\Banking\CreateCheckAction;
use App\Actions\Banking\CreateDepositAction;
use App\Actions\Sales\ReceivePaymentAction;
use App\Livewire\Banking\DepositForm;
use App\Livewire\Banking\ReconciliationWorksheet;
use App\Models\Account;
use App\Models\BankAccount;
use App\Models\Check;
use App\Models\Customer;
use App\Models\Deposit;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\Payment;
use App\Models\User;
use Database\Seeders\ChartOfAccountsSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BankingGlAndReconciliationTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected BankAccount $bankAccount;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->seed(ChartOfAccountsSeeder::class);

        $this->owner = User::factory()->create();
        $this->owner->assignRole('owner');

        $gl = Account::query()->where('number', '1010')->firstOrFail();
        $this->bankAccount = BankAccount::query()->create([
            'account_id' => $gl->id,
            'name' => 'Operating Account',
            'bank_name' => 'Test Bank',
            'account_number_mask' => '****1010',
            'opening_balance' => 1000,
            'is_active' => true,
        ]);
    }

    public function test_deposit_posts_bank_and_clears_undeposited_funds(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['balance' => 50, 'is_active' => true]);
        $invoice = Invoice::query()->create([
            'invoice_number' => 'INV-DEP-1',
            'customer_id' => $customer->id,
            'invoice_date' => now()->toDateString(),
            'status' => 'open',
            'subtotal' => 50,
            'tax_total' => 0,
            'total' => 50,
            'amount_paid' => 0,
            'balance_due' => 50,
            'created_by' => $this->owner->id,
        ]);

        $payment = app(ReceivePaymentAction::class)->handle([
            'customer_id' => $customer->id,
            'payment_number' => 'PMT-DEP-1',
            'payment_date' => now()->toDateString(),
            'amount' => 50,
            'method' => 'check',
            'created_by' => $this->owner->id,
        ], [
            ['invoice_id' => $invoice->id, 'amount' => 50],
        ]);

        $this->assertFalse($payment->deposited);
        $this->assertDatabaseHas('journal_entries', ['entry_number' => 'JE-PMT-PMT-DEP-1']);

        Livewire::test(DepositForm::class)
            ->set('bank_account_id', (string) $this->bankAccount->id)
            ->set('selectedPayments.'.$payment->id, true)
            ->call('save')
            ->assertRedirect(route('deposits.index'));

        $payment->refresh();
        $this->assertTrue($payment->deposited);
        $this->assertDatabaseHas('deposits', ['bank_account_id' => $this->bankAccount->id, 'total' => '50.00']);
        $this->assertTrue(JournalEntry::query()->where('entry_number', 'like', 'JE-DEP-%')->exists());
    }

    public function test_check_posts_expense_and_bank_credit(): void
    {
        $this->actingAs($this->owner);

        $check = app(CreateCheckAction::class)->handle([
            'check_number' => 'CHK-1001',
            'bank_account_id' => $this->bankAccount->id,
            'check_date' => now()->toDateString(),
            'amount' => 75.25,
            'payee' => 'Office Supply Co',
            'memo' => 'Supplies',
            'created_by' => $this->owner->id,
        ]);

        $this->assertDatabaseHas('checks', [
            'id' => $check->id,
            'amount' => '75.25',
        ]);
        $this->assertDatabaseHas('journal_entries', [
            'entry_number' => 'JE-CHK-CHK-1001',
            'reference_type' => Check::class,
            'reference_id' => $check->id,
        ]);
    }

    public function test_reconciliation_finishes_when_difference_is_zero(): void
    {
        $this->actingAs($this->owner);

        $payment = Payment::query()->create([
            'payment_number' => 'PMT-R1',
            'customer_id' => Customer::factory()->create()->id,
            'payment_date' => now()->toDateString(),
            'amount' => 100,
            'unapplied_amount' => 0,
            'method' => 'check',
            'deposited' => false,
            'created_by' => $this->owner->id,
        ]);

        $deposit = app(CreateDepositAction::class)->handle([
            'number' => 'DEP-R1',
            'bank_account_id' => $this->bankAccount->id,
            'deposit_date' => now()->toDateString(),
            'created_by' => $this->owner->id,
        ], [$payment->id]);

        $check = app(CreateCheckAction::class)->handle([
            'check_number' => 'CHK-R1',
            'bank_account_id' => $this->bankAccount->id,
            'check_date' => now()->toDateString(),
            'amount' => 40,
            'payee' => 'Vendor',
            'created_by' => $this->owner->id,
        ]);

        // opening 1000 + deposit 100 - check 40 = 1060
        Livewire::test(ReconciliationWorksheet::class)
            ->set('bank_account_id', (string) $this->bankAccount->id)
            ->set('statement_date', now()->toDateString())
            ->set('statement_ending_balance', '1060.00')
            ->call('start')
            ->assertSet('started', true)
            ->set('clearedDeposits.'.$deposit->id, true)
            ->set('clearedChecks.'.$check->id, true)
            ->call('finish')
            ->assertSet('started', false)
            ->assertHasNoErrors();

        $this->assertNotNull($deposit->fresh()->cleared_at);
        $this->assertNotNull($check->fresh()->cleared_at);
    }

    public function test_reconciliation_page_loads(): void
    {
        $this->actingAs($this->owner)
            ->get(route('reconciliation.index'))
            ->assertOk()
            ->assertSee('Reconcile')
            ->assertDontSee('Coming in Phase');
    }
}
