<?php

namespace Tests\Feature;

use App\Livewire\Search\GlobalSearch;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
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

    public function test_global_search_finds_customers_items_and_invoices(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create([
            'display_name' => 'Alpha Search Mart',
            'customer_number' => '5501',
            'is_active' => true,
        ]);
        Item::factory()->create([
            'sku' => 'SRCH-SKU-9',
            'name' => 'Search Widget',
            'is_active' => true,
        ]);
        Invoice::query()->create([
            'customer_id' => $customer->id,
            'invoice_number' => 'INV-SRCH-77',
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(10)->toDateString(),
            'status' => 'open',
            'subtotal' => 50,
            'tax_total' => 0,
            'total' => 50,
            'amount_paid' => 0,
            'balance_due' => 50,
            'created_by' => $this->owner->id,
        ]);

        Livewire::test(GlobalSearch::class)
            ->set('query', 'Alpha')
            ->assertSet('open', true)
            ->assertSee('Alpha Search Mart')
            ->set('query', 'SRCH-SKU')
            ->assertSee('SRCH-SKU-9')
            ->set('query', 'INV-SRCH')
            ->assertSee('INV-SRCH-77')
            ->set('query', 'Customer Center')
            ->assertSee('Customer Center');
    }

    public function test_global_search_ignores_short_queries(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(GlobalSearch::class)
            ->set('query', 'a')
            ->assertSet('open', false)
            ->assertSet('groups', []);
    }
}
