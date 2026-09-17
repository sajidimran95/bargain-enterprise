<?php

namespace Tests\Feature;

use App\Livewire\Sales\InvoiceForm;
use App\Models\Item;
use App\Models\User;
use App\Support\ItemCatalog;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ItemCatalogLineItemsTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);

        $this->owner = User::factory()->create([
            'email' => 'owner-items@bargain.local',
        ]);
        $this->owner->assignRole('owner');
    }

    public function test_item_catalog_options_are_alphabetical_by_code(): void
    {
        Item::factory()->create(['is_active' => true, 'sku' => 'ZETA-9', 'barcode' => null, 'name' => 'Zeta']);
        Item::factory()->create(['is_active' => true, 'sku' => 'ALPHA-1', 'barcode' => 'BETA-2', 'name' => 'Beta']);
        Item::factory()->create(['is_active' => true, 'sku' => 'ALPHA-1B', 'barcode' => null, 'name' => 'Alpha']);

        $codes = array_map(
            fn (string $label) => explode(' — ', $label, 2)[0],
            array_values(ItemCatalog::selectOptions())
        );

        $this->assertSame(['ALPHA-1B', 'BETA-2', 'ZETA-9'], $codes);
    }

    public function test_item_search_returns_at_most_twenty_five_matches(): void
    {
        foreach (range(1, 30) as $n) {
            Item::factory()->create([
                'is_active' => true,
                'sku' => sprintf('WIDGET-%02d', $n),
                'barcode' => null,
                'name' => 'Widget '.$n,
            ]);
        }

        $results = ItemCatalog::search('WIDGET', 25);

        $this->assertCount(25, $results);
        $this->assertSame('WIDGET-01', $results[0]['code']);
        $this->assertSame('WIDGET-25', $results[24]['code']);
    }

    public function test_partial_scan_shows_search_results_under_bar(): void
    {
        $this->actingAs($this->owner);

        Item::factory()->create(['is_active' => true, 'sku' => 'BOLT-100', 'barcode' => null, 'name' => 'Bolt 100']);
        Item::factory()->create(['is_active' => true, 'sku' => 'BOLT-200', 'barcode' => null, 'name' => 'Bolt 200']);
        Item::factory()->create(['is_active' => true, 'sku' => 'NUT-10', 'barcode' => null, 'name' => 'Nut']);

        Livewire::test(InvoiceForm::class)
            ->set('scanCode', 'BOLT')
            ->assertCount('itemSearchResults', 2)
            ->assertSet('itemSearchResults.0.code', 'BOLT-100')
            ->assertSet('lines.0.item_id', '');
    }

    public function test_selecting_search_result_adds_line(): void
    {
        $this->actingAs($this->owner);

        $item = Item::factory()->create([
            'is_active' => true,
            'sku' => 'PICK-9',
            'barcode' => null,
            'sales_price' => 4.00,
        ]);

        Livewire::test(InvoiceForm::class)
            ->set('scanCode', 'PICK')
            ->call('selectItemSearchResult', $item->id)
            ->assertSet('lines.0.item_id', (string) $item->id)
            ->assertSet('scanCode', '')
            ->assertCount('itemSearchResults', 0);
    }

    public function test_exact_scan_code_auto_adds_line_without_enter(): void
    {
        $this->actingAs($this->owner);

        $item = Item::factory()->create([
            'is_active' => true,
            'sku' => 'AUTO-SKU',
            'barcode' => '998877665544',
            'sales_price' => 12.25,
        ]);

        Livewire::test(InvoiceForm::class)
            ->set('scanCode', '998877665544')
            ->assertSet('lines.0.item_id', (string) $item->id)
            ->assertSet('lines.0.item_code', '998877665544')
            ->assertSet('scanCode', '')
            ->assertCount('itemSearchResults', 0);
    }

    public function test_typing_item_code_fills_invoice_line(): void
    {
        $this->actingAs($this->owner);

        $item = Item::factory()->create([
            'is_active' => true,
            'sku' => 'TYPE-100',
            'barcode' => null,
            'sales_price' => 3.50,
            'sales_description' => 'Typed widget',
        ]);

        Livewire::test(InvoiceForm::class)
            ->set('lines.0.item_code', 'TYPE-100')
            ->assertSet('lines.0.item_id', (string) $item->id)
            ->assertSet('lines.0.description', 'Typed widget');
    }
}
