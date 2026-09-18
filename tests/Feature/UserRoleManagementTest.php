<?php

namespace Tests\Feature;

use App\Livewire\Roles\RoleForm;
use App\Livewire\Roles\RoleIndex;
use App\Livewire\Users\UserForm;
use App\Livewire\Users\UserIndex;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserRoleManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
    }

    public function test_admin_and_sales_representative_roles_are_seeded_with_menu_permissions(): void
    {
        $this->assertDatabaseHas('roles', ['name' => 'admin']);
        $this->assertDatabaseHas('roles', ['name' => 'sales_representative']);
        $this->assertTrue(Permission::query()->where('name', 'users.manage')->where('menu', 'Company')->exists());
        $this->assertTrue(Permission::query()->where('name', 'roles.manage')->exists());
        $this->assertTrue(Permission::query()->whereNotNull('submenu')->exists());

        $admin = Role::query()->where('name', 'admin')->firstOrFail();
        $this->assertSame(Permission::query()->count(), $admin->permissions()->count());

        $salesRep = Role::query()->where('name', 'sales_representative')->with('permissions')->firstOrFail();
        $this->assertTrue($salesRep->hasPermission('invoice.create'));
        $this->assertFalse($salesRep->hasPermission('roles.manage'));
    }

    public function test_admin_can_create_user_with_sales_representative_role(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(UserForm::class)
            ->set('name', 'Rep User')
            ->set('email', 'rep@bargain.local')
            ->set('password', 'Password1!')
            ->set('password_confirmation', 'Password1!')
            ->set('role', 'sales_representative')
            ->call('save')
            ->assertRedirect(route('users.index'));

        $user = User::query()->where('email', 'rep@bargain.local')->firstOrFail();
        $this->assertTrue($user->hasRole('sales_representative'));
        $this->assertTrue($user->hasPermission('invoice.create'));
        $this->assertFalse($user->hasPermission('settings.manage'));
    }

    public function test_role_form_toggles_menu_submenu_permissions(): void
    {
        $this->actingAs($this->admin);

        $role = Role::query()->where('name', 'sales_representative')->firstOrFail();

        Livewire::test(RoleForm::class, ['role' => $role])
            ->assertSet('editingId', $role->id)
            ->call('toggleMenu', 'Vendors')
            ->call('save')
            ->assertRedirect(route('roles.index'));

        $role->refresh()->load('permissions');
        $this->assertTrue($role->hasPermission('vendor.view'));
        $this->assertTrue($role->hasPermission('purchase.create'));
    }

    public function test_user_and_role_lists_are_reachable_for_admin(): void
    {
        $this->actingAs($this->admin);

        $this->get(route('users.index'))->assertOk()->assertSee('User List');
        $this->get(route('roles.index'))->assertOk()->assertSee('Role List');
        $this->get(route('roles.create'))->assertOk()->assertSee('Create Role');

        Livewire::test(UserIndex::class)->assertSee('New User');
        Livewire::test(RoleIndex::class)->assertSee('New Role');
    }

    public function test_sales_representative_cannot_open_user_management(): void
    {
        $rep = User::factory()->create();
        $rep->assignRole('sales_representative');

        $this->actingAs($rep)
            ->get(route('users.index'))
            ->assertForbidden();
    }
}
