<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Deposit List" new-route="deposits.create" new-label="Record Deposit" :title="$deposits->total().' deposits'" />

    <div class="be-panel m-3">
        <div class="be-panel__body">
            <div class="mb-2">
                <x-erp.input x-ref="listSearch" wire:model.live.debounce.300ms="search" placeholder="Search deposit # / bank…" class="max-w-sm" />
            </div>

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Number</th>
                        <th>Bank Account</th>
                        <th class="text-right">Items</th>
                        <th class="text-right">Total</th>
                        <th>Memo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($deposits as $deposit)
                        <tr
                            wire:key="dep-{{ $deposit->id }}"
                            wire:click="selectLine({{ $deposit->id }})"
                            class="{{ $selectedLineId === $deposit->id ? 'is-selected' : '' }}"
                        >
                            <td>{{ $deposit->deposit_date?->format('m/d/Y') }}</td>
                            <td>{{ $deposit->number }}</td>
                            <td>{{ $deposit->bankAccount?->name }}</td>
                            <td class="num">{{ $deposit->items_count }}</td>
                            <td class="num">{{ number_format((float) $deposit->total, 2) }}</td>
                            <td>{{ $deposit->memo }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><x-erp.empty-state title="No deposits" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$deposits" />
        </div>
    </div>
</div>
