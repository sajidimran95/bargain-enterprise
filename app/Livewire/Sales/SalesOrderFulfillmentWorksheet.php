<?php

namespace App\Livewire\Sales;

use App\Actions\Sales\CreateInvoiceAction;
use App\Models\Invoice;
use App\Models\SalesOrder;
use App\Support\DocumentNumbers;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Sales Order Fulfillment')]
class SalesOrderFulfillmentWorksheet extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    /** @var array<int, bool> */
    public array $selectedOrders = [];

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function toggleOrder(int $orderId): void
    {
        $this->selectedOrders[$orderId] = ! ($this->selectedOrders[$orderId] ?? false);
    }

    public function selectAllVisible(): void
    {
        foreach ($this->ordersQuery()->paginate(50) as $order) {
            $this->selectedOrders[(int) $order->id] = true;
        }
    }

    public function clearSelection(): void
    {
        $this->selectedOrders = [];
    }

    public function createInvoices(CreateInvoiceAction $createInvoice): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);

        $ids = collect($this->selectedOrders)->filter()->keys()->map(fn ($id) => (int) $id)->all();
        if ($ids === []) {
            $this->dispatch('be-toast', message: 'Select at least one sales order.');

            return;
        }

        $created = 0;

        foreach (SalesOrder::query()->with(['lines.item', 'customer'])->whereIn('id', $ids)->where('status', 'open')->get() as $order) {
            if ($order->lines->isEmpty()) {
                continue;
            }

            $lines = $order->lines->map(fn ($line) => [
                'item_id' => $line->item_id,
                'description' => $line->description,
                'quantity' => $line->quantity,
                'rate' => $line->rate,
                'taxable' => $line->taxable,
            ])->all();

            try {
                DB::transaction(function () use ($createInvoice, $order, $lines, &$created) {
                    $createInvoice->handle([
                        'customer_id' => $order->customer_id,
                        'invoice_number' => DocumentNumbers::next(Invoice::class, 'invoice_number', 'INV-'),
                        'invoice_date' => now()->toDateString(),
                        'due_date' => now()->addDays(30)->toDateString(),
                        'sales_order_id' => $order->id,
                        'memo' => 'Fulfilled from '.$order->number,
                        'created_by' => auth()->id(),
                    ], $lines);

                    $order->update(['status' => 'invoiced']);
                    $created++;
                });
            } catch (\Throwable $e) {
                $this->dispatch('be-toast', message: $order->number.': '.$e->getMessage());
            }
        }

        $this->selectedOrders = [];
        $this->resetPage();
        $this->dispatch('be-toast', message: $created > 0
            ? "Created {$created} invoice(s) from selected sales orders."
            : 'No invoices created.');
    }

    protected function ordersQuery()
    {
        return SalesOrder::query()
            ->with(['customer', 'lines.item'])
            ->withCount('lines')
            ->where('status', 'open')
            ->when($this->search !== '', function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('number', 'like', $like)
                        ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                });
            })
            ->orderByDesc('order_date');
    }

    public function render()
    {
        $orders = $this->ordersQuery()->paginate(25);

        return view('livewire.sales.sales-order-fulfillment-worksheet', [
            'orders' => $orders,
        ])->layoutData([
            'title' => 'Sales Order Fulfillment',
            'windowTitle' => 'Sales Order Fulfillment Worksheet',
        ]);
    }
}
