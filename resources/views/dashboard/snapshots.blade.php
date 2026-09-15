<x-app-layout>
    <x-slot name="title">Company Snapshot</x-slot>
    <x-slot name="windowTitle">Company</x-slot>

    <div class="be-page">
        <div class="be-page__tabs" role="tablist">
            <span class="be-page__tab is-active" role="tab" aria-selected="true">Company</span>
            <span class="be-page__tab" role="tab">Payments</span>
            <span class="be-page__tab" role="tab">Customer</span>
        </div>

        <div class="be-toolbar">
            <button type="button" class="be-link-btn">Add Content &gt;</button>
            <button type="button" class="be-link-btn">Restore Default</button>
            <span class="ml-auto text-[11px] text-gray-500">Widget data arrives in Phase 10</span>
        </div>

        <div class="be-widget-grid">
            @foreach ([
                'Income and Expense Trend',
                'Prev Year Income Comparison',
                'Customers Who Owe Money',
                'Account Balances',
                'Top Customers by Sales',
                'Best-Selling Items',
                'Expense Breakdown',
            ] as $widget)
                <section class="be-widget" aria-label="{{ $widget }}">
                    <header class="be-widget__header">
                        <span>{{ $widget }}</span>
                        <select class="be-input w-auto py-0.5 text-[11px]" aria-label="Period for {{ $widget }}">
                            <option>This year</option>
                            <option>Last year</option>
                        </select>
                    </header>
                    <div class="be-widget__body">
                        There is no data for this graph.
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</x-app-layout>
