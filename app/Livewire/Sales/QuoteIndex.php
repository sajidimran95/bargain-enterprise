<?php

namespace App\Livewire\Sales;

use App\Livewire\Concerns\CreatesErpDrafts;
use App\Livewire\Concerns\WithErpListActions;
use App\Models\Quote;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Quotes')]
class QuoteIndex extends Component
{
    use CreatesErpDrafts;
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

    public function createDraft(): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);

        try {
            $customer = $this->requireFirstCustomer();
        } catch (RuntimeException $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return;
        }

        Quote::query()->create([
            'number' => $this->nextNumber(Quote::class, 'number', 'QT-'),
            'customer_id' => $customer->id,
            'quote_date' => now()->toDateString(),
            'expiry_date' => now()->addDays(30)->toDateString(),
            'status' => 'draft',
            'subtotal' => 0,
            'tax_total' => 0,
            'total' => 0,
            'memo' => 'Draft quote',
        ]);

        $this->resetPage();
        $this->dispatch('be-toast', message: 'Draft quote created.');
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('invoice.view'), 403);

        $rows = Quote::query()
            ->with('customer')
            ->withCount('lines')
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status))
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('number', 'like', $like)
                        ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('quote_date')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Quote $quote) => [
                $quote->quote_date?->format('Y-m-d'),
                $quote->number,
                $quote->customer?->display_name,
                $quote->status,
                $quote->lines_count,
                number_format((float) $quote->total, 2, '.', ''),
            ]);

        return $this->csvDownload(
            'quotes.csv',
            ['Date', 'Number', 'Customer', 'Status', 'Lines', 'Total'],
            $rows
        );
    }

    public function render()
    {
        $quotes = Quote::query()
            ->with('customer')
            ->withCount('lines')
            ->when($this->status !== 'all', fn ($q) => $q->where('status', $this->status))
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('number', 'like', $like)
                        ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('quote_date')
            ->orderByDesc('id')
            ->paginate(30);

        return view('livewire.sales.quote-index', [
            'quotes' => $quotes,
        ])->layoutData([
            'title' => 'Quotes',
            'windowTitle' => 'Quotes',
        ]);
    }
}
