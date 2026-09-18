<?php

namespace App\Livewire\Sales;

use App\Mail\DocumentMail;
use App\Models\Invoice;
use App\Services\DocumentPdfService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Select Invoices to Print / Email')]
class InvoiceBatch extends Component
{
    #[Url]
    public string $queue = 'print';

    /** @var array<int, bool> */
    public array $selected = [];

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        if (! in_array($this->queue, ['print', 'email', 'both'], true)) {
            $this->queue = 'print';
        }

        $this->selectAllVisible();
    }

    public function updatedQueue(): void
    {
        $this->selectAllVisible();
    }

    public function selectAllVisible(): void
    {
        $this->selected = $this->queuedInvoices()
            ->mapWithKeys(fn (Invoice $invoice) => [$invoice->id => true])
            ->all();
    }

    public function clearSelection(): void
    {
        $this->selected = [];
    }

    public function printSelected(): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        $ids = $this->selectedIds();
        if ($ids === []) {
            $this->dispatch('be-toast', message: 'Select at least one invoice to print.');

            return;
        }

        $urls = Invoice::query()
            ->whereIn('id', $ids)
            ->orderBy('invoice_date')
            ->orderBy('id')
            ->get()
            ->map(fn (Invoice $invoice) => route('invoices.pdf', $invoice))
            ->values()
            ->all();

        Invoice::query()->whereIn('id', $ids)->update(['print_later' => false]);

        $this->js('('.json_encode($urls).').forEach((url) => window.open(url, "_blank"))');
        $this->dispatch('be-toast', message: count($ids).' invoice PDF(s) opened. Print Later cleared.');
        $this->selectAllVisible();
    }

    public function emailSelected(DocumentPdfService $pdf): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        $ids = $this->selectedIds();
        if ($ids === []) {
            $this->dispatch('be-toast', message: 'Select at least one invoice to email.');

            return;
        }

        $invoices = Invoice::query()
            ->with(['customer', 'lines.item'])
            ->whereIn('id', $ids)
            ->orderBy('invoice_date')
            ->orderBy('id')
            ->get();

        $sent = 0;
        $skipped = 0;

        foreach ($invoices as $invoice) {
            $to = $invoice->customer?->email;
            if (! filled($to)) {
                $skipped++;

                continue;
            }

            $content = $pdf->output('pdf.invoice', ['invoice' => $invoice]);

            Mail::to($to)->send(new DocumentMail(
                headline: 'Invoice '.$invoice->invoice_number,
                intro: 'Please find attached invoice '.$invoice->invoice_number.' from '.config('bargain.company_name', config('app.name')).'.',
                pdf: [
                    'filename' => 'invoice-'.$invoice->invoice_number.'.pdf',
                    'content' => $content,
                ],
            ));

            $invoice->email_later = false;
            $invoice->save();
            $sent++;
        }

        $message = $sent.' invoice(s) emailed.';
        if ($skipped > 0) {
            $message .= ' '.$skipped.' skipped (no customer email).';
        }

        $this->dispatch('be-toast', message: $message);
        $this->selectAllVisible();
    }

    public function removeFromQueue(int $invoiceId): void
    {
        $invoice = Invoice::query()->findOrFail($invoiceId);
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        if ($this->queue === 'email') {
            $invoice->email_later = false;
        } elseif ($this->queue === 'print') {
            $invoice->print_later = false;
        } else {
            $invoice->print_later = false;
            $invoice->email_later = false;
        }

        $invoice->save();
        unset($this->selected[$invoiceId]);
        $this->dispatch('be-toast', message: $invoice->invoice_number.' removed from batch queue.');
    }

    /**
     * @return list<int>
     */
    protected function selectedIds(): array
    {
        return collect($this->selected)
            ->filter()
            ->keys()
            ->map(fn ($id) => (int) $id)
            ->values()
            ->all();
    }

    /**
     * @return Collection<int, Invoice>
     */
    protected function queuedInvoices()
    {
        return Invoice::query()
            ->with('customer')
            ->when($this->queue === 'print', fn ($q) => $q->where('print_later', true))
            ->when($this->queue === 'email', fn ($q) => $q->where('email_later', true))
            ->when($this->queue === 'both', fn ($q) => $q->where(function ($inner) {
                $inner->where('print_later', true)->orWhere('email_later', true);
            }))
            ->orderBy('invoice_date')
            ->orderBy('id')
            ->get();
    }

    public function render()
    {
        $invoices = $this->queuedInvoices();

        return view('livewire.sales.invoice-batch', [
            'invoices' => $invoices,
            'selectedCount' => count($this->selectedIds()),
        ])->layoutData([
            'title' => 'Select Invoices to Print / Email',
            'windowTitle' => 'Select Invoices to Print / Email',
        ]);
    }
}
