<?php

namespace Tests\Feature;

use App\Livewire\Lookups\PaymentMethodList;
use App\Livewire\Purchasing\VendorBillForm;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Support\PaymentMethods;
use Database\Seeders\LookupTablesSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PaymentMethodListTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->seed(LookupTablesSeeder::class);
        $this->owner = User::factory()->create();
        $this->owner->assignRole('owner');
    }

    public function test_payment_method_list_creates_method_used_on_enter_bills(): void
    {
        $this->actingAs($this->owner);

        $this->get(route('payment-methods.index'))
            ->assertOk()
            ->assertSee('Payment Method List');

        Livewire::test(PaymentMethodList::class)
            ->set('code', 'zelle')
            ->set('name', 'Zelle')
            ->set('sort_order', '25')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('payment_methods', [
            'code' => 'zelle',
            'name' => 'Zelle',
            'is_active' => true,
        ]);
        $this->assertSame('Zelle', PaymentMethods::options()['zelle']);

        Livewire::test(VendorBillForm::class)
            ->set('pay_bill_now', true)
            ->assertSee('Zelle')
            ->assertSee('Cash');
    }

    public function test_deactivated_payment_method_is_hidden_from_dropdowns(): void
    {
        $this->actingAs($this->owner);

        $method = PaymentMethod::query()->where('code', 'cash')->firstOrFail();

        Livewire::test(PaymentMethodList::class)
            ->call('toggleActive', $method->id);

        $this->assertFalse(array_key_exists('cash', PaymentMethods::options()));
        $this->assertSame('Cash', PaymentMethods::labelFor('cash'));
    }
}
