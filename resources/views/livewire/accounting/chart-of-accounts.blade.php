<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Chart of Accounts" :title="$accounts->total().' accounts'">
        <x-slot:new>
            <x-erp.button type="button" variant="primary" wire:click="newAccount">New</x-erp.button>
        </x-slot:new>
    </x-erp.list-toolbar>

    @if ($showForm)
        <div class="be-panel m-3">
            <div class="be-panel__header">
                <h2 class="be-panel__title">{{ $editingId ? 'Edit Account' : 'New Account' }}</h2>
            </div>
            <div class="be-panel__body">
                <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label class="be-label">Number</label>
                        <x-erp.input wire:model="form.number" />
                        @error('form.number') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="be-label">Name</label>
                        <x-erp.input wire:model="form.name" />
                        @error('form.name') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="be-label">Type</label>
                        <x-erp.select
                            wire:model="form.type"
                            :options="[
                                'asset' => 'Asset',
                                'liability' => 'Liability',
                                'equity' => 'Equity',
                                'income' => 'Income',
                                'expense' => 'Expense',
                                'cogs' => 'COGS',
                            ]"
                        />
                        @error('form.type') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="be-label">Subtype</label>
                        <x-erp.input wire:model="form.subtype" placeholder="Optional" />
                        @error('form.subtype') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-end gap-2">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" wire:model="form.is_active" class="rounded border-gray-300">
                            Active
                        </label>
                    </div>
                </div>
                <div class="mt-3 flex gap-2">
                    <x-erp.button type="button" variant="primary" wire:click="saveAccount">Save</x-erp.button>
                    <x-erp.button type="button" wire:click="cancelForm">Cancel</x-erp.button>
                </div>
            </div>
        </div>
    @endif

    <div class="be-panel m-3">
        <div class="be-panel__body">
            <div class="mb-2 flex flex-wrap gap-2">
                <x-erp.input x-ref="listSearch" wire:model.live.debounce.300ms="search" placeholder="Search number / name…" class="max-w-sm" />
                <x-erp.select
                    wire:model.live="typeFilter"
                    :options="[
                        'all' => 'All types',
                        'asset' => 'Asset',
                        'liability' => 'Liability',
                        'equity' => 'Equity',
                        'income' => 'Income',
                        'expense' => 'Expense',
                        'cogs' => 'COGS',
                    ]"
                />
            </div>

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>Number</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Subtype</th>
                        <th>Active</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($accounts as $account)
                        <tr
                            wire:key="acct-{{ $account->id }}"
                            wire:click="selectLine({{ $account->id }})"
                            class="{{ $selectedLineId === $account->id ? 'is-selected' : '' }}"
                        >
                            <td>{{ $account->number }}</td>
                            <td>{{ $account->name }} @if ($account->is_system)<span class="be-badge">System</span>@endif</td>
                            <td>{{ $account->type }}</td>
                            <td>{{ $account->subtype }}</td>
                            <td>{{ $account->is_active ? 'Yes' : 'No' }}</td>
                            <td class="text-right">
                                @unless ($account->is_system)
                                    <x-erp.button type="button" wire:click="editAccount({{ $account->id }})" @click.stop>Edit</x-erp.button>
                                    <x-erp.button type="button" wire:click="toggleActive({{ $account->id }})" @click.stop>
                                        {{ $account->is_active ? 'Deactivate' : 'Activate' }}
                                    </x-erp.button>
                                @endunless
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6"><x-erp.empty-state title="No accounts" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$accounts" />
        </div>
    </div>
</div>
