<?php

namespace Tests\Feature;

use App\Actions\Customers\CreateCustomerAction;
use App\Actions\Customers\UpdateCustomerAction;
use App\Livewire\Audit\AuditLogIndex;
use App\Models\AuditLog;
use App\Models\Customer;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AuditLogTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected User $sales;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->owner = User::factory()->create();
        $this->owner->assignRole('owner');
        $this->sales = User::factory()->create();
        $this->sales->assignRole('sales');
    }

    public function test_owner_can_view_audit_log_page(): void
    {
        $this->actingAs($this->owner)
            ->get(route('audit.index'))
            ->assertOk()
            ->assertSee('Audit Log');
    }

    public function test_sales_cannot_view_audit_log_page(): void
    {
        $this->actingAs($this->sales)
            ->get(route('audit.index'))
            ->assertForbidden();
    }

    public function test_customer_create_and_update_write_audit_entries(): void
    {
        $this->actingAs($this->owner);

        $customer = app(CreateCustomerAction::class)->handle([
            'company_name' => 'Audit Co',
            'display_name' => 'Audit Co',
            'email' => 'audit@example.com',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'created',
            'model_type' => Customer::class,
            'model_id' => $customer->id,
            'user_id' => $this->owner->id,
        ]);

        app(UpdateCustomerAction::class)->handle($customer, [
            'company_name' => 'Audit Co Updated',
            'display_name' => 'Audit Co Updated',
            'email' => 'audit@example.com',
            'is_active' => true,
        ]);

        $this->assertTrue(
            AuditLog::query()
                ->where('action', 'updated')
                ->where('model_type', Customer::class)
                ->where('model_id', $customer->id)
                ->exists()
        );
    }

    public function test_audit_log_filters_and_detail_selection(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['display_name' => 'Filter Me']);
        $log = AuditLog::query()->create([
            'user_id' => $this->owner->id,
            'action' => 'created',
            'model_type' => Customer::class,
            'model_id' => $customer->id,
            'old_values' => null,
            'new_values' => ['display_name' => 'Filter Me'],
        ]);

        Livewire::test(AuditLogIndex::class)
            ->set('search', 'Filter Me')
            ->assertSee('created')
            ->assertSee('Customer')
            ->call('selectEntry', $log->id)
            ->assertSet('selectedId', $log->id)
            ->assertSee('Filter Me');
    }
}
