<?php

namespace Tests\Feature;

use App\Actions\Customers\CreateCustomerAction;
use App\Livewire\Customers\CustomerCenter;
use App\Livewire\Customers\CustomerForm;
use App\Models\Customer;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CustomerCenterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_manager_can_view_customer_center(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        Customer::factory()->create(['display_name' => 'Mobil North Haven', 'company_name' => 'Mobil']);

        $this->actingAs($user)
            ->get(route('customers.index'))
            ->assertOk()
            ->assertSee('Customer Center')
            ->assertSee('Mobil North Haven');
    }

    public function test_sales_without_customer_view_is_forbidden(): void
    {
        $user = User::factory()->create();
        // sales has customer.view — use a user with no roles
        $this->actingAs($user)
            ->get(route('customers.index'))
            ->assertForbidden();
    }

    public function test_create_customer_action_persists_record(): void
    {
        $customer = app(CreateCustomerAction::class)->handle([
            'company_name' => 'Corner Smoke Shop',
            'display_name' => 'Corner Smoke Shop',
            'bill_to_city' => 'Bridgeport',
            'bill_to_state' => 'CT',
        ]);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'company_name' => 'Corner Smoke Shop',
            'bill_to_city' => 'Bridgeport',
        ]);
    }

    public function test_livewire_can_create_customer(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        Livewire::actingAs($user)
            ->test(CustomerForm::class)
            ->set('company_name', 'Test Mart')
            ->set('display_name', 'Test Mart')
            ->call('save')
            ->assertRedirect();

        $this->assertDatabaseHas('customers', ['company_name' => 'Test Mart']);
    }

    public function test_customer_center_can_select_and_add_note(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        $customer = Customer::factory()->create(['display_name' => 'Alpha Store']);

        Livewire::actingAs($user)
            ->test(CustomerCenter::class)
            ->call('selectCustomer', $customer->id)
            ->set('noteBody', 'Pinned account note')
            ->set('activeTab', 'notes')
            ->call('addNote')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('customer_notes', [
            'customer_id' => $customer->id,
            'body' => 'Pinned account note',
        ]);
    }

    public function test_deactivate_customer_marks_inactive(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        $customer = Customer::factory()->create();

        Livewire::actingAs($user)
            ->test(CustomerCenter::class, ['customer' => $customer])
            ->call('deactivate');

        $this->assertFalse($customer->fresh()->is_active);
    }
}
