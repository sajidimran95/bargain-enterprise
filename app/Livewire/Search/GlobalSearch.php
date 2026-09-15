<?php

namespace App\Livewire\Search;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Vendor;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $query = '';

    public bool $open = false;

    /** @var array<int, array{type: string, label: string, results: array<int, array{title: string, subtitle?: string, url: string}>}> */
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

        if (auth()->user()?->hasPermission('customer.view')) {
            $customers = Customer::query()
                ->active()
                ->search($term)
                ->orderBy('display_name')
                ->limit(8)
                ->get()
                ->map(fn (Customer $c) => [
                    'title' => $c->display_name,
                    'subtitle' => $c->company_name.($c->phone ? ' · '.$c->phone : ''),
                    'url' => route('customers.show', $c),
                ])
                ->all();

            if ($customers !== []) {
                $groups[] = ['type' => 'customers', 'label' => 'Customers', 'results' => $customers];
            }
        }

        if (auth()->user()?->hasPermission('vendor.view')) {
            $vendors = Vendor::query()
                ->active()
                ->search($term)
                ->orderBy('display_name')
                ->limit(8)
                ->get()
                ->map(fn (Vendor $v) => [
                    'title' => $v->display_name,
                    'subtitle' => $v->company_name,
                    'url' => route('vendors.show', $v),
                ])
                ->all();

            if ($vendors !== []) {
                $groups[] = ['type' => 'vendors', 'label' => 'Vendors', 'results' => $vendors];
            }
        }

        if (auth()->user()?->hasPermission('item.view')) {
            $items = Item::query()
                ->active()
                ->search($term)
                ->orderBy('sku')
                ->limit(8)
                ->get()
                ->map(fn (Item $i) => [
                    'title' => $i->sku,
                    'subtitle' => $i->sales_description ?: $i->name,
                    'url' => route('items.edit', $i),
                ])
                ->all();

            if ($items !== []) {
                $groups[] = ['type' => 'items', 'label' => 'Items', 'results' => $items];
            }
        }

        $this->groups = $groups;
        $this->open = true;
    }

    public function close(): void
    {
        $this->open = false;
    }

    public function render()
    {
        return view('livewire.search.global-search');
    }
}
