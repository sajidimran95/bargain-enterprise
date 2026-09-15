<?php

namespace Tests\Feature;

use App\Livewire\Reports\CustomerDirectoryReport;
use App\Livewire\Sales\InvoiceIndex;
use App\Mail\DocumentMail;
use App\Models\CreditMemo;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class DocumentPdfEmailTest extends TestCase
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

    public function test_guests_cannot_download_invoice_pdf(): void
    {
        $invoice = $this->makeInvoice();

        $this->get(route('invoices.pdf', $invoice))
            ->assertRedirect(route('login'));
    }

    public function test_owner_can_download_invoice_pdf(): void
    {
        $this->actingAs($this->owner);

        $invoice = $this->makeInvoice();

        $this->get(route('invoices.pdf', $invoice))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_owner_can_download_credit_memo_pdf(): void
    {
        $this->actingAs($this->owner);

        $memo = CreditMemo::query()->create([
            'credit_number' => 'CM-PDF-1',
            'customer_id' => Customer::factory()->create()->id,
            'credit_date' => now()->toDateString(),
            'status' => 'open',
            'subtotal' => 25,
            'tax_total' => 0,
            'total' => 25,
            'remaining_credit' => 25,
        ]);

        $this->get(route('credit-memos.pdf', $memo))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_report_pdf_download_works(): void
    {
        $this->actingAs($this->owner);

        Customer::factory()->create([
            'display_name' => 'PDF Customer',
            'is_active' => true,
        ]);

        Livewire::test(CustomerDirectoryReport::class)
            ->call('exportPdf')
            ->assertFileDownloaded('customer-directory.pdf');
    }

    public function test_invoice_email_sends_pdf_attachment(): void
    {
        Mail::fake();
        $this->actingAs($this->owner);

        $invoice = $this->makeInvoice([
            'email' => 'buyer@example.com',
        ]);

        Livewire::test(InvoiceIndex::class)
            ->call('openInvoiceEmail', $invoice->id)
            ->assertSet('showEmailModal', true)
            ->assertSet('emailTo', 'buyer@example.com')
            ->call('sendInvoiceEmail');

        Mail::assertSent(DocumentMail::class, function (DocumentMail $mail) use ($invoice) {
            return $mail->hasTo('buyer@example.com')
                && $mail->headline === 'Invoice '.$invoice->invoice_number
                && count($mail->attachments()) === 1;
        });
    }

    public function test_report_email_sends_pdf_attachment(): void
    {
        Mail::fake();
        $this->actingAs($this->owner);

        Livewire::test(CustomerDirectoryReport::class)
            ->call('openEmailModal')
            ->set('emailTo', 'reports@example.com')
            ->call('sendReportEmail')
            ->assertSet('showEmailModal', false);

        Mail::assertSent(DocumentMail::class, function (DocumentMail $mail) {
            return $mail->hasTo('reports@example.com')
                && $mail->headline === 'Customer Contact List'
                && count($mail->attachments()) === 1;
        });
    }

    /**
     * @param  array{email?: string}  $customerOverrides
     */
    protected function makeInvoice(array $customerOverrides = []): Invoice
    {
        $customer = Customer::factory()->create(array_merge([
            'display_name' => 'Invoice PDF Co',
            'is_active' => true,
        ], $customerOverrides));

        return Invoice::query()->create([
            'invoice_number' => 'INV-PDF-1',
            'customer_id' => $customer->id,
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'status' => 'open',
            'subtotal' => 50,
            'tax_total' => 0,
            'total' => 50,
            'amount_paid' => 0,
            'balance_due' => 50,
            'created_by' => $this->owner->id,
        ]);
    }
}
