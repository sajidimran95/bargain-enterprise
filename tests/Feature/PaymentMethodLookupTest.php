<?php

namespace Tests\Feature;

use App\Livewire\Lookups\LookupManager;
use App\Livewire\Sales\InvoiceForm;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Support\PaymentMethods;
use Database\Seeders\LookupTablesSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentMethodLookupTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_methods_are_managed_in_lookups_and_used_on_invoice(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $this->seed(LookupTablesSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('manager');
        $this->actingAs($user);

        $this->assertArrayHasKey('cash', PaymentMethods::options());
        $this->assertSame('Cash', PaymentMethods::options()['cash']);

        Livewire::test(LookupManager::class)
            ->call('setType', 'payment_methods')
            ->set('form.code', 'venmo')
            ->set('form.name', 'Venmo')
            ->set('form.sort_order', 15)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('payment_methods', [
            'code' => 'venmo',
            'name' => 'Venmo',
            'is_active' => true,
        ]);

        Livewire::test(InvoiceForm::class)
            ->set('receive_payment_now', true)
            ->assertSee('Venmo')
            ->assertSee('Cash')
            ->assertSee('Check');

        $method = PaymentMethod::query()->where('code', 'venmo')->firstOrFail();
        Livewire::test(LookupManager::class)
            ->call('setType', 'payment_methods')
            ->call('toggleActive', $method->id);

        $this->assertFalse(array_key_exists('venmo', PaymentMethods::options()));
    }
}
