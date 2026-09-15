<?php

namespace Tests\Feature;

use App\Livewire\Dashboard\CompanySnapshot;
use App\Livewire\Purchasing\VendorBillForm;
use App\Livewire\Purchasing\VendorPaymentForm;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InsightsTabsTest extends TestCase
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

    public function test_insights_payments_and_customer_tabs_switch(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(CompanySnapshot::class)
            ->assertSet('insightsTab', 'company')
            ->assertSee('Income and Expense Trend')
            ->call('setInsightsTab', 'payments')
            ->assertSet('insightsTab', 'payments')
            ->assertSee('Payment Summary')
            ->assertSee('Undeposited Payments')
            ->call('setInsightsTab', 'customer')
            ->assertSet('insightsTab', 'customer')
            ->assertSee('Open Invoices')
            ->assertSee('Top Customers by Sales');
    }

    public function test_enter_bills_shows_bill_credit_and_expense_tabs(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(VendorBillForm::class)
            ->assertSee('Bill')
            ->assertSee('Credit')
            ->assertSee('Expenses')
            ->assertSee('Items')
            ->assertSee('Select PO')
            ->set('lineTab', 'expenses')
            ->assertSee('Add Expense Line');
    }

    public function test_pay_bills_page_loads_with_bill_grid(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(VendorPaymentForm::class)
            ->assertSee('Pay Bills')
            ->assertSee('Bills to Pay')
            ->assertSee('Select All / Pay All');
    }
}
