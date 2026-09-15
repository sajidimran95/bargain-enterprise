<div class="be-page be-home-page">
    <x-erp.home-insights-tabs active="insights" />

    <div class="be-insights-bar">
        <div class="be-insights-bar__tabs" role="tablist">
            <button type="button" role="tab" wire:click="setInsightsTab('company')" class="{{ $insightsTab === 'company' ? 'is-active' : '' }}" aria-selected="{{ $insightsTab === 'company' ? 'true' : 'false' }}">Company</button>
            <button type="button" role="tab" wire:click="setInsightsTab('payments')" class="{{ $insightsTab === 'payments' ? 'is-active' : '' }}" aria-selected="{{ $insightsTab === 'payments' ? 'true' : 'false' }}">Payments</button>
            <button type="button" role="tab" wire:click="setInsightsTab('customer')" class="{{ $insightsTab === 'customer' ? 'is-active' : '' }}" aria-selected="{{ $insightsTab === 'customer' ? 'true' : 'false' }}">Customer</button>
        </div>
        <div class="be-insights-bar__tools">
            <button type="button" class="be-link-btn text-[11px]" wire:click="toggleAddContent">Add Content &gt;</button>
            <button type="button" class="be-link-btn text-[11px]" wire:click="restoreDefaultWidgets">Restore Default</button>
            <span class="text-[11px] text-gray-500">Live data · {{ $year }}</span>
        </div>
    </div>

    @if ($showAddContent && $insightsTab === 'company')
        <div class="be-panel mx-3 mb-2">
            <div class="be-panel__header">
                <h2 class="be-panel__title text-[12px]">Add Content</h2>
            </div>
            <div class="be-panel__body flex flex-wrap gap-3 py-2">
                @foreach ($widgetLabels as $key => $label)
                    <label class="inline-flex items-center gap-1.5 text-[11px] text-gray-700">
                        <input type="checkbox" @checked($widgets[$key] ?? false) wire:click="toggleWidget('{{ $key }}')">
                        {{ $label }}
                    </label>
                @endforeach
            </div>
        </div>
    @endif

    @if ($insightsTab === 'company')
        <div class="be-widget-grid">
            @if ($widgets['income_expense'] ?? true)
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Income and Expense Trend</span>
                    <span class="be-widget__meta">This year</span>
                </div>
                <div class="be-widget__body">
                    @for ($m = 1; $m <= 12; $m++)
                        @php
                            $income = (float) ($monthlyIncome[$m] ?? 0);
                            $expense = (float) ($expenseByMonth[$m] ?? 0);
                            $max = max(1, (float) $monthlyIncome->max(), (float) $expenseByMonth->max());
                        @endphp
                        <div class="mb-1 flex items-center gap-2 text-[11px]">
                            <span class="w-8 text-gray-500">{{ date('M', mktime(0, 0, 0, $m, 1)) }}</span>
                            <div class="flex h-3 flex-1 gap-0.5 border border-gray-200 bg-white">
                                <div class="h-full bg-[#5cb85c]" style="width: {{ min(50, $income > 0 ? max(2, ($income / $max) * 50) : 0) }}%"></div>
                                <div class="h-full bg-[#e67e22]" style="width: {{ min(50, $expense > 0 ? max(2, ($expense / $max) * 50) : 0) }}%"></div>
                            </div>
                            <span class="w-20 text-right tabular-nums text-[10px]">{{ number_format($income, 0) }}</span>
                        </div>
                    @endfor
                    <div class="mt-2 flex gap-3 text-[10px] text-gray-500">
                        <span><span class="inline-block h-2 w-2 bg-[#5cb85c]"></span> Income</span>
                        <span><span class="inline-block h-2 w-2 bg-[#e67e22]"></span> Expenses</span>
                    </div>
                </div>
            </div>
            @endif

            @if ($widgets['account_balances'] ?? true)
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Account Balances</span>
                    <a href="{{ route('accounting.chart') }}" class="be-widget__meta be-link-btn">Go to Chart of Accounts</a>
                </div>
                <div class="be-widget__body">
                    <div class="be-kv"><span class="be-kv__label">Accounts Receivable</span><span class="num">{{ number_format($arBalance, 2) }}</span></div>
                    <div class="be-kv"><span class="be-kv__label">Accounts Payable</span><span class="num">{{ number_format($apBalance, 2) }}</span></div>
                    <div class="be-kv"><span class="be-kv__label">Inventory Value</span><span class="num">{{ number_format($inventoryValue, 2) }}</span></div>
                    <div class="be-kv"><span class="be-kv__label">Open Invoices</span><span>{{ $openInvoiceCount }}</span></div>
                    <div class="be-kv"><span class="be-kv__label">Open Vendor Bills</span><span>{{ $openBillCount }}</span></div>
                </div>
            </div>
            @endif

            @if ($widgets['expense_breakdown'] ?? true)
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Expense Breakdown</span>
                    <span class="be-widget__meta">This year-to-date</span>
                </div>
                <div class="be-widget__body">
                    @php $expenseTotal = (float) $expenseByMonth->sum(); @endphp
                    @if ($expenseTotal > 0)
                        @for ($m = 1; $m <= 12; $m++)
                            @php $val = (float) ($expenseByMonth[$m] ?? 0); @endphp
                            @if ($val > 0)
                                <div class="mb-1 flex items-center gap-2 text-[11px]">
                                    <span class="w-8 text-gray-500">{{ date('M', mktime(0, 0, 0, $m, 1)) }}</span>
                                    <div class="h-3 flex-1 border border-gray-200 bg-white">
                                        <div class="h-full bg-[#e67e22]" style="width: {{ min(100, max(4, ($val / $expenseTotal) * 100)) }}%"></div>
                                    </div>
                                    <span class="w-16 text-right tabular-nums">{{ number_format($val, 0) }}</span>
                                </div>
                            @endif
                        @endfor
                    @else
                        <div class="flex min-h-[120px] items-center justify-center">
                            <p class="text-[12px] text-gray-500">There is no data for this graph.</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            @if ($widgets['customers_owe'] ?? true)
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Customers Who Owe Money</span>
                    <a href="{{ route('payments.create') }}" class="be-widget__meta be-link-btn">Receive Payments</a>
                </div>
                <div class="be-widget__body p-0">
                    <table class="be-table be-table--line-select">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th class="text-right">Amt Due</th>
                            </tr>
                        </thead>
                        <tbody x-data="{ selectedLine: null }">
                            @forelse ($customersWhoOwe as $customer)
                                <tr
                                    @click="selectedLine = 'cust-{{ $customer->id }}'"
                                    :class="selectedLine === 'cust-{{ $customer->id }}' ? 'is-selected' : ''"
                                >
                                    <td>{{ $customer->display_name }}</td>
                                    <td class="num text-red-700">{{ number_format((float) $customer->balance, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2">No open balances.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if ($widgets['top_customers'] ?? true)
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Top Customers by Sales</span>
                    <span class="be-widget__meta">This year</span>
                </div>
                <div class="be-widget__body">
                    @forelse ($topCustomers as $row)
                        @php $sales = (float) $row->sales; @endphp
                        <div class="mb-1.5">
                            <div class="mb-0.5 flex justify-between text-[11px]">
                                <span class="truncate pr-2">{{ $row->customer?->display_name }}</span>
                                <span class="num shrink-0">{{ number_format($sales, 0) }}</span>
                            </div>
                            <div class="h-2.5 border border-gray-200 bg-white">
                                <div class="h-full bg-[#4a90d9]" style="width: {{ min(100, $sales > 0 ? max(4, ($sales / max(1, (float) $topCustomers->max('sales'))) * 100) : 0) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-[11px] text-gray-500">No sales yet.</p>
                    @endforelse
                </div>
            </div>
            @endif

            @if ($widgets['best_sellers'] ?? true)
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Best-Selling Items</span>
                    <span class="be-widget__meta">This year</span>
                </div>
                <div class="be-widget__body">
                    @forelse ($bestSellers as $row)
                        @php $sales = (float) $row->sales; @endphp
                        <div class="mb-1.5">
                            <div class="mb-0.5 flex justify-between text-[11px]">
                                <span class="truncate pr-2">{{ $row->item?->sku }} — {{ \Illuminate\Support\Str::limit($row->item?->name, 28) }}</span>
                                <span class="num shrink-0">{{ number_format($sales, 0) }}</span>
                            </div>
                            <div class="h-2.5 border border-gray-200 bg-white">
                                <div class="h-full bg-[#5cb85c]" style="width: {{ min(100, $sales > 0 ? max(4, ($sales / max(1, (float) $bestSellers->max('sales'))) * 100) : 0) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-[11px] text-gray-500">No item sales yet.</p>
                    @endforelse
                </div>
            </div>
            @endif

            @if ($widgets['pop'] ?? true)
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Prev Year Income Comparison</span>
                    <span class="be-widget__meta">Weekly · this vs last year</span>
                </div>
                <div class="be-widget__body">
                    @php
                        $popMax = max(1, (float) collect($popWeeks)->max('this_year'), (float) collect($popWeeks)->max('last_year'));
                    @endphp
                    @foreach ($popWeeks as $week)
                        <div class="mb-1 flex items-center gap-2 text-[11px]">
                            <span class="w-12 shrink-0 text-gray-500">{{ $week['label'] }}</span>
                            <div class="flex h-3 flex-1 gap-0.5 border border-gray-200 bg-white">
                                <div class="h-full bg-[#5cb85c]" style="width: {{ min(50, $week['this_year'] > 0 ? max(2, ($week['this_year'] / $popMax) * 50) : 0) }}%"></div>
                                <div class="h-full bg-[#9b59b6]" style="width: {{ min(50, $week['last_year'] > 0 ? max(2, ($week['last_year'] / $popMax) * 50) : 0) }}%"></div>
                            </div>
                            <span class="w-24 text-right tabular-nums text-[10px]">{{ number_format($week['this_year'], 0) }}</span>
                        </div>
                    @endforeach
                    <div class="mt-2 flex gap-3 text-[10px] text-gray-500">
                        <span><span class="inline-block h-2 w-2 bg-[#5cb85c]"></span> This year</span>
                        <span><span class="inline-block h-2 w-2 bg-[#9b59b6]"></span> Last year</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    @elseif ($insightsTab === 'payments')
        <div class="be-widget-grid">
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Payment Summary</span>
                    <span class="be-widget__meta">This year</span>
                </div>
                <div class="be-widget__body">
                    <div class="be-kv"><span class="be-kv__label">Payments Received YTD</span><span class="num">{{ number_format($paymentsReceivedYtd, 2) }}</span></div>
                    <div class="be-kv"><span class="be-kv__label">Undeposited Funds</span><span class="num text-red-700">{{ number_format($undepositedTotal, 2) }}</span></div>
                    <div class="be-kv"><span class="be-kv__label">Open AR</span><span class="num">{{ number_format($arBalance, 2) }}</span></div>
                    <div class="mt-3 flex gap-2">
                        <a href="{{ route('payments.create') }}" class="be-btn be-btn--primary">Receive Payments</a>
                        <a href="{{ route('deposits.create') }}" class="be-btn">Make Deposits</a>
                    </div>
                </div>
            </div>

            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Undeposited Payments</span>
                    <a href="{{ route('deposits.create') }}" class="be-widget__meta be-link-btn">Make Deposits</a>
                </div>
                <div class="be-widget__body p-0">
                    <table class="be-table be-table--line-select">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Customer</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody x-data="{ selectedLine: null }">
                            @forelse ($undeposited as $payment)
                                <tr
                                    @click="selectedLine = 'udp-{{ $payment->id }}'"
                                    :class="selectedLine === 'udp-{{ $payment->id }}' ? 'is-selected' : ''"
                                >
                                    <td>{{ $payment->payment_date?->format('m/d/Y') }}</td>
                                    <td>{{ $payment->customer?->display_name }}</td>
                                    <td class="num">{{ number_format((float) $payment->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3">No undeposited payments.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Recent Payments</span>
                    <a href="{{ route('payments.index') }}" class="be-widget__meta be-link-btn">Payment List</a>
                </div>
                <div class="be-widget__body p-0">
                    <table class="be-table be-table--line-select">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Num</th>
                                <th>Customer</th>
                                <th class="text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody x-data="{ selectedLine: null }">
                            @forelse ($recentPayments as $payment)
                                <tr
                                    @click="selectedLine = 'pmt-{{ $payment->id }}'"
                                    :class="selectedLine === 'pmt-{{ $payment->id }}' ? 'is-selected' : ''"
                                >
                                    <td>{{ $payment->payment_date?->format('m/d/Y') }}</td>
                                    <td>{{ $payment->payment_number }}</td>
                                    <td>{{ $payment->customer?->display_name }}</td>
                                    <td class="num">{{ number_format((float) $payment->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4">No payments yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="be-widget-grid">
            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Customers Who Owe Money</span>
                    <a href="{{ route('payments.create') }}" class="be-widget__meta be-link-btn">Receive Payments</a>
                </div>
                <div class="be-widget__body p-0">
                    <table class="be-table be-table--line-select">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th class="text-right">Amt Due</th>
                            </tr>
                        </thead>
                        <tbody x-data="{ selectedLine: null }">
                            @forelse ($customersWhoOwe as $customer)
                                <tr
                                    @click="selectedLine = 'owe-{{ $customer->id }}'"
                                    :class="selectedLine === 'owe-{{ $customer->id }}' ? 'is-selected' : ''"
                                >
                                    <td>
                                        <a href="{{ route('customers.index', ['selectedId' => $customer->id]) }}" class="be-link-btn" @click.stop>
                                            {{ $customer->display_name }}
                                        </a>
                                    </td>
                                    <td class="num text-red-700">{{ number_format((float) $customer->balance, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="2">No open balances.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Top Customers by Sales</span>
                    <span class="be-widget__meta">This year</span>
                </div>
                <div class="be-widget__body">
                    @forelse ($topCustomers as $row)
                        @php $sales = (float) $row->sales; @endphp
                        <div class="mb-1.5">
                            <div class="mb-0.5 flex justify-between text-[11px]">
                                <span class="truncate pr-2">{{ $row->customer?->display_name }}</span>
                                <span class="num shrink-0">{{ number_format($sales, 0) }}</span>
                            </div>
                            <div class="h-2.5 border border-gray-200 bg-white">
                                <div class="h-full bg-[#4a90d9]" style="width: {{ min(100, $sales > 0 ? max(4, ($sales / max(1, (float) $topCustomers->max('sales'))) * 100) : 0) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-[11px] text-gray-500">No sales yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="be-widget">
                <div class="be-widget__header">
                    <span>Open Invoices</span>
                    <a href="{{ route('invoices.index') }}" class="be-widget__meta be-link-btn">Invoice List</a>
                </div>
                <div class="be-widget__body p-0">
                    <table class="be-table be-table--line-select">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Due</th>
                                <th class="text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody x-data="{ selectedLine: null }">
                            @forelse ($openInvoices as $invoice)
                                <tr
                                    @click="selectedLine = 'inv-{{ $invoice->id }}'"
                                    :class="selectedLine === 'inv-{{ $invoice->id }}' ? 'is-selected' : ''"
                                >
                                    <td>{{ $invoice->customer?->display_name }}</td>
                                    <td class="{{ $invoice->due_date && $invoice->due_date->isPast() ? 'text-red-700' : '' }}">
                                        {{ $invoice->due_date?->format('m/d/Y') ?: '—' }}
                                    </td>
                                    <td class="num">{{ number_format((float) $invoice->balance_due, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3">No open invoices.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
