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
            $headers = QbSalesReportExport::headersFor($layout);
            $visibleHeader = collect($headers)->first(fn (string $h) => $h !== '') ?? 'Qty';

            Livewire::test(SalesByItemReport::class)
                ->set('datePreset', 'this_month')
                ->set('layout', $layout)
                ->assertSee($visibleHeader)
                ->call('exportExcel')
                ->assertFileDownloaded(QbSalesReportExport::filename($layout));
        }

        $this->assertSame(
            config('qb_sales_reports.columns.customer_detail'),
            QbSalesReportExport::headersFor('customer_detail')
        );
        $this->assertSame(
            config('qb_sales_reports.columns.item_summary'),
            QbSalesReportExport::headersFor('item_summary')
        );
        $this->assertContains('Name Address', QbSalesReportExport::headersFor('customer_detail'));
        $this->assertContains('Name Address', QbSalesReportExport::headersFor('ship_to_detail'));
        $this->assertNotContains('Name Address', QbSalesReportExport::headersFor('item_summary'));
        $this->assertSame([
            '', 'Type', 'Date', 'Num', 'Name Address', 'Name Street1', 'Name Street2', 'Name City', 'Name State', 'Name Zip',
            'Name Tax ID', 'Name Account #', 'Memo', 'Name', 'Item', 'Item Description', 'Class', 'Qty', 'Sales Price',
            'Amount', 'Balance', 'Account Type', 'Paid Date', 'Ship To City', 'Ship To Address 1', 'Ship To Address 2',
            'Ship To State', 'Ship Zip',
        ], QbSalesReportExport::headersFor('customer_detail'));
    }

    public function test_customize_report_opens_the_customer_detail_dialog(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $owner = User::factory()->create();
        $owner->assignRole('owner');
        $this->actingAs($owner);

        Livewire::test(SalesByItemReport::class)
            ->set('layout', 'customer_detail')
            ->call('customizeReport')
            ->assertSee('Modify Report: Sales by Customer Detail')
            ->assertSee('Display')
            ->assertSee('Filters')
            ->assertSee('(left margin)')
            ->assertSee('Name Street2')
            ->assertSee('Accrual includes every invoice.')
            ->assertSee('Ascending order')
            ->assertSee('Revert')
            ->call('closeModifyReport')
            ->assertDontSee('Modify Report: Sales by Customer Detail');
    }

    public function test_customer_detail_excel_columns_follow_the_checked_list(): void
    {
        [$owner] = $this->customerDetailSale();
        $this->actingAs($owner);

        Livewire::test(SalesByItemReport::class)
            ->set('datePreset', 'this_month')
            ->set('layout', 'customer_detail')
            ->assertSee('Suite 4')
            ->assertSee('ACCT-44')
            ->call('customizeReport')
            ->set('draftVisibleColumns', ['0', '1', '2', '3'])
            ->call('applyModifyReport')
            ->assertDontSee('Name Street2')
            ->assertSee('Type');

        $line = InvoiceLine::query()->with(['item', 'invoice.customer.taxCode', 'invoice.paymentAllocations.payment'])->first();
        $payload = app(QbSalesReportExport::class)->build('customer_detail', collect([$line]));
        $detail = collect($payload['rows'])->first(fn (array $row) => ($row[1] ?? null) === 'Invoice');
        $index = array_flip($payload['headers']);

        $this->assertSame('Suite 4', $detail[$index['Name Street2']]);
        $this->assertSame('ACCT-44', $detail[$index['Name Account #']]);
        $this->assertSame('SODA-COKE', $detail[$index['Item']]);
        $this->assertSame('Coke 24ct', $detail[$index['Item Description']]);
        $this->assertSame('Income', $detail[$index['Account Type']]);
        $this->assertSame('20.00', $detail[$index['Balance']]);
    }

    public function test_filters_tab_lists_the_nashville_choose_filters(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $owner = User::factory()->create();
        $owner->assignRole('owner');
        $this->actingAs($owner);

        Livewire::test(SalesByItemReport::class)
            ->call('customizeReport')
            ->set('modifyTab', 'filters')
            ->assertSee('Choose Filter')
            ->assertSee('Current Filter Choices')
            ->assertSee('Report Type')
            ->assertSee('Account')
            ->assertSee('Name Zip')
            ->assertSee('Name Street1')
            ->assertSee('Item')
            ->assertSee('TransactionType')
            ->assertSee('Workers Comp Code')
            ->assertSee('P.O. Number')
            ->assertSee('Remove Selected Filter');
    }

    public function test_name_zip_item_and_customer_group_filters_limit_the_sales_report(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $owner = User::factory()->create();
        $owner->assignRole('owner');
        $this->actingAs($owner);

        $nashville = Customer::factory()->create([
            'display_name' => 'Nashville Buyer',
            'bill_to_state' => 'TN',
            'bill_to_zip' => '37201',
            'is_active' => true,
        ]);
        $memphis = Customer::factory()->create([
            'display_name' => 'Memphis Buyer',
            'bill_to_state' => 'TX',
            'bill_to_zip' => '38103',
            'is_active' => true,
        ]);
        $cola = Item::factory()->create([
            'sku' => 'ZIP-COLA',
            'name' => 'Zip Cola',
            'is_active' => true,
        ]);
        $river = Item::factory()->create([
            'sku' => 'RIVER-COLA',
            'name' => 'River Cola',
            'is_active' => true,
        ]);
        $this->makeInvoice($nashville, $cola, $owner, 'INV-NASH-1', paid: false);
        $this->makeInvoice($memphis, $river, $owner, 'INV-MEM-1', paid: false);

        Livewire::test(SalesByItemReport::class)
            ->set('datePreset', 'this_month')
            ->set('layout', 'customer_detail')
            ->assertSee('Nashville Buyer')
            ->assertSee('Memphis Buyer')
            ->call('customizeReport')
            ->set('modifyTab', 'filters')
            ->set('filterChoice', 'name_zip')
            ->set('draftFilters.extra.name_zip', '37201')
            ->call('applyModifyReport')
            ->assertSee('Nashville Buyer')
            ->assertDontSee('Memphis Buyer')
            ->set('filters.extra.name_zip', '')
            ->set('filters.item', [(string) $river->id])
            ->assertSee('Memphis Buyer')
            ->assertDontSee('Nashville Buyer')
            ->set('filters.item', [])
            ->set('filters.group', ['inside_tn'])
            ->assertSee('Nashville Buyer')
            ->assertDontSee('Memphis Buyer')
            ->set('filters.group', [])
            ->set('filters.extra.fob', 'dock')
            ->assertDontSee('Nashville Buyer')
            ->assertDontSee('Memphis Buyer');
    }

    public function test_cash_basis_includes_only_paid_invoices(): void
    {
        [$owner, $openNumber, $paidNumber] = $this->customerDetailSale(withPaidInvoice: true);
        $this->actingAs($owner);

        Livewire::test(SalesByItemReport::class)
            ->set('datePreset', 'this_month')
            ->set('layout', 'customer_detail')
            ->set('basis', 'cash')
            ->assertSee($paidNumber)
            ->assertDontSee($openNumber);
    }

    /**
     * @return array{0: User, 1: string, 2: string}
     */
    private function customerDetailSale(bool $withPaidInvoice = false): array
    {
        $this->seed(RolePermissionSeeder::class);

        $owner = User::factory()->create();
        $owner->assignRole('owner');

        $customer = Customer::factory()->create([
            'display_name' => '4 EVER',
            'customer_number' => 'ACCT-44',
            'bill_to_street1' => '123 Main',
            'bill_to_street2' => 'Suite 4',
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
            'is_active' => true,
        ]);

        $openNumber = 'INV-OPEN-1';
        $this->makeInvoice($customer, $item, $owner, $openNumber, paid: false);

        $paidNumber = 'INV-PAID-1';
        if ($withPaidInvoice) {
            $this->makeInvoice($customer, $item, $owner, $paidNumber, paid: true);
        }

        return [$owner, $openNumber, $paidNumber];
    }

    private function makeInvoice(Customer $customer, Item $item, User $owner, string $number, bool $paid): void
    {
        $invoice = Invoice::query()->create([
            'invoice_number' => $number,
            'customer_id' => $customer->id,
            'invoice_date' => now()->toDateString(),
            'status' => $paid ? 'paid' : 'open',
            'memo' => 'Weekly drop',
            'subtotal' => 20,
            'tax_total' => 0,
            'total' => 20,
            'amount_paid' => $paid ? 20 : 0,
            'balance_due' => $paid ? 0 : 20,
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
    }
}
