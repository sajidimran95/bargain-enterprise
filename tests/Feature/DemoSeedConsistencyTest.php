<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\InventoryTransaction;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\JournalEntry;
use App\Models\JournalLine;
use App\Models\Payment;
use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Vendor;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoSeedConsistencyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_demo_seed_creates_expected_volumes(): void
    {
        $this->assertGreaterThanOrEqual(30, Customer::query()->count());
        $this->assertGreaterThanOrEqual(10, Vendor::query()->count());
        $this->assertGreaterThanOrEqual(100, Item::query()->count());
        $this->assertGreaterThanOrEqual(50, Invoice::query()->count());
        $this->assertGreaterThanOrEqual(20, PurchaseOrder::query()->count());
        $this->assertGreaterThan(0, InventoryTransaction::query()->count());
        $this->assertGreaterThan(0, Payment::query()->count());
        $this->assertGreaterThan(0, JournalEntry::query()->count());
    }

    public function test_customer_balances_match_open_invoice_balances(): void
    {
        $customerBalance = (float) Customer::query()->sum('balance');
        $invoiceBalance = (float) Invoice::query()->sum('balance_due');

        $this->assertEqualsWithDelta($customerBalance, $invoiceBalance, 0.05);
    }

    public function test_item_on_hand_matches_latest_ledger_balance(): void
    {
        $item = Item::query()
            ->whereHas('inventoryTransactions')
            ->inRandomOrder()
            ->first();

        $this->assertNotNull($item);

        $latest = InventoryTransaction::query()
            ->where('item_id', $item->id)
            ->orderByDesc('id')
            ->first();

        $this->assertEqualsWithDelta((float) $item->on_hand, (float) $latest->balance_after, 0.0001);
    }

    public function test_journal_entries_are_balanced(): void
    {
        $unbalanced = JournalEntry::query()
            ->withSum('lines as debit_sum', 'debit')
            ->withSum('lines as credit_sum', 'credit')
            ->get()
            ->filter(fn ($entry) => abs((float) $entry->debit_sum - (float) $entry->credit_sum) > 0.01);

        $this->assertTrue($unbalanced->isEmpty(), 'Found unbalanced journal entries.');
        $this->assertGreaterThan(0, JournalLine::query()->count());
    }

    public function test_seeded_users_can_login_and_see_live_modules(): void
    {
        $user = User::query()->where('email', 'owner@bargain.local')->firstOrFail();

        $this->actingAs($user)
            ->get(route('customers.index'))
            ->assertOk()
            ->assertSee('Customer Center');

        $this->actingAs($user)
            ->get(route('invoices.index'))
            ->assertOk()
            ->assertSee('INV-');

        $this->actingAs($user)
            ->get(route('inventory.index'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('dashboard.snapshots'))
            ->assertOk()
            ->assertSee('Accounts Receivable');
    }
}
