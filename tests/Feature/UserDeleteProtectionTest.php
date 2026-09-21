<?php

namespace Tests\Feature;

use App\Livewire\Users\UserForm;
use App\Livewire\Users\UserIndex;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserDeleteProtectionTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $this->admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
        ]);
        $this->admin->assignRole('admin');
    }

    public function test_primary_admin_cannot_be_deleted_from_list(): void
    {
        $this->actingAs($this->admin);

        $other = User::factory()->create(['email' => 'staff@example.com']);
        $other->assignRole('sales');

        Livewire::test(UserIndex::class)
            ->call('deleteUser', $this->admin->id)
            ->assertDispatched('be-toast');

        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);

        Livewire::test(UserIndex::class)
            ->call('deleteUser', $other->id)
            ->assertDispatched('be-toast');

        $this->assertDatabaseMissing('users', ['id' => $other->id]);
    }

    public function test_user_cannot_delete_own_account(): void
    {
        $this->actingAs($this->admin);

        $manager = User::factory()->create(['email' => 'manager@example.com']);
        $manager->assignRole('manager');

        $this->actingAs($manager);

        Livewire::test(UserIndex::class)
            ->call('deleteUser', $manager->id)
            ->assertDispatched('be-toast');

        $this->assertDatabaseHas('users', ['id' => $manager->id]);
    }

    public function test_primary_admin_cannot_be_deleted_from_edit_form(): void
    {
        $this->actingAs($this->admin);

        Livewire::test(UserForm::class, ['user' => $this->admin])
            ->call('deleteUser')
            ->assertDispatched('be-toast')
            ->assertSet('editingId', $this->admin->id);

        $this->assertDatabaseHas('users', ['id' => $this->admin->id, 'email' => 'admin@gmail.com']);
    }
}
