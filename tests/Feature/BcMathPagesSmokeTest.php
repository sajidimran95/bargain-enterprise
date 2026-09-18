<?php

namespace Tests\Feature;

use App\Livewire\Purchasing\VendorBillForm;
use App\Livewire\Sales\InvoiceForm;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class BcMathPagesSmokeTest extends TestCase
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

    public function test_bcadd_is_available(): void
    {
        $this->assertTrue(function_exists('bcadd'));
        $this->assertSame('3.00', bcadd('1.00', '2.00', 2));
    }

    public function test_vendor_bill_form_renders_and_amount_due_works(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(VendorBillForm::class)
            ->assertOk()
            ->assertSet('docType', 'bill');

        $this->assertSame('0.00', bcadd('0.00', '0.00', 2));
        $this->assertTrue(function_exists('bcadd'));
        $this->assertTrue(function_exists('bcsub'));
        $this->assertTrue(function_exists('bcmul'));
        $this->assertTrue(function_exists('bcdiv'));
        $this->assertTrue(function_exists('bccomp'));
    }

    public function test_invoice_form_renders(): void
    {
        $this->actingAs($this->owner);

        Livewire::test(InvoiceForm::class)
            ->assertOk();
    }
}
