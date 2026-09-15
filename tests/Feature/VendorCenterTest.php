<?php

namespace Tests\Feature;

use App\Actions\Vendors\CreateVendorAction;
use App\Livewire\Vendors\VendorCenter;
use App\Models\User;
use App\Models\Vendor;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class VendorCenterTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_manager_can_view_vendor_center(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        Vendor::factory()->create(['display_name' => 'Demo Tobacco Supply']);

        $this->actingAs($user)
            ->get(route('vendors.index'))
            ->assertOk()
            ->assertSee('Demo Tobacco Supply');
    }

    public function test_create_vendor_action_works(): void
    {
        $vendor = app(CreateVendorAction::class)->handle([
            'company_name' => 'Acme Distributing',
            'display_name' => 'Acme Distributing',
        ]);

        $this->assertDatabaseHas('vendors', ['id' => $vendor->id, 'company_name' => 'Acme Distributing']);
    }

    public function test_vendor_center_selects_vendor(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        $vendor = Vendor::factory()->create(['display_name' => 'Beta Wholesale']);

        Livewire::actingAs($user)
            ->test(VendorCenter::class)
            ->call('selectVendor', $vendor->id)
            ->assertSet('selectedId', $vendor->id);
    }
}
