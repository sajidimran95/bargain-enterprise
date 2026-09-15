<?php

namespace Tests\Feature;

use App\Actions\Items\CreateItemAction;
use App\Livewire\Items\ItemList;
use App\Livewire\Lookups\LookupManager;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ItemManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_manager_can_view_item_list(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        Item::factory()->create([
            'sku' => '0819721012052',
            'sales_description' => 'Bluntville Cigarillos Triple Vanilla 25ct',
        ]);

        $this->actingAs($user)
            ->get(route('items.index'))
            ->assertOk()
            ->assertSee('0819721012052')
            ->assertSee('Bluntville Cigarillos Triple Vanilla 25ct');
    }

    public function test_create_item_action_persists_tobacco_fields(): void
    {
        $category = ItemCategory::factory()->create(['code' => '3251']);

        $item = app(CreateItemAction::class)->handle([
            'sku' => '9990001112223',
            'name' => 'Test Tobacco Pack',
            'sales_description' => 'Test Tobacco Pack',
            'sales_price' => 12.50,
            'item_category_id' => $category->id,
            'items_per_container' => 20,
            'promotion' => '2 For $1.39',
        ]);

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'sku' => '9990001112223',
            'item_category_id' => $category->id,
            'promotion' => '2 For $1.39',
        ]);
    }

    public function test_item_list_hides_inactive_by_default(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        Item::factory()->create(['sku' => 'ACTIVE001', 'name' => 'Active Item']);
        Item::factory()->inactive()->create(['sku' => 'INACTIVE001', 'name' => 'Inactive Item']);

        Livewire::actingAs($user)
            ->test(ItemList::class)
            ->assertSee('ACTIVE001')
            ->assertDontSee('INACTIVE001')
            ->set('includeInactive', true)
            ->assertSee('INACTIVE001');
    }

    public function test_lookup_manager_can_create_category(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        Livewire::actingAs($user)
            ->test(LookupManager::class)
            ->call('setType', 'categories')
            ->set('form.code', '5555')
            ->set('form.name', 'Energy Drinks')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('item_categories', [
            'code' => '5555',
            'name' => 'Energy Drinks',
        ]);
    }
}
