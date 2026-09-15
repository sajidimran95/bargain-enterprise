<div class="be-page be-invoice-page">
    <div class="be-doc-toolbar">
        @unless ($started)
            <x-erp.button type="button" variant="primary" wire:click="start">Continue</x-erp.button>
        @else
            <x-erp.button type="button" variant="primary" wire:click="finish">Reconcile Now</x-erp.button>
            <x-erp.button type="button" wire:click="resetWorksheet">Leave</x-erp.button>
        @endunless
        <x-erp.workspace-link route="banking.index" class="be-btn">Bank Accounts</x-erp.workspace-link>
        <span class="be-doc-toolbar__title">Reconcile</span>
    </div>

    <div class="be-invoice-layout be-invoice-layout--inspector-collapsed">
        <div class="be-invoice-main">
            <div class="be-invoice-doc">
                <div class="be-invoice-midrow">
                    <div class="be-invoice-midrow__left">
                        <h1 class="be-invoice-title">Reconcile</h1>
                    </div>
                    <div class="be-invoice-midrow__right">
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Account</label>
                            <x-erp.select
                                wire:model="bank_account_id"
                                class="be-input--combo"
                                :options="['' => 'Select…'] + $bankAccounts"
                                :disabled="$started"
                            />
                            @error('bank_account_id') <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Statement Date</label>
                            <x-erp.input type="date" wire:model="statement_date" class="be-input--combo" :disabled="$started" />
                            @error('statement_date') <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                        <div class="be-field be-field--inline">
                            <label class="be-field__label be-field__label--caps">Ending Balance</label>
                            <x-erp.input type="number" step="0.01" wire:model="statement_ending_balance" class="be-input--combo" :disabled="$started" />
                            @error('statement_ending_balance') <span class="be-field__error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                @if ($started)
                    <div class="be-reconcile-summary">
                        <div><span class="be-reconcile-summary__label">Beginning Balance</span><div class="num font-semibold">{{ number_format((float) $beginningBalance, 2) }}</div></div>
                        <div><span class="be-reconcile-summary__label">+ Deposits cleared</span><div class="num font-semibold">{{ number_format((float) $clearedDepositsTotal, 2) }}</div></div>
                        <div><span class="be-reconcile-summary__label">− Checks cleared</span><div class="num font-semibold">{{ number_format((float) $clearedChecksTotal, 2) }}</div></div>
                        <div><span class="be-reconcile-summary__label">Cleared Balance</span><div class="num font-semibold">{{ number_format((float) $clearedBalance, 2) }}</div></div>
                        <div>
                            <span class="be-reconcile-summary__label">Difference</span>
                            <div class="num font-semibold {{ bccomp($difference, '0', 2) === 0 ? 'text-green-700' : 'text-red-700' }}">
                                {{ number_format((float) $difference, 2) }}
                            </div>
                        </div>
                    </div>
                    @error('finish') <p class="be-invoice-error">{{ $message }}</p> @enderror

                    <div class="grid gap-4 lg:grid-cols-2">
                        <div>
                            <h2 class="be-section-title">Deposits and Other Credits</h2>
                            <div class="be-invoice-grid-wrap">
                                <table class="be-table be-invoice-grid be-table--line-select">
                                    <thead>
                                        <tr>
                                            <th style="width:40px">✓</th>
                                            <th>DATE</th>
                                            <th>NUM</th>
                                            <th class="text-right">AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($deposits as $deposit)
                                            <tr wire:key="dep-{{ $deposit->id }}">
                                                <td><input type="checkbox" wire:model.live="clearedDeposits.{{ $deposit->id }}"></td>
                                                <td>{{ $deposit->deposit_date?->format('m/d/Y') }}</td>
                                                <td>{{ $deposit->number }}</td>
                                                <td class="num">{{ number_format((float) $deposit->total, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="4">No uncleared deposits.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div>
                            <h2 class="be-section-title">Checks and Payments</h2>
                            <div class="be-invoice-grid-wrap">
                                <table class="be-table be-invoice-grid be-table--line-select">
                                    <thead>
                                        <tr>
                                            <th style="width:40px">✓</th>
                                            <th>DATE</th>
                                            <th>NUM</th>
                                            <th>PAYEE</th>
                                            <th class="text-right">AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($checks as $check)
                                            <tr wire:key="chk-{{ $check->id }}">
                                                <td><input type="checkbox" wire:model.live="clearedChecks.{{ $check->id }}"></td>
                                                <td>{{ $check->check_date?->format('m/d/Y') }}</td>
                                                <td>{{ $check->check_number }}</td>
                                                <td>{{ $check->payee }}</td>
                                                <td class="num">{{ number_format((float) $check->amount, 2) }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5">No uncleared checks.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
