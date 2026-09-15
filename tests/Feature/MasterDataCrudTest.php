<?php

namespace Tests\Feature;

use App\Actions\Customers\DuplicateCustomerAction;
use App\Actions\Items\DuplicateItemAction;
use App\Actions\Lookups\DeleteLookupAction;
use App\Livewire\Customers\CustomerCenter;
use App\Livewire\Items\ItemList;
use App\Livewire\Lookups\LookupManager;
use App\Livewire\Settings\CompanySettings;
use App\Livewire\Vendors\VendorCenter;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vendor;
use App\Support\CsvExporter;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use RuntimeException;
use Tests\TestCase;

class MasterDataCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_customer_contact_can_be_created_updated_and_deleted(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        $customer = Customer::factory()->create();

        Livewire::actingAs($user)
            ->test(CustomerCenter::class, ['customer' => $customer])
            ->set('contactName', 'Sam Contact')
            ->set('contactPhone', '203-555-1111')
            ->call('addContact')
            ->assertHasNoErrors();

        $contact = CustomerContact::query()->where('customer_id', $customer->id)->firstOrFail();

        Livewire::actingAs($user)
            ->test(CustomerCenter::class, ['customer' => $customer])
            ->call('editContact', $contact->id)
            ->set('contactName', 'Sam Updated')
            ->call('addContact')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('customer_contacts', [
            'id' => $contact->id,
            'name' => 'Sam Updated',
        ]);

        Livewire::actingAs($user)
            ->test(CustomerCenter::class, ['customer' => $customer])
            ->call('deleteContact', $contact->id);

        $this->assertDatabaseMissing('customer_contacts', ['id' => $contact->id]);
    }

    public function test_customer_can_be_duplicated_and_reactivated(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        $customer = Customer::factory()->create(['is_active' => false, 'display_name' => 'Alpha']);

        $copy = app(DuplicateCustomerAction::class)->handle($customer);
        $this->assertStringContainsString('(Copy)', $copy->display_name);
        $this->assertTrue($copy->is_active);

        Livewire::actingAs($user)
            ->test(CustomerCenter::class, ['customer' => $customer])
            ->call('activate');

        $this->assertTrue($customer->fresh()->is_active);
    }

    public function test_vendor_center_supports_contact_crud(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        $vendor = Vendor::factory()->create();

        Livewire::actingAs($user)
            ->test(VendorCenter::class, ['vendor' => $vendor])
            ->set('contactName', 'Vendor Rep')
            ->call('addContact')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('vendor_contacts', [
            'vendor_id' => $vendor->id,
            'name' => 'Vendor Rep',
        ]);
    }

    public function test_item_can_be_duplicated_activated_and_exported(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        $item = Item::factory()->create(['sku' => 'SKU-100', 'is_active' => false]);

        $copy = app(DuplicateItemAction::class)->handle($item);
        $this->assertNotSame($item->sku, $copy->sku);
        $this->assertSame(0.0, (float) $copy->on_hand);

        Livewire::actingAs($user)
            ->test(ItemList::class)
            ->call('selectItem', $item->id)
            ->call('activateSelected')
            ->assertHasNoErrors();

        $this->assertTrue($item->fresh()->is_active);

        $response = app(CsvExporter::class)->download('items.csv', ['SKU'], [['SKU-100']]);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('text/csv', (string) $response->headers->get('content-type'));
    }

    public function test_lookup_can_be_created_edited_and_deleted(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        Livewire::actingAs($user)
            ->test(LookupManager::class)
            ->call('setType', 'categories')
            ->set('form.code', '7777')
            ->set('form.name', 'Candy')
            ->call('save')
            ->assertHasNoErrors();

        $category = ItemCategory::query()->where('code', '7777')->firstOrFail();

        Livewire::actingAs($user)
            ->test(LookupManager::class)
            ->call('setType', 'categories')
            ->call('edit', $category->id)
            ->set('form.name', 'Candy Updated')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('item_categories', [
            'id' => $category->id,
            'name' => 'Candy Updated',
        ]);

        Livewire::actingAs($user)
            ->test(LookupManager::class)
            ->call('setType', 'categories')
            ->call('delete', $category->id);

        $this->assertDatabaseMissing('item_categories', ['id' => $category->id]);
    }

    public function test_lookup_in_use_cannot_be_deleted(): void
    {
        $category = ItemCategory::factory()->create();
        Item::factory()->create(['item_category_id' => $category->id]);

        $this->expectException(RuntimeException::class);
        app(DeleteLookupAction::class)->handle('categories', $category->id);
    }

    public function test_settings_can_be_saved(): void
    {
        $user = User::factory()->create();
        $user->assignRole('owner');

        Livewire::actingAs($user)
            ->test(CompanySettings::class)
            ->set('company_name', 'Bargain Enterprise LLC')
            ->set('negative_policy', 'BLOCK')
            ->set('allow_manager_override', false)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Bargain Enterprise LLC', Setting::getValue('company.name'));
        $this->assertSame('BLOCK', Setting::getValue('inventory.negative_policy'));
        $this->assertFalse(Setting::getValue('inventory.allow_manager_override'));
    }
}
