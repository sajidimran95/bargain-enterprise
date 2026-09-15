<div class="be-page">
    <x-erp.toolbar>
        @unless ($started)
            <x-erp.button type="button" variant="primary" wire:click="start">Continue</x-erp.button>
        @else
            <x-erp.button type="button" variant="primary" wire:click="finish">Finish</x-erp.button>
            <x-erp.button type="button" wire:click="resetWorksheet">Leave</x-erp.button>
        @endunless
        <a href="{{ route('banking.index') }}" class="be-btn">Bank Accounts</a>
        <span class="ml-auto text-[11px] text-gray-500">Bank Reconciliation</span>
    </x-erp.toolbar>

    <div class="be-panel m-3">
        <div class="be-panel__header">
            <h1 class="be-panel__title">Reconcile</h1>
        </div>
        <div class="be-panel__body">
            <div class="mb-4 grid gap-3 md:grid-cols-3">
                <div>
                    <label class="be-label">Account *</label>
                    <x-erp.select
                        wire:model="bank_account_id"
                        :options="['' => 'Select…'] + $bankAccounts"
                        :disabled="$started"
                    />
                    @error('bank_account_id') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="be-label">Statement Date *</label>
                    <x-erp.input type="date" wire:model="statement_date" :disabled="$started" />
                    @error('statement_date') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="be-label">Ending Balance *</label>
                    <x-erp.input type="number" step="0.01" wire:model="statement_ending_balance" :disabled="$started" />
                    @error('statement_ending_balance') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            @if ($started)
                <div class="mb-4 grid gap-2 border bg-[#f7f7f7] p-3 text-[12px] md:grid-cols-5" style="border-color: var(--be-border);">
                    <div><span class="text-gray-500">Beginning</span><div class="num font-semibold">{{ number_format((float) $beginningBalance, 2) }}</div></div>
                    <div><span class="text-gray-500">+ Deposits cleared</span><div class="num font-semibold">{{ number_format((float) $clearedDepositsTotal, 2) }}</div></div>
                    <div><span class="text-gray-500">− Checks cleared</span><div class="num font-semibold">{{ number_format((float) $clearedChecksTotal, 2) }}</div></div>
                    <div><span class="text-gray-500">Cleared balance</span><div class="num font-semibold">{{ number_format((float) $clearedBalance, 2) }}</div></div>
                    <div>
                        <span class="text-gray-500">Difference</span>
                        <div class="num font-semibold {{ bccomp($difference, '0', 2) === 0 ? 'text-green-700' : 'text-red-700' }}">
                            {{ number_format((float) $difference, 2) }}
                        </div>
                    </div>
                </div>
                @error('finish') <p class="mb-2 text-xs text-red-600">{{ $message }}</p> @enderror

                <div class="grid gap-4 lg:grid-cols-2">
                    <div>
                        <h2 class="be-section-title">Deposits and Other Credits</h2>
                        <table class="be-table">
                            <thead>
                                <tr>
                                    <th style="width:40px">✓</th>
                                    <th>Date</th>
                                    <th>Num</th>
                                    <th class="text-right">Amount</th>
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
                    <div>
                        <h2 class="be-section-title">Checks and Payments</h2>
                        <table class="be-table">
                            <thead>
                                <tr>
                                    <th style="width:40px">✓</th>
                                    <th>Date</th>
                                    <th>Num</th>
                                    <th>Payee</th>
                                    <th class="text-right">Amount</th>
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
            @endif
        </div>
    </div>
</div>
