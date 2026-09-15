<?php

namespace Tests\Feature;

use App\Livewire\Reports\InventoryStockReport;
use App\Livewire\Workspace\MyShortcuts;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\User;
use App\Support\ErpShortcuts;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class MyShortcutsTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->owner = User::factory()->create();
        $this->owner->assignRole('owner');
    }

    public function test_dashboard_shows_qb_style_my_shortcuts_footer(): void
    {
        $this->actingAs($this->owner);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('My Shortcuts')
            ->assertSee('View Balances')
            ->assertSee('Run Favorite Reports')
            ->assertSee('Open Windows')
            ->assertSee('Customize shortcuts', false)
            ->assertSee('Customers')
            ->assertSee('MSA Inventory');
    }

    public function test_user_can_add_and_remove_shortcut(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(MyShortcuts::class)
            ->call('removeShortcut', 'msa_inventory')
            ->assertDontSee('MSA Inventory')
            ->call('openAdd')
            ->assertSet('showAdd', true)
            ->call('addShortcut', 'msa_inventory')
            ->assertSee('MSA Inventory')
            ->call('closeAdd')
            ->assertSet('showAdd', false);

        $keys = ErpShortcuts::pinnedKeys($this->owner);
        $this->assertContains('msa_inventory', $keys);
    }

    public function test_msa_inventory_stock_table_matches_qb_columns(): void
    {
        $this->actingAs($this->owner);

        $type = ItemType::factory()->create(['label' => 'Tobacco']);
        Item::factory()->create([
            'sku' => 'SKU-INV',
            'barcode' => '083519600675',
            'sales_description' => "Dark Horse 100's, 200 Cigars (Menthol)",
            'type' => 'inventory_part',
            'item_type_id' => $type->id,
            'on_hand' => 60,
            'sales_price' => 7.95,
            'purchase_cost' => 0,
            'is_active' => true,
        ]);

        $this->get(route('reports.inventory'))
            ->assertOk()
            ->assertSee('MSA Inventory')
            ->assertSee('NAME')
            ->assertSee('DESCRIPTION')
            ->assertSee('TYPE')
            ->assertSee('ITEM TYPE')
            ->assertSee('TOTAL QUANTITY ON HAND')
            ->assertSee('PRICE')
            ->assertSee('COST')
            ->assertSee('083519600675')
            ->assertSee("Dark Horse 100's, 200 Cigars (Menthol)")
            ->assertSee('Inventory Part')
            ->assertSee('Tobacco')
            ->assertSee('Look for');

        Livewire::test(InventoryStockReport::class)
            ->set('search', '083519600675')
            ->call('runSearch')
            ->assertSee("Dark Horse 100's, 200 Cigars (Menthol)")
            ->assertDontSee('No inventory items.');
    }
}
