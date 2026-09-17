<?php

namespace App\Livewire\Sales;

use App\Livewire\Concerns\WithLineItems;
use App\Models\Customer;
use App\Models\SalesOrder;
use App\Models\SalesOrderLine;
use App\Support\DocumentNumbers;
use App\Support\ItemCatalog;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Create Sales Order')]
class SalesOrderForm extends Component
{
    use WithLineItems;

    public string $number = '';

    public string $customer_id = '';

    public string $order_date = '';

    public string $status = 'open';

    public string $memo = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);
        $this->number = DocumentNumbers::next(SalesOrder::class, 'number', 'SO-');
        $this->order_date = now()->toDateString();
        $this->addLine();
    }

    public function save(): mixed
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);

        $this->validate([
            'number' => ['required', 'string', 'max:50', 'unique:sales_orders,number'],
            'customer_id' => ['required', 'exists:customers,id'],
            'order_date' => ['required', 'date'],
            'memo' => ['nullable', 'string', 'max:255'],
        ]);

        $lines = $this->validatedLinePayload();
        $subtotal = $this->linesSubtotal();

        DB::transaction(function () use ($lines, $subtotal) {
            $order = SalesOrder::query()->create([
                'number' => $this->number,
                'customer_id' => (int) $this->customer_id,
                'order_date' => $this->order_date,
                'status' => 'open',
                'subtotal' => $subtotal,
                'tax_total' => 0,
                'total' => $subtotal,
                'memo' => $this->memo ?: null,
            ]);

            foreach ($lines as $i => $line) {
                SalesOrderLine::query()->create([
                    'sales_order_id' => $order->id,
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

        $this->dispatch('be-toast', message: 'Sales order '.$this->number.' saved.');

        return $this->redirect(route('sales-orders.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.sales.document-form', [
            'pageTitle' => 'Create Sales Order',
            'cancelRoute' => 'sales-orders.index',
            'partyLabel' => 'Customer',
            'partyOptions' => Customer::query()->active()->orderBy('display_name')->pluck('display_name', 'id')->all(),
            'partyField' => 'customer_id',
            'dateField' => 'order_date',
            'dateLabel' => 'Order Date',
            'numberField' => 'number',
            'numberLabel' => 'SO #',

            'itemOptions' => ItemCatalog::selectOptions(),
        ])->layoutData([
            'title' => 'Create Sales Order',
            'windowTitle' => 'Create Sales Order',
        ]);
    }
}
