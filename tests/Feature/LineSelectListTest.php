<?php

namespace Tests\Feature;

use App\Livewire\Sales\InvoiceIndex;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LineSelectListTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_list_supports_line_wise_selection(): void
    {
        $this->seed(RolePermissionSeeder::class);
        $owner = User::factory()->create();
        $owner->assignRole('owner');
        $this->actingAs($owner);

        $invoice = Invoice::query()->create([
            'invoice_number' => 'INV-SEL-1',
            'customer_id' => Customer::factory()->create()->id,
            'invoice_date' => now()->toDateString(),
            'status' => 'open',
            'subtotal' => 10,
            'tax_total' => 0,
            'total' => 10,
            'amount_paid' => 0,
            'balance_due' => 10,
            'created_by' => $owner->id,
        ]);

        Livewire::test(InvoiceIndex::class)
            ->assertSee('INV-SEL-1')
            ->call('selectLine', $invoice->id)
            ->assertSet('selectedLineId', $invoice->id)
            ->assertSeeHtml('is-selected');
    }
}
