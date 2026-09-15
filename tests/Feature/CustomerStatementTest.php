<?php

namespace Tests\Feature;

use App\Livewire\Sales\CustomerStatement;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CustomerStatementTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->owner = User::factory()->create();
        $this->owner->assignRole('owner');
    }

    public function test_customer_statement_page_loads_and_shows_activity(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create([
            'display_name' => 'Statement Customer',
            'is_active' => true,
        ]);

        Invoice::query()->create([
            'invoice_number' => 'INV-STMT-1',
            'customer_id' => $customer->id,
            'invoice_date' => now()->toDateString(),
            'status' => 'open',
            'subtotal' => 150,
            'tax_total' => 0,
            'total' => 150,
            'amount_paid' => 0,
            'balance_due' => 150,
            'created_by' => $this->owner->id,
        ]);

        $this->get(route('customers.statements'))
            ->assertOk()
            ->assertSee('Create Statements');

        Livewire::test(CustomerStatement::class)
            ->set('customer_id', (string) $customer->id)
            ->set('datePreset', 'this_month')
            ->assertSee('Statement Customer')
            ->assertSee('INV-STMT-1')
            ->assertSee('Invoice');
    }

    public function test_customers_menu_includes_create_statements(): void
    {
        $item = collect(config('erp_menubar.Customers'))
            ->firstWhere('label', 'Create Statements');

        $this->assertSame('customers.statements', $item['route'] ?? null);
    }

    public function test_apply_credits_and_coa_use_qb_chrome(): void
    {
        $this->actingAs($this->owner);

        $this->get(route('accounting.chart'))
            ->assertOk()
            ->assertSee('Chart of Accounts')
            ->assertSee('Look for');
    }
}
