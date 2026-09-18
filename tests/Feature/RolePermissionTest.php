<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_roles_and_permissions_are_seeded(): void
    {
        $this->assertDatabaseHas('roles', ['name' => 'owner']);
        $this->assertDatabaseHas('roles', ['name' => 'admin']);
        $this->assertDatabaseHas('roles', ['name' => 'manager']);
        $this->assertDatabaseHas('roles', ['name' => 'sales']);
        $this->assertDatabaseHas('roles', ['name' => 'sales_representative']);
        $this->assertDatabaseHas('roles', ['name' => 'bookkeeper']);
        $this->assertTrue(Permission::query()->where('name', 'invoice.create')->exists());
        $this->assertTrue(Permission::query()->where('name', 'users.manage')->exists());
    }

    public function test_owner_has_all_permissions_via_gate(): void
    {
        $user = User::factory()->create();
        $user->assignRole('owner');

        $this->assertTrue($user->hasPermission('invoice.delete'));
        $this->assertTrue($user->hasPermission('settings.manage'));
        $this->assertTrue($user->hasPermission('users.manage'));
        $this->assertTrue(Gate::forUser($user)->allows('settings.manage'));
    }

    public function test_admin_has_all_permissions_like_owner(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->assertTrue($user->hasPermission('roles.manage'));
        $this->assertTrue(Gate::forUser($user)->allows('users.manage'));
    }

    public function test_sales_role_cannot_manage_settings(): void
    {
        $user = User::factory()->create();
        $user->assignRole('sales');
        $user->load('roles.permissions');

        $this->assertTrue($user->hasPermission('invoice.create'));
        $this->assertFalse($user->hasPermission('settings.manage'));
        $this->assertFalse($user->hasPermission('accounting.manage'));
    }

    public function test_bookkeeper_can_manage_accounting_but_not_create_invoices(): void
    {
        $user = User::factory()->create();
        $user->assignRole('bookkeeper');
        $user->load('roles.permissions');

        $this->assertTrue($user->hasPermission('accounting.manage'));
        $this->assertFalse($user->hasPermission('invoice.create'));
    }

    public function test_authenticated_user_can_view_dashboard_shell(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Vendors', false);
        $response->assertSee('Customers', false);
        $response->assertSee('My Shortcuts', false);
    }
}
