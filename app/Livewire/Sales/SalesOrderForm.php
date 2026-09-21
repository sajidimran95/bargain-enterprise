<?php

namespace App\Livewire\Sales;

use App\Livewire\Concerns\WithLineItems;
use App\Models\Customer;
use App\Models\Item;
use App\Models\SalesOrder;
use App\Models\SalesOrderLine;
use App\Services\InventoryService;
use App\Support\DocumentNumbers;
use App\Support\ItemCatalog;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Create Sales Order')]
class SalesOrderForm extends Component
{
    use WithLineItems;

    public ?int $editingId = null;

    public string $number = '';

    public string $customer_id = '';

    public string $order_date = '';

    public string $status = 'open';

    public string $memo = '';

    public function mount(?SalesOrder $salesOrder = null): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);

        if ($salesOrder?->exists) {
            abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);
            $this->loadOrder($salesOrder);

            return;
        }

        $this->number = DocumentNumbers::next(SalesOrder::class, 'number', 'SO-');
        $this->order_date = now()->toDateString();
        $this->addLine();
    }

    public function loadOrder(SalesOrder $order): void
    {
        $order->loadMissing('lines.item');
        $this->editingId = (int) $order->id;
        $this->number = (string) $order->number;
        $this->customer_id = (string) $order->customer_id;
        $this->order_date = $order->order_date?->toDateString() ?: now()->toDateString();
        $this->status = (string) $order->status;
        $this->memo = (string) ($order->memo ?? '');
        $this->lines = [];
        foreach ($order->lines as $line) {
            $this->lines[] = [
                'item_id' => (string) $line->item_id,
                'item_code' => $line->item?->barcode ?: $line->item?->sku ?: '',
                'description' => (string) ($line->description ?? ''),
                'quantity' => number_format((float) $line->quantity, 2, '.', ''),
                'rate' => number_format((float) $line->rate, 2, '.', ''),
                'amount' => number_format((float) $line->amount, 2, '.', ''),
                'taxable' => (bool) $line->taxable,
                'class' => '',
            ];
        }
        if ($this->lines === []) {
            $this->addLine();
        }
    }

    public function save(InventoryService $inventory): mixed
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);

        $this->validate([
            'number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sales_orders', 'number')->ignore($this->editingId),
            ],
            'customer_id' => ['required', 'exists:customers,id'],
            'order_date' => ['required', 'date'],
            'memo' => ['nullable', 'string', 'max:255'],
        ]);

        $lines = $this->validatedLinePayload();
        $subtotal = $this->linesSubtotal();

        try {
            DB::transaction(function () use ($lines, $subtotal, $inventory) {
                if ($this->editingId) {
                    $order = SalesOrder::query()->whereKey($this->editingId)->lockForUpdate()->with('lines.item')->firstOrFail();
                    if ($order->status === 'invoiced') {
                        throw new \RuntimeException('Cannot edit an invoiced sales order.');
                    }

                    $oldLines = $order->lines->map(fn (SalesOrderLine $line) => [
                        'item' => $line->item,
                        'quantity' => $line->quantity,
                    ])->all();

                    $newLines = collect($lines)->map(function (array $line) {
                        return [
                            'item' => Item::query()->findOrFail($line['item_id']),
                            'quantity' => $line['quantity'],
                        ];
                    })->all();

                    $wasCommitted = ! in_array($order->status, ['draft', 'cancelled', 'invoiced'], true);
                    $willCommit = ! in_array($order->status === 'draft' ? 'open' : $order->status, ['draft', 'cancelled', 'invoiced'], true);

                    $inventory->syncOnSoQty(
                        $wasCommitted ? $oldLines : [],
                        $willCommit ? $newLines : []
                    );

                    $order->update([
                        'number' => $this->number,
                        'customer_id' => (int) $this->customer_id,
                        'order_date' => $this->order_date,
                        'status' => $order->status === 'draft' ? 'open' : $order->status,
                        'subtotal' => $subtotal,
                        'tax_total' => 0,
                        'total' => $subtotal,
                        'memo' => $this->memo ?: null,
                    ]);

                    $order->lines()->delete();
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

                    $this->editingId = (int) $order->id;

                    return;
                }

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

                $newLines = [];
                foreach ($lines as $i => $line) {
                    $item = Item::query()->findOrFail($line['item_id']);
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
                    $newLines[] = ['item' => $item, 'quantity' => $line['quantity']];
                }

                $inventory->syncOnSoQty([], $newLines);
                $this->editingId = (int) $order->id;
            });
        } catch (\Throwable $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return null;
        }

        $this->dispatch('be-toast', message: 'Sales order '.$this->number.' saved.');

        return $this->redirect(route('sales-orders.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.sales.document-form', [
            'pageTitle' => $this->editingId ? 'Edit Sales Order' : 'Create Sales Order',
            'cancelRoute' => 'sales-orders.index',
            'partyLabel' => 'Customer',
            'partyOptions' => Customer::query()->active()->orderBy('display_name')->pluck('display_name', 'id')->all(),
            'partyField' => 'customer_id',
            'dateField' => 'order_date',
            'dateLabel' => 'Order Date',
            'numberField' => 'number',
            'numberLabel' => 'SO #',
            'itemOptions' => ItemCatalog::optionsForLineItems($this->lines),
        ])->layoutData([
            'title' => $this->editingId ? 'Edit Sales Order' : 'Create Sales Order',
            'windowTitle' => $this->editingId ? 'Edit Sales Order' : 'Create Sales Order',
        ]);
    }
}
