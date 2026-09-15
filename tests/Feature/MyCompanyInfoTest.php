<?php

namespace Tests\Feature;

use App\Livewire\Settings\MyCompany;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MyCompanyInfoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_my_company_and_preferences_are_separate_routes(): void
    {
        $user = User::factory()->create();
        $user->assignRole('owner');
        $this->actingAs($user);

        $this->get(route('company.info'))
            ->assertOk()
            ->assertSee('My Company')
            ->assertSee('Company Information')
            ->assertDontSee('Negative Stock Policy');

        $this->get(route('settings.index'))
            ->assertOk()
            ->assertSee('Preferences')
            ->assertSee('Negative Stock Policy')
            ->assertDontSee('Federal EIN');
    }

    public function test_my_company_shortcut_opens_company_info_not_preferences(): void
    {
        $this->assertSame('company.info', config('erp_shortcuts.catalog.company.route'));
        $this->assertSame('settings.index', config('erp_shortcuts.catalog.settings.route'));
        $this->assertNotSame(
            config('erp_shortcuts.catalog.company.route'),
            config('erp_shortcuts.catalog.settings.route')
        );
    }

    public function test_my_company_saves_identity_fields(): void
    {
        $user = User::factory()->create();
        $user->assignRole('owner');

        Livewire::actingAs($user)
            ->test(MyCompany::class)
            ->set('company_name', 'Bargain Wholesale LLC')
            ->set('legal_name', 'Bargain Wholesale Limited Liability Co')
            ->set('phone', '555-0100')
            ->set('city', 'Nashville')
            ->set('federal_ein', '12-3456789')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Bargain Wholesale LLC', Setting::getValue('company.name'));
        $this->assertSame('Bargain Wholesale Limited Liability Co', Setting::getValue('company.legal_name'));
        $this->assertSame('555-0100', Setting::getValue('company.phone'));
        $this->assertSame('Nashville', Setting::getValue('company.city'));
        $this->assertSame('12-3456789', Setting::getValue('company.federal_ein'));
    }
}
