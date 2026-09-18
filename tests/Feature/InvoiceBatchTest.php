<?php

namespace Tests\Feature;

use App\Livewire\Sales\InvoiceBatch;
use App\Livewire\Sales\InvoiceForm;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class InvoiceBatchTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(RolePermissionSeeder::class);
        $this->owner = User::factory()->create();
        $this->owner->assignRole('owner');
    }

    public function test_create_a_batch_opens_print_later_queue(): void
    {
        $this->actingAs($this->owner);

        $customer = Customer::factory()->create(['is_active' => true]);
        $queued = Invoice::query()->create([
            'invoice_number' => 'INV-BATCH-1',
            'customer_id' => $customer->id,
            'invoice_date' => now()->toDateString(),
            'status' => 'open',
            'subtotal' => 25,
            'tax_total' => 0,
            'total' => 25,
            'amount_paid' => 0,
            'balance_due' => 25,
            'print_later' => true,
            'created_by' => $this->owner->id,
        ]);
        Invoice::query()->create([
            'invoice_number' => 'INV-BATCH-2',
            'customer_id' => $customer->id,
            'invoice_date' => now()->toDateString(),
            'status' => 'open',
            'subtotal' => 10,
            'tax_total' => 0,
            'total' => 10,
            'amount_paid' => 0,
            'balance_due' => 10,
            'print_later' => false,
            'created_by' => $this->owner->id,
        ]);

        Livewire::test(InvoiceForm::class)
            ->call('createBatch')
            ->assertSet('print_later', true)
            ->assertDispatched('be-toast');

        Livewire::test(InvoiceBatch::class, ['queue' => 'print'])
            ->assertSee('INV-BATCH-1')
            ->assertDontSee('INV-BATCH-2')
            ->set('selected.'.$queued->id, true)
            ->call('printSelected')
            ->assertDispatched('be-toast');

        $this->assertFalse((bool) $queued->fresh()->print_later);
    }

    public function test_reports_tab_shows_invoice_links(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(InvoiceForm::class)
            ->set('ribbonTab', 'reports')
            ->assertSee('Customer Open Balance')
            ->assertSee('A/R Aging')
            ->assertSee('Print Forms (Batch)')
            ->assertSee('Invoice List');
    }
}
