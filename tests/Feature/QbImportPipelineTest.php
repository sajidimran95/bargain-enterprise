<?php

namespace Tests\Feature;

use App\Livewire\Import\QbImportWizard;
use App\Livewire\Workspace\Shell;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\User;
use App\Services\QbImportService;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;
use Tests\TestCase;

class QbImportPipelineTest extends TestCase
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

    public function test_import_wizard_page_loads_for_owner(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(QbImportWizard::class)
            ->assertSee('Import from QuickBooks')
            ->assertSee('Raw → Normalize → Validate → Transform → Production');
    }

    public function test_items_csv_runs_full_pipeline_into_production(): void
    {
        $this->actingAs($this->owner);

        $csv = "sku,name,sales_price,purchase_cost,on_hand,category,is_active\n".
            "IMP-1,Imported Item,9.99,4.50,12,Import Cat,1\n";

        $file = UploadedFile::fake()->createWithContent('items.csv', $csv);

        $service = app(QbImportService::class);
        $batch = $service->ingestCsv($file, 'items', $this->owner->id);
        $this->assertSame('raw', $batch->status);
        $this->assertSame(1, $batch->row_count);

        $batch = $service->runPipeline($batch);

        $this->assertSame('production', $batch->status);
        $this->assertSame(1, $batch->success_count);
        $this->assertDatabaseHas('item_categories', ['name' => 'Import Cat']);
        $this->assertDatabaseHas('items', [
            'sku' => 'IMP-1',
            'name' => 'Imported Item',
        ]);

        $item = Item::query()->where('sku', 'IMP-1')->first();
        $this->assertNotNull($item);
        $this->assertSame('9.99', number_format((float) $item->sales_price, 2, '.', ''));
        $this->assertTrue(ItemCategory::query()->whereKey($item->item_category_id)->exists());
    }

    public function test_validation_marks_incomplete_rows_failed_without_production_write(): void
    {
        $this->actingAs($this->owner);

        $csv = "sku,name,sales_price\n,Missing Sku Item,1.00\n";
        $file = UploadedFile::fake()->createWithContent('bad.csv', $csv);

        $service = app(QbImportService::class);
        $batch = $service->ingestCsv($file, 'items', $this->owner->id);
        $batch = $service->normalize($batch);
        $batch = $service->validate($batch);

        $this->assertSame(1, $batch->error_count);
        $this->assertSame('failed', $batch->rows()->first()->stage);
        $this->assertDatabaseCount('items', 0);
    }

    public function test_workspace_shell_shows_close_all_control(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(Shell::class)
            ->assertSee('Close All');

        $component = Livewire::test(Shell::class);
        $component->call('openRoute', 'vendors.index');
        $component->call('openRoute', 'items.index');
        $component->call('closeAllTabs');
        $this->assertSame('dashboard', $component->get('activeTabId'));
        $this->assertCount(1, $component->get('tabs'));
    }

    public function test_menubar_config_matches_quickbooks_order(): void
    {
        $menus = array_keys(config('erp_menubar'));

        $this->assertSame([
            'File',
            'Edit',
            'View',
            'Lists',
            'Favorites',
            'Mfg & Whsle',
            'Company',
            'Customers',
            'Vendors',
            'Employees',
            'Banking',
            'Reports',
            'Window',
            'Help',
        ], $menus);

        $window = collect(config('erp_menubar.Window'));
        $this->assertTrue($window->contains(fn ($item) => ($item['action'] ?? null) === 'close-all'));
    }
}
