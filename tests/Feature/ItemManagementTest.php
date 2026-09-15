<?php

namespace Tests\Feature;

use App\Actions\Items\CreateItemAction;
use App\Livewire\Items\ItemForm;
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

    public function test_edit_item_matches_quickbooks_layout(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        $item = Item::factory()->create([
            'sku' => '0819721012052',
            'sales_description' => 'Bluntville Cigarillos Triple Vanilla 25ct',
            'sales_price' => 29.50,
            'type' => 'inventory_part',
            'is_active' => true,
        ]);

        $this->actingAs($user)
            ->get(route('items.edit', $item))
            ->assertOk()
            ->assertSee('PURCHASE INFORMATION')
            ->assertSee('SALES INFORMATION')
            ->assertSee('INVENTORY INFORMATION')
            ->assertSee('Custom Fields')
            ->assertSee('Item is inactive')
            ->assertSee('On Hand')
            ->assertSee('Bluntville Cigarillos Triple Vanilla 25ct');

        Livewire::actingAs($user)
            ->test(ItemForm::class, ['item' => $item])
            ->call('openCustomFields')
            ->assertSet('showCustomFields', true)
            ->assertSee('Category Code')
            ->assertSee('Promotion')
            ->set('itemInactive', true)
            ->assertSet('is_active', false);
    }

    public function test_item_right_actions_notes_and_spelling_are_dynamic(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');
        $item = Item::factory()->create([
            'sku' => 'SPELL-001',
            'sales_description' => 'Vanilla  Vanilla Cigarillos',
            'is_active' => true,
        ]);

        Livewire::actingAs($user)
            ->test(ItemForm::class, ['item' => $item])
            ->call('openNotes')
            ->assertSet('showNotes', true)
            ->set('noteBody', 'Check warehouse shelf B2')
            ->call('saveNote')
            ->assertSee('Check warehouse shelf B2')
            ->call('checkSpelling')
            ->assertSet('showSpelling', true)
            ->assertSee('Extra spaces or repeated words')
            ->call('applySpellingFixes')
            ->assertSet('sales_description', 'Vanilla Cigarillos');

        $this->assertDatabaseHas('item_notes', [
            'item_id' => $item->id,
            'body' => 'Check warehouse shelf B2',
        ]);
    }

    public function test_barcode_accepts_scan_or_type_and_blocks_duplicates(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        Item::factory()->create([
            'sku' => 'OTHER-SKU',
            'barcode' => '012345678905',
        ]);

        $item = Item::factory()->create([
            'sku' => 'ITEM-SKU',
            'barcode' => 'ITEM-SKU',
        ]);

        Livewire::actingAs($user)
            ->test(ItemForm::class, ['item' => $item])
            ->set('barcode', ' 998877665544 ')
            ->call('acceptBarcode')
            ->assertSet('barcode', '998877665544')
            ->assertHasNoErrors('barcode')
            ->set('barcode', '012345678905')
            ->call('acceptBarcode')
            ->assertHasErrors('barcode')
            ->call('useSkuAsBarcode')
            ->assertSet('barcode', 'ITEM-SKU')
            ->assertHasNoErrors('barcode');
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
