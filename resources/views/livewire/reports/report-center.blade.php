<div class="be-page">
    <x-erp.list-toolbar heading="Report Center" :showFind="false" :showExcel="false" :showPrint="false" />

    <div class="m-3 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
        <div class="be-panel">
            <div class="be-panel__header">
                <h2 class="be-panel__title">Customer Contact List</h2>
            </div>
            <div class="be-panel__body">
                <p class="text-2xl font-semibold">{{ number_format($customerCount) }}</p>
                <p class="text-sm text-gray-500">Active customers</p>
                <div class="mt-3 flex gap-2">
                    <a href="{{ route('reports.customers') }}" class="be-btn be-btn--primary">Run Report</a>
                    <x-erp.button type="button" wire:click="exportCustomers">Excel</x-erp.button>
                </div>
            </div>
        </div>

        <div class="be-panel">
            <div class="be-panel__header">
                <h2 class="be-panel__title">Inventory / Stock</h2>
            </div>
            <div class="be-panel__body">
                <p class="text-2xl font-semibold">{{ number_format((float) $inventoryUnits, 2) }}</p>
                <p class="text-sm text-gray-500">Total units on hand</p>
                <div class="mt-3 flex gap-2">
                    <a href="{{ route('reports.inventory') }}" class="be-btn be-btn--primary">Run Report</a>
                    <x-erp.button type="button" wire:click="exportInventory">Excel</x-erp.button>
                </div>
            </div>
        </div>

        <div class="be-panel">
            <div class="be-panel__header">
                <h2 class="be-panel__title">Sales by Item</h2>
            </div>
            <div class="be-panel__body">
                <p class="text-2xl font-semibold">{{ number_format((float) $salesThisMonth, 2) }}</p>
                <p class="text-sm text-gray-500">{{ now()->format('F Y') }} invoiced sales</p>
                <div class="mt-3 flex gap-2">
                    <a href="{{ route('reports.sales-by-item') }}" class="be-btn be-btn--primary">Run Report</a>
                    <x-erp.button type="button" wire:click="exportSalesThisMonth">Excel</x-erp.button>
                </div>
            </div>
        </div>

        <div class="be-panel">
            <div class="be-panel__header">
                <h2 class="be-panel__title">Customer Open Balance</h2>
            </div>
            <div class="be-panel__body">
                <p class="text-2xl font-semibold">{{ number_format((float) $openAr, 2) }}</p>
                <p class="text-sm text-gray-500">Outstanding receivables</p>
                <div class="mt-3 flex gap-2">
                    <a href="{{ route('reports.open-balance') }}" class="be-btn be-btn--primary">Run Report</a>
                    <x-erp.button type="button" wire:click="exportOpenAr">Excel</x-erp.button>
                </div>
            </div>
        </div>

        <div class="be-panel">
            <div class="be-panel__header">
                <h2 class="be-panel__title">Open AP</h2>
            </div>
            <div class="be-panel__body">
                <p class="text-2xl font-semibold">{{ number_format((float) $openAp, 2) }}</p>
                <p class="text-sm text-gray-500">Outstanding payables</p>
                <div class="mt-3 flex gap-2">
                    <a href="{{ route('vendor-bills.index') }}" class="be-btn">Open Bills</a>
                    <x-erp.button type="button" wire:click="exportOpenAp">Excel</x-erp.button>
                </div>
            </div>
        </div>
    </div>
</div>
