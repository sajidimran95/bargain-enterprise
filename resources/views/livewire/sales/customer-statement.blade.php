<div class="be-page be-invoice-page">
    <div class="be-doc-toolbar">
        <x-erp.button type="button" onclick="window.print()">Print</x-erp.button>
        <x-erp.button type="button" wire:click="exportExcel">Excel</x-erp.button>
        <x-erp.workspace-link route="customers.index" class="be-btn">Customer Center</x-erp.workspace-link>
        <span class="be-doc-toolbar__title">Create Statements</span>
    </div>

    <div class="be-invoice-layout be-invoice-layout--inspector-collapsed">
        <div class="be-invoice-main">
            <div class="be-invoice-doc">
                <div class="be-invoice-toprow">
                    <div class="be-field be-field--grow">
                        <label class="be-field__label be-field__label--caps be-field__label--on-blue">Customer</label>
                        <x-erp.select wire:model.live="customer_id" class="be-input--combo be-input--customer" :options="['' => 'Select customer…'] + $customers" />
                    </div>
                </div>

                <div class="be-invoice-midrow">
                    <div class="be-invoice-midrow__left">
                        <h1 class="be-invoice-title">Statement</h1>
                    </div>
                    <div class="be-invoice-midrow__right">
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Dates</label>
                            <x-erp.select wire:model.live="datePreset" class="be-input--combo" :options="$datePresetOptions" />
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">From</label>
                            <x-erp.input type="date" wire:model.live="from" class="be-input--combo" />
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">To</label>
                            <x-erp.input type="date" wire:model.live="to" class="be-input--combo" />
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Stmt Date</label>
                            <x-erp.input type="date" wire:model="statement_date" class="be-input--combo" />
                        </div>
                    </div>
                </div>

                @if ($customer)
                    <div class="be-statement-meta">
                        <div>
                            <strong>{{ $customer->display_name }}</strong><br>
                            {!! nl2br(e($customer->formattedBillingAddress() ?: '')) !!}
                        </div>
                        <div class="text-right">
                            <div class="text-[11px] text-gray-500">Statement Date</div>
                            <div class="font-semibold">{{ \Carbon\Carbon::parse($statement_date)->format('m/d/Y') }}</div>
                            <div class="mt-2 text-[11px] text-gray-500">Amount Due</div>
                            <div class="text-[18px] font-semibold num">{{ number_format((float) $endingBalance, 2) }}</div>
                        </div>
                    </div>
                @endif

                <div class="be-invoice-grid-wrap">
                    <table class="be-table be-invoice-grid be-table--line-select">
                        <thead>
                            <tr>
                                <th>DATE</th>
                                <th>TYPE</th>
                                <th>NUM</th>
                                <th>MEMO</th>
                                <th class="text-right">AMOUNT</th>
                                <th class="text-right">BALANCE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lines as $row)
                                <tr>
                                    <td>{{ $row['date'] }}</td>
                                    <td>{{ $row['type'] }}</td>
                                    <td>{{ $row['num'] }}</td>
                                    <td>{{ $row['memo'] }}</td>
                                    <td class="num">{{ number_format($row['amount'], 2) }}</td>
                                    <td class="num">{{ number_format($row['balance'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">{{ $customer_id === '' ? 'Select a customer to preview the statement.' : 'No activity in this date range.' }}</td>
                                </tr>
                            @endforelse
                            @if ($lines->isNotEmpty())
                                <tr class="be-report-table__total">
                                    <td colspan="5" class="text-right">Amount Due</td>
                                    <td class="num">{{ number_format((float) $endingBalance, 2) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
