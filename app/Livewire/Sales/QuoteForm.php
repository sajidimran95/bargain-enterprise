<?php

namespace App\Livewire\Sales;

use App\Livewire\Concerns\WithLineItems;
use App\Models\Customer;
use App\Models\Quote;
use App\Models\QuoteLine;
use App\Support\DocumentNumbers;
use App\Support\ItemCatalog;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Create Quote')]
class QuoteForm extends Component
{
    use WithLineItems;

    public string $number = '';

    public string $customer_id = '';

    public string $quote_date = '';

    public string $expiry_date = '';

    public string $status = 'open';

    public string $memo = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);
        $this->number = DocumentNumbers::next(Quote::class, 'number', 'QT-');
        $this->quote_date = now()->toDateString();
        $this->expiry_date = now()->addDays(30)->toDateString();
        $this->addLine();
    }

    public function save(): mixed
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);

        $this->validate([
            'number' => ['required', 'string', 'max:50', 'unique:quotes,number'],
            'customer_id' => ['required', 'exists:customers,id'],
            'quote_date' => ['required', 'date'],
            'expiry_date' => ['nullable', 'date'],
            'memo' => ['nullable', 'string', 'max:255'],
        ]);

        $lines = $this->validatedLinePayload();
        $subtotal = $this->linesSubtotal();

        DB::transaction(function () use ($lines, $subtotal) {
            $quote = Quote::query()->create([
                'number' => $this->number,
                'customer_id' => (int) $this->customer_id,
                'quote_date' => $this->quote_date,
                'expiry_date' => $this->expiry_date ?: null,
                'status' => 'open',
                'subtotal' => $subtotal,
                'tax_total' => 0,
                'total' => $subtotal,
                'memo' => $this->memo ?: null,
            ]);

            foreach ($lines as $i => $line) {
                QuoteLine::query()->create([
                    'quote_id' => $quote->id,
                    'item_id' => $line['item_id'],
                    'description' => $line['description'] ?? null,
                    'quantity' => $line['quantity'],
                    'rate' => $line['rate'],
                    'amount' => number_format((float) $line['quantity'] * (float) $line['rate'], 2, '.', ''),
                    'taxable' => (bool) ($line['taxable'] ?? true),
                    'line_order' => $i,
                ]);
            }
        });

        $this->dispatch('be-toast', message: 'Quote '.$this->number.' saved.');

        return $this->redirect(route('quotes.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.sales.document-form', [
            'pageTitle' => 'Create Quote',
            'cancelRoute' => 'quotes.index',
            'partyLabel' => 'Customer',
            'partyOptions' => Customer::query()->active()->orderBy('display_name')->pluck('display_name', 'id')->all(),
            'partyField' => 'customer_id',
            'dateField' => 'quote_date',
            'dateLabel' => 'Quote Date',
            'numberField' => 'number',
            'numberLabel' => 'Quote #',
            'showExpiry' => true,

            'itemOptions' => ItemCatalog::selectOptions(),
        ])->layoutData([
            'title' => 'Create Quote',
            'windowTitle' => 'Create Quote',
        ]);
    }
}
