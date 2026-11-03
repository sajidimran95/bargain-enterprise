<?php

namespace App\Livewire\Sales;

use App\Actions\Sales\CreateInvoiceAction;
use App\Livewire\Concerns\WithErpListActions;
use App\Models\Invoice;
use App\Models\SalesOrder;
use App\Services\InventoryService;
use App\Support\DocumentNumbers;
use App\Support\SystemAccounts;
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
    use WithErpListActions;
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

    public function createInvoices(CreateInvoiceAction $createInvoice, InventoryService $inventory): void
    {
        abort_unless(auth()->user()?->hasPermission('invoice.create'), 403);

        $ids = collect($this->selectedOrders)
            ->filter(fn (mixed $selected): bool => filter_var($selected, FILTER_VALIDATE_BOOLEAN))
            ->keys()
            ->map(fn (mixed $id): int => (int) $id)
            ->filter(fn (int $id): bool => $id > 0)
            ->values()
            ->all();

        if ($ids === []) {
            $this->dispatch('be-toast', message: 'Select at least one sales order.');

            return;
        }

        SystemAccounts::ensureAll();

        $created = 0;
        /** @var list<string> $errors */
        $errors = [];
        $firstInvoiceId = null;

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
                $invoice = DB::transaction(function () use ($createInvoice, $inventory, $order, $lines, &$created) {
                    $invoice = $createInvoice->handle([
                        'customer_id' => $order->customer_id,
                        'invoice_number' => DocumentNumbers::next(Invoice::class, 'invoice_number', 'INV-'),
                        'invoice_date' => now()->toDateString(),
                        'due_date' => now()->addDays(30)->toDateString(),
                        'sales_order_id' => $order->id,
                        'memo' => 'Fulfilled from '.$order->number,
                        'created_by' => auth()->id(),
                    ], $lines);

                    $soLines = $order->lines->map(fn ($line) => [
                        'item' => $line->item,
                        'quantity' => $line->quantity,
                    ])->all();
                    $inventory->syncOnSoQty($soLines, []);

                    $order->update(['status' => 'invoiced']);
                    $created++;

                    return $invoice;
                });

                $firstInvoiceId ??= $invoice->id;
            } catch (\Throwable $e) {
                $errors[] = $order->number.': '.$e->getMessage();
            }
        }

        $this->selectedOrders = [];
        $this->resetPage();

        if ($created > 0) {
            $this->dispatch('be-toast', message: "Created {$created} invoice(s) from selected sales orders.");
            if ($firstInvoiceId !== null) {
                $this->openWorkspaceEdit('invoices.edit', ['invoice' => $firstInvoiceId], 'Invoice');
            }

            return;
        }

        $this->dispatch('be-toast', message: $errors[0] ?? 'No invoices created.');
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
