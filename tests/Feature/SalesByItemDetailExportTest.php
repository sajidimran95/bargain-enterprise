<?php

namespace Tests\Feature;

use App\Livewire\Reports\SalesByItemReport;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Item;
use App\Models\ItemType;
use App\Models\UnitOfMeasure;
use App\Models\User;
use App\Support\QbSalesReportExport;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SalesByItemDetailExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_qb_sales_layout_downloads_matching_xlsm(): void
    {
        $this->seed(RolePermissionSeeder::class);

        $owner = User::factory()->create(['name' => 'bashar']);
        $owner->assignRole('owner');
        $this->actingAs($owner);

        $type = ItemType::factory()->create([
            'name' => 'inventory',
            'label' => 'Inventory',
        ]);
        $uom = UnitOfMeasure::factory()->create([
            'name' => 'Each',
            'abbreviation' => 'ea',
        ]);
        $customer = Customer::factory()->create([
            'display_name' => '4 EVER',
            'bill_to_street1' => '123 Main',
            'bill_to_city' => 'Nashville',
            'bill_to_state' => 'TN',
            'bill_to_zip' => '37201',
            'is_active' => true,
        ]);
        $item = Item::factory()->create([
            'sku' => 'SODA-COKE',
            'barcode' => 'SODA-COKE',
            'name' => 'Coke 24ct',
            'sales_description' => 'Coke 24ct',
            'item_type_id' => $type->id,
            'unit_of_measure_id' => $uom->id,
            'average_cost' => 4,
            'is_active' => true,
        ]);

        $invoice = Invoice::query()->create([
            'invoice_number' => 'INV-DETAIL-1',
            'customer_id' => $customer->id,
            'invoice_date' => now()->toDateString(),
            'status' => 'open',
            'memo' => 'Weekly drop',
            'subtotal' => 20,
            'tax_total' => 0,
            'total' => 20,
            'amount_paid' => 0,
            'balance_due' => 20,
            'created_by' => $owner->id,
        ]);

        InvoiceLine::query()->create([
            'invoice_id' => $invoice->id,
            'item_id' => $item->id,
            'description' => 'Coke 24ct',
            'quantity' => 2,
            'rate' => 10,
            'amount' => 20,
            'taxable' => true,
            'tax_amount' => 0,
            'line_order' => 0,
        ]);

        foreach (array_keys(QbSalesReportExport::layouts()) as $layout) {
            Livewire::test(SalesByItemReport::class)
                ->set('datePreset', 'this_month')
                ->set('layout', $layout)
                ->assertSee(QbSalesReportExport::headersFor($layout)[1] ?: 'Qty', false)
                ->call('exportExcel')
                ->assertFileDownloaded(QbSalesReportExport::filename($layout));
        }

        $this->assertSame(
            ['Type', 'Date', 'Num', 'Name Address', 'Name Street1', 'Name City', 'Name State', 'Name Zip', 'Name Fax #', 'Memo', 'Name', 'Item', 'Qty', 'U/M', 'Sales Price', 'Amount', 'Balance'],
            array_values(array_filter(QbSalesReportExport::headersFor('customer_detail')))
        );
        $this->assertSame(
            ['Type', 'Date', 'Num', 'Memo', 'Name', 'Qty', 'U/M', 'Sales Price', 'Amount', 'Balance'],
            array_values(array_filter(QbSalesReportExport::headersFor('item_detail')))
        );
        $this->assertSame(
            ['Type', 'Date', 'Num', 'Ship To Address 1', 'Ship To Address 2', 'Ship Zip', 'Name Address', 'Name Street1', 'Name City', 'Name State', 'Name Zip', 'Name Fax #', 'Item', 'Account', 'Qty', 'Sales Price', 'Amount'],
            array_values(array_filter(QbSalesReportExport::headersFor('ship_to_detail')))
        );
        $this->assertSame(
            ['Qty', 'Amount', '% of Sales', 'Avg Price', 'COGS', 'Avg COGS', 'Gross Margin', 'Gross Margin %'],
            array_values(array_filter(QbSalesReportExport::headersFor('item_summary')))
        );
    }
}
