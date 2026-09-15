<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Journal Entries" :title="$entries->total().' entries'">
        <x-slot:new>
            <x-erp.button type="button" variant="primary" wire:click="newEntry">New</x-erp.button>
        </x-slot:new>
    </x-erp.list-toolbar>

    @if ($showForm)
        <div class="be-panel be-list-panel">
            <div class="be-panel__header">
                <h2 class="be-panel__title">Manual Journal Entry</h2>
            </div>
            <div class="be-panel__body">
                <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label class="be-label">Date</label>
                        <x-erp.input type="date" wire:model="form.entry_date" />
                        @error('form.entry_date') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="be-label">Memo</label>
                        <x-erp.input wire:model="form.memo" placeholder="Optional memo" />
                    </div>
                    <div>
                        <label class="be-label">Debit Account</label>
                        <x-erp.select
                            wire:model="form.debit_account_id"
                            :options="['' => 'Select account…'] + $accounts->mapWithKeys(fn ($a) => [$a->id => $a->number.' — '.$a->name])->all()"
                        />
                        @error('form.debit_account_id') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="be-label">Credit Account</label>
                        <x-erp.select
                            wire:model="form.credit_account_id"
                            :options="['' => 'Select account…'] + $accounts->mapWithKeys(fn ($a) => [$a->id => $a->number.' — '.$a->name])->all()"
                        />
                        @error('form.credit_account_id') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="be-label">Amount</label>
                        <x-erp.input type="number" step="0.01" min="0.01" wire:model="form.amount" />
                        @error('form.amount') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="mt-3 flex gap-2">
                    <x-erp.button type="button" variant="primary" wire:click="saveEntry">Post Entry</x-erp.button>
                    <x-erp.button type="button" wire:click="cancelForm">Cancel</x-erp.button>
                </div>
            </div>
        </div>
    @endif

    <div class="be-panel be-list-panel">
        <div class="be-panel__body">
            <x-erp.look-for placeholder="Entry # / memo…" />

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th></th>
                        <th>Date</th>
                        <th>Number</th>
                        <th>Memo</th>
                        <th class="text-right">Lines</th>
                        <th class="text-right">Debit</th>
                        <th class="text-right">Credit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($entries as $entry)
                        <tr
                            wire:key="je-{{ $entry->id }}"
                            wire:click="selectEntry({{ $entry->id }})"
                            class="{{ $selectedId === $entry->id ? 'is-selected' : '' }}"
                        >
                            <td>
                                <x-erp.button type="button" wire:click="selectEntry({{ $entry->id }})" @click.stop>
                                    {{ $selectedId === $entry->id ? '−' : '+' }}
                                </x-erp.button>
                            </td>
                            <td>{{ $entry->entry_date?->format('m/d/Y') }}</td>
                            <td>{{ $entry->entry_number }}</td>
                            <td>{{ $entry->memo }}</td>
                            <td class="num">{{ $entry->lines_count }}</td>
                            <td class="num">{{ number_format((float) ($entry->debit_total ?? 0), 2) }}</td>
                            <td class="num">{{ number_format((float) ($entry->credit_total ?? 0), 2) }}</td>
                        </tr>
                        @if ($selectedId === $entry->id && $selectedEntry)
                            <tr wire:key="je-lines-{{ $entry->id }}">
                                <td colspan="7" class="bg-gray-50 p-3">
                                    <table class="be-table be-table--compact w-full">
                                        <thead>
                                            <tr>
                                                <th>Account</th>
                                                <th class="text-right">Debit</th>
                                                <th class="text-right">Credit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($selectedEntry->lines as $line)
                                                <tr>
                                                    <td>{{ $line->account?->number }} — {{ $line->account?->name }}</td>
                                                    <td class="num">{{ number_format((float) $line->debit, 2) }}</td>
                                                    <td class="num">{{ number_format((float) $line->credit, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr><td colspan="7"><x-erp.empty-state title="No journal entries" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$entries" />
        </div>
    </div>
</div>
