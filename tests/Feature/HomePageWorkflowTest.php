<?php

namespace Tests\Feature;

use App\Livewire\Dashboard\HomePlaceholder;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class HomePageWorkflowTest extends TestCase
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

    public function test_home_page_shows_qb_workflow_sections(): void
    {
        $this->actingAs($this->owner);

        $this->get(route('dashboard.home'))
            ->assertOk()
            ->assertSee('Vendors')
            ->assertSee('Customers')
            ->assertSee('Employees')
            ->assertSee('Company')
            ->assertSee('Banking')
            ->assertSee('Purchase Orders')
            ->assertSee('Receive Inventory')
            ->assertSee('Enter Bills Against Inventory')
            ->assertSee('Pay Bills')
            ->assertSee('Enter Bills')
            ->assertSee('Quotes (Estimates)')
            ->assertSee('Sales Orders')
            ->assertSee('Create Invoices')
            ->assertSee('Receive Payments')
            ->assertSee('Statement Charges')
            ->assertSee('Statements')
            ->assertSee('Refunds &amp; Credits', false)
            ->assertSee('Turn On Payroll')
            ->assertSee('Chart of Accounts')
            ->assertSee('Record Deposits')
            ->assertSee('Check Register')
            ->assertSee('New Business Loans')
            ->assertSee('Manage Sales Tax')
            ->assertSee('href="/purchase-orders/create?embed=1"', false)
            ->assertSee('href="/employees/time?embed=1"', false)
            ->assertSee('href="/employees/payroll?embed=1"', false)
            ->assertSee('href="/banking/create?embed=1"', false);
    }

    public function test_enter_time_and_payroll_pages_load(): void
    {
        $this->actingAs($this->owner);

        $this->get(route('employees.time'))
            ->assertOk()
            ->assertSee('Enter Time')
            ->assertSee('Weekly Timesheet');

        $this->get(route('employees.payroll'))
            ->assertOk()
            ->assertSee('Turn On Payroll');
    }

    public function test_home_page_shows_live_open_invoice_badge(): void
    {
        $this->actingAs($this->owner);

        Invoice::query()->create([
            'customer_id' => Customer::factory()->create()->id,
            'invoice_number' => 'INV-HOME-1',
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(15)->toDateString(),
            'status' => 'open',
            'subtotal' => 100,
            'tax_total' => 0,
            'total' => 100,
            'balance_due' => 100,
        ]);

        Livewire::test(HomePlaceholder::class)
            ->assertSee('Create Invoices')
            ->assertSee('1');
    }
}
