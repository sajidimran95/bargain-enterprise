<?php

namespace App\Livewire\Search;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\SalesOrder;
use App\Models\Vendor;
use App\Models\VendorBill;
use Illuminate\Support\Js;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $query = '';

    public bool $open = false;

    /**
     * @var list<array{type: string, label: string, results: list<array{title: string, subtitle?: string, route: string, params?: array<string, mixed>, title_tab?: string}>}>
     */
    public array $groups = [];

    public function updatedQuery(): void
    {
        $this->search();
    }

    public function search(): void
    {
        $term = trim($this->query);

        if (mb_strlen($term) < 2) {
            $this->groups = [];
            $this->open = false;

            return;
        }

        $groups = [];
        $like = '%'.$term.'%';
        $user = auth()->user();

        if ($user?->hasPermission('customer.view')) {
            $customers = Customer::query()
                ->active()
                ->search($term)
                ->orderBy('display_name')
                ->limit(6)
                ->get()
                ->map(fn (Customer $customer) => [
                    'title' => $customer->display_name,
                    'subtitle' => trim(($customer->customer_number ? '#'.$customer->customer_number.' · ' : '').($customer->company_name ?: '').($customer->phone ? ' · '.$customer->phone : '')),
                    'route' => 'customers.show',
                    'params' => ['customer' => $customer->id],
                    'title_tab' => 'Customer: '.$customer->display_name,
                ])
                ->all();

            if ($customers !== []) {
                $groups[] = ['type' => 'customers', 'label' => 'Customers', 'results' => $customers];
            }
        }

        if ($user?->hasPermission('vendor.view')) {
            $vendors = Vendor::query()
                ->active()
                ->search($term)
                ->orderBy('display_name')
                ->limit(6)
                ->get()
                ->map(fn (Vendor $vendor) => [
                    'title' => $vendor->display_name,
                    'subtitle' => (string) ($vendor->company_name ?: $vendor->vendor_number),
                    'route' => 'vendors.show',
                    'params' => ['vendor' => $vendor->id],
                    'title_tab' => 'Vendor: '.$vendor->display_name,
                ])
                ->all();

            if ($vendors !== []) {
                $groups[] = ['type' => 'vendors', 'label' => 'Vendors', 'results' => $vendors];
            }
        }

        if ($user?->hasPermission('item.view')) {
            $items = Item::query()
                ->active()
                ->search($term)
                ->orderBy('sku')
                ->limit(6)
                ->get()
                ->map(fn (Item $item) => [
                    'title' => $item->sku,
                    'subtitle' => (string) ($item->sales_description ?: $item->name),
                    'route' => 'items.edit',
                    'params' => ['item' => $item->id],
                    'title_tab' => 'Item: '.$item->sku,
                ])
                ->all();

            if ($items !== []) {
                $groups[] = ['type' => 'items', 'label' => 'Items', 'results' => $items];
            }
        }

        if ($user?->hasPermission('invoice.view')) {
            $invoices = Invoice::query()
                ->with('customer')
                ->where(function ($q) use ($like) {
                    $q->where('invoice_number', 'like', $like)
                        ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                })
                ->orderByDesc('invoice_date')
                ->limit(6)
                ->get()
                ->map(fn (Invoice $invoice) => [
                    'title' => $invoice->invoice_number,
                    'subtitle' => trim(($invoice->customer?->display_name ?? '').' · '.number_format((float) $invoice->total, 2).' · '.$invoice->status),
                    'route' => 'invoices.index',
                    'params' => [],
                    'title_tab' => 'Invoices',
                ])
                ->all();

            if ($invoices !== []) {
                $groups[] = ['type' => 'invoices', 'label' => 'Invoices', 'results' => $invoices];
            }

            $salesOrders = SalesOrder::query()
                ->with('customer')
                ->where(function ($q) use ($like) {
                    $q->where('number', 'like', $like)
                        ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                })
                ->orderByDesc('order_date')
                ->limit(5)
                ->get()
                ->map(fn (SalesOrder $order) => [
                    'title' => $order->number,
                    'subtitle' => trim(($order->customer?->display_name ?? '').' · '.number_format((float) $order->total, 2)),
                    'route' => 'sales-orders.index',
                    'params' => [],
                    'title_tab' => 'Sales Orders',
                ])
                ->all();

            if ($salesOrders !== []) {
                $groups[] = ['type' => 'sales-orders', 'label' => 'Sales Orders', 'results' => $salesOrders];
            }
        }

        if ($user?->hasPermission('purchase.view')) {
            $bills = VendorBill::query()
                ->with('vendor')
                ->where(function ($q) use ($like) {
                    $q->where('bill_number', 'like', $like)
                        ->orWhereHas('vendor', fn ($v) => $v->where('display_name', 'like', $like));
                })
                ->orderByDesc('bill_date')
                ->limit(5)
                ->get()
                ->map(fn (VendorBill $bill) => [
                    'title' => $bill->bill_number,
                    'subtitle' => trim(($bill->vendor?->display_name ?? '').' · '.number_format((float) $bill->total, 2)),
                    'route' => 'vendor-bills.index',
                    'params' => [],
                    'title_tab' => 'Bills',
                ])
                ->all();

            if ($bills !== []) {
                $groups[] = ['type' => 'bills', 'label' => 'Bills', 'results' => $bills];
            }

            $purchaseOrders = PurchaseOrder::query()
                ->with('vendor')
                ->where(function ($q) use ($like) {
                    $q->where('number', 'like', $like)
                        ->orWhereHas('vendor', fn ($v) => $v->where('display_name', 'like', $like));
                })
                ->orderByDesc('order_date')
                ->limit(5)
                ->get()
                ->map(fn (PurchaseOrder $order) => [
                    'title' => $order->number,
                    'subtitle' => trim(($order->vendor?->display_name ?? '').' · '.number_format((float) $order->total, 2)),
                    'route' => 'purchase-orders.index',
                    'params' => [],
                    'title_tab' => 'Purchase Orders',
                ])
                ->all();

            if ($purchaseOrders !== []) {
                $groups[] = ['type' => 'purchase-orders', 'label' => 'Purchase Orders', 'results' => $purchaseOrders];
            }
        }

        $help = $this->searchMenusAndShortcuts($term);
        if ($help !== []) {
            $groups[] = ['type' => 'help', 'label' => 'Menus & Shortcuts', 'results' => $help];
        }

        $this->groups = $groups;
        $this->open = true;
    }

    public function openResult(string $route, array $params = [], ?string $title = null): void
    {
        $this->close();
        $this->js(
            'typeof beOpenWorkspace === "function" && beOpenWorkspace('
            .Js::from($route).', '
            .Js::from($params).', '
            .Js::from($title)
            .')'
        );
    }

    public function close(): void
    {
        $this->open = false;
    }

    public function clear(): void
    {
        $this->query = '';
        $this->groups = [];
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.search.global-search');
    }

    /**
     * @return list<array{title: string, subtitle?: string, route: string, params?: array<string, mixed>, title_tab?: string}>
     */
    private function searchMenusAndShortcuts(string $term): array
    {
        $needle = mb_strtolower($term);
        $hits = [];

        foreach (config('erp_shortcuts.catalog', []) as $item) {
            $label = (string) ($item['label'] ?? '');
            $route = $item['route'] ?? null;
            if ($route && str_contains(mb_strtolower($label), $needle)) {
                $hits[$route.'|'.$label] = [
                    'title' => $label,
                    'subtitle' => 'Shortcut',
                    'route' => $route,
                    'params' => [],
                    'title_tab' => $label,
                ];
            }
        }

        $walk = function (array $entries, string $path = '') use (&$walk, &$hits, $needle): void {
            foreach ($entries as $entry) {
                if (! empty($entry['separator'])) {
                    continue;
                }

                $label = (string) ($entry['label'] ?? '');
                $full = trim($path.' › '.$label, ' ›');

                if (! empty($entry['route']) && str_contains(mb_strtolower($label), $needle)) {
                    $hits[$entry['route'].'|'.$full] = [
                        'title' => $label,
                        'subtitle' => $path !== '' ? 'Menu: '.$path : 'Menu',
                        'route' => $entry['route'],
                        'params' => [],
                        'title_tab' => $label,
                    ];
                }

                if (! empty($entry['children']) && is_array($entry['children'])) {
                    $walk($entry['children'], $full !== '' ? $full : $label);
                }
            }
        };

        foreach (config('erp_menubar', []) as $menu => $entries) {
            if (is_array($entries)) {
                $walk($entries, $menu);
            }
        }

        return array_values(array_slice($hits, 0, 8));
    }
}
