<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Check List" new-route="checks.create" new-label="Write Checks" :title="$checks->total().' checks'" />

    <div class="be-panel m-3">
        <div class="be-panel__body">
            <div class="mb-2">
                <x-erp.input x-ref="listSearch" wire:model.live.debounce.300ms="search" placeholder="Search check # / payee…" class="max-w-sm" />
            </div>

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Check #</th>
                        <th>Bank Account</th>
                        <th>Payee</th>
                        <th class="text-right">Amount</th>
                        <th>Memo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($checks as $check)
                        <tr
                            wire:key="chk-{{ $check->id }}"
                            wire:click="selectLine({{ $check->id }})"
                            class="{{ $selectedLineId === $check->id ? 'is-selected' : '' }}"
                        >
                            <td>{{ $check->check_date?->format('m/d/Y') }}</td>
                            <td>{{ $check->check_number }}</td>
                            <td>{{ $check->bankAccount?->name }}</td>
                            <td>{{ $check->payee ?: $check->vendor?->display_name }}</td>
                            <td class="num">{{ number_format((float) $check->amount, 2) }}</td>
                            <td>{{ $check->memo }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><x-erp.empty-state title="No checks" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$checks" />
        </div>
    </div>
</div>
