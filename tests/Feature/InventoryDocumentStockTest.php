<?php

namespace Tests\Feature;

use App\Livewire\Items\ItemForm;
use App\Livewire\Sales\CreditMemoForm;
use App\Livewire\Sales\InvoiceForm;
use App\Livewire\Sales\SalesReceiptForm;
use App\Models\Customer;
use App\Models\Item;
use App\Models\User;
use Database\Seeders\ChartOfAccountsSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InventoryDocumentStockTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->seed(ChartOfAccountsSeeder::class);
        $this->owner = User::factory()->create();
        $this->owner->assignRole('owner');
    }

    public function test_item_edit_form_loads_when_nullable_strings_are_null(): void
    {
        $this->actingAs($this->owner);

        $item = Item::factory()->create([
            'barcode' => null,
            'manufacturer_part_number' => null,
            'purchase_description' => null,
            'sales_description' => null,
            'cogs_account' => null,
            'income_account' => null,
            'asset_account' => null,
            'promotion' => null,
        ]);

        $this->get(route('items.edit', $item))
            ->assertOk()
            ->assertSee($item->sku);

        Livewire::test(ItemForm::class, ['item' => $item])
            ->assertSet('manufacturer_part_number', '')
            ->assertSet('barcode', '')
            ->assertSet('promotion', '');
    }

    public function test_invoice_decreases_on_hand_stock(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 100,
            'average_cost' => 2,
            'sales_price' => 10,
        ]);

        Livewire::test(InvoiceForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.item_code', $item->sku)
            ->set('lines.0.quantity', '3')
            ->set('lines.0.rate', '10')
            ->set('lines.0.amount', '30.00')
            ->call('saveAndClose')
            ->assertRedirect(route('invoices.index'));

        $item->refresh();
        $this->assertSame('97.0000', number_format((float) $item->on_hand, 4, '.', ''));
    }

    public function test_sales_receipt_decreases_on_hand_stock(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 40,
            'average_cost' => 1,
            'sales_price' => 5,
        ]);

        Livewire::test(SalesReceiptForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('payment_method', 'cash')
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '4')
            ->set('lines.0.rate', '5')
            ->set('lines.0.amount', '20.00')
            ->call('save')
            ->assertRedirect(route('sales-receipts.index'));

        $item->refresh();
        $this->assertSame('36.0000', number_format((float) $item->on_hand, 4, '.', ''));
    }

    public function test_credit_memo_restores_on_hand_stock(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create([
            'is_active' => true,
            'balance' => 50,
        ]);
        $item = Item::factory()->create([
            'type' => 'inventory_part',
            'is_active' => true,
            'on_hand' => 10,
            'average_cost' => 1,
            'sales_price' => 8,
        ]);

        Livewire::test(CreditMemoForm::class)
            ->set('customer_id', (string) $customer->id)
            ->set('lines.0.item_id', (string) $item->id)
            ->set('lines.0.quantity', '2')
            ->set('lines.0.rate', '8')
            ->set('lines.0.amount', '16.00')
            ->call('save')
            ->assertRedirect(route('credit-memos.index'));

        $item->refresh();
        $customer->refresh();
        $this->assertSame('12.0000', number_format((float) $item->on_hand, 4, '.', ''));
        $this->assertSame('34.00', number_format((float) $customer->balance, 2, '.', ''));
    }
}
