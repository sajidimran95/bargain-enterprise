<?php

namespace App\Livewire\Sales;

use App\Livewire\Concerns\SendsInvoiceEmail;
use App\Livewire\Concerns\WithErpListActions;
use App\Models\Invoice;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Invoices')]
class InvoiceIndex extends Component
{
    use AuthorizesRequests;
    use SendsInvoiceEmail;
    use WithErpListActions;
    use WithPagination;

    #[Url]
    public string $status = 'all';

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function openEdit(int $id): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);
        $invoice = Invoice::query()->findOrFail($id);
        $this->selectedLineId = $id;
        $this->openWorkspaceEdit('invoices.edit', ['invoice' => $invoice->id], 'Invoice: '.$invoice->invoice_number);
    }

    protected function selectedDocumentPdfUrl(int $id): ?string
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        $invoice = Invoice::query()->findOrFail($id);

        return route('invoices.print', $invoice);
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        $rows = Invoice::query()
            ->with('customer')
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status))
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('invoice_number', 'like', $like)
                        ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('invoice_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Invoice $invoice) => [
                $invoice->invoice_date?->format('Y-m-d'),
                $invoice->invoice_number,
                $invoice->customer?->display_name,
                $invoice->status,
                number_format((float) $invoice->total, 2, '.', ''),
                number_format((float) $invoice->amount_paid, 2, '.', ''),
                number_format((float) $invoice->balance_due, 2, '.', ''),
            ]);

        return $this->csvDownload(
            'invoices.csv',
            ['Date', 'Number', 'Customer', 'Status', 'Total', 'Paid', 'Balance'],
            $rows
        );
    }

    public function render()
    {
        $invoices = Invoice::query()
            ->with('customer')
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status))
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('invoice_number', 'like', $like)
                        ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('invoice_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('livewire.sales.invoice-index', [
            'invoices' => $invoices,
            'openBalance' => Invoice::query()->whereIn('status', ['open', 'partial'])->sum('balance_due'),
        ])->layoutData([
            'title' => 'Invoice List',
            'windowTitle' => 'Invoice List',
        ]);
    }
}
