<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Bank Accounts" new-route="banking.create" new-label="New Account" :title="$accounts->total().' bank accounts'" />

    <div class="be-panel m-3">
        <div class="be-panel__body">
            <div class="mb-2">
                <x-erp.input x-ref="listSearch" wire:model.live.debounce.300ms="search" placeholder="Search bank / GL account…" class="max-w-sm" />
            </div>

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Bank Account</th>
                        <th>Bank</th>
                        <th>GL Number</th>
                        <th>GL Name</th>
                        <th>Mask</th>
                        <th class="text-right">Opening</th>
                        <th>Active</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($accounts as $bank)
                        <tr
                            wire:key="bank-{{ $bank->id }}"
                            wire:click="selectLine({{ $bank->id }})"
                            class="{{ $selectedLineId === $bank->id ? 'is-selected' : '' }}"
                        >
                            <td>{{ $bank->name }}</td>
                            <td>{{ $bank->bank_name }}</td>
                            <td>{{ $bank->account?->number }}</td>
                            <td>{{ $bank->account?->name }}</td>
                            <td>{{ $bank->account_number_mask }}</td>
                            <td class="num">{{ number_format((float) $bank->opening_balance, 2) }}</td>
                            <td>{{ $bank->is_active ? 'Yes' : 'No' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><x-erp.empty-state title="No bank accounts" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$accounts" />
        </div>
    </div>
</div>
