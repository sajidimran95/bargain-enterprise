<?php

namespace App\Livewire\Concerns;

use App\Mail\DocumentMail;
use App\Models\Invoice;
use App\Services\DocumentPdfService;
use Illuminate\Support\Facades\Mail;

trait SendsInvoiceEmail
{
    public bool $showEmailModal = false;

    public string $emailInvoiceId = '';

    public string $emailTo = '';

    public function openInvoiceEmail(int $invoiceId): void
    {
        $invoice = Invoice::query()->with('customer')->findOrFail($invoiceId);
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        $this->emailInvoiceId = (string) $invoice->id;
        $this->emailTo = $invoice->customer?->email ?: (auth()->user()?->email ?? '');
        $this->showEmailModal = true;
    }

    public function closeEmailModal(): void
    {
        $this->showEmailModal = false;
        $this->emailInvoiceId = '';
        $this->emailTo = '';
    }

    public function sendInvoiceEmail(DocumentPdfService $pdf): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        $this->validate([
            'emailTo' => ['required', 'email'],
            'emailInvoiceId' => ['required', 'exists:invoices,id'],
        ]);

        $invoice = Invoice::query()->with(['customer', 'lines.item'])->findOrFail($this->emailInvoiceId);
        $content = $pdf->output('pdf.invoice', ['invoice' => $invoice]);

        Mail::to($this->emailTo)->send(new DocumentMail(
            headline: 'Invoice '.$invoice->invoice_number,
            intro: 'Please find attached invoice '.$invoice->invoice_number.' from '.config('bargain.company_name', config('app.name')).'.',
            pdf: [
                'filename' => 'invoice-'.$invoice->invoice_number.'.pdf',
                'content' => $content,
            ],
        ));

        $this->closeEmailModal();
        $this->dispatch('be-toast', message: 'Invoice emailed to '.$this->emailTo);
    }
}
