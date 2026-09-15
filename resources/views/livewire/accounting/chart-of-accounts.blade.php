<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Chart of Accounts" :title="$accounts->total().' accounts'">
        <x-slot:new>
            <x-erp.button type="button" variant="primary" wire:click="newAccount">New</x-erp.button>
        </x-slot:new>
    </x-erp.list-toolbar>

    @if ($showForm)
        <div class="be-entity-dialog be-entity-dialog--inline">
            <div class="be-entity-dialog__header">
                <h2 class="be-entity-dialog__title">{{ $editingId ? 'Edit Account' : 'New Account' }}</h2>
            </div>
            <div class="be-entity-dialog__body">
                <div class="be-form-grid">
                    <div class="be-field">
                        <label class="be-field__label">Number</label>
                        <x-erp.input wire:model="form.number" />
                        @error('form.number') <span class="be-field__error">{{ $message }}</span> @enderror
                    </div>
                    <div class="be-field">
                        <label class="be-field__label">Name</label>
                        <x-erp.input wire:model="form.name" />
                        @error('form.name') <span class="be-field__error">{{ $message }}</span> @enderror
                    </div>
                    <div class="be-field">
                        <label class="be-field__label">Account Type</label>
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
                        @error('form.type') <span class="be-field__error">{{ $message }}</span> @enderror
                    </div>
                    <div class="be-field">
                        <label class="be-field__label">Detail Type / Subtype</label>
                        <x-erp.input wire:model="form.subtype" placeholder="Optional" />
                        @error('form.subtype') <span class="be-field__error">{{ $message }}</span> @enderror
                    </div>
                    <div class="be-field flex items-end">
                        <label class="inline-flex items-center gap-2 text-[12px]">
                            <input type="checkbox" wire:model="form.is_active" class="rounded border-gray-300">
                            Active
                        </label>
                    </div>
                </div>
                <div class="mt-3 flex gap-2">
                    <x-erp.button type="button" variant="primary" wire:click="saveAccount">OK</x-erp.button>
                    <x-erp.button type="button" wire:click="cancelForm">Cancel</x-erp.button>
                </div>
            </div>
        </div>
    @endif

    <div class="be-panel be-list-panel">
        <div class="be-panel__body">
            <x-erp.look-for placeholder="Account # / name…">
                <div class="be-field">
                    <label class="be-field__label">Type</label>
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
            </x-erp.look-for>

            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>NAME</th>
                        <th>TYPE</th>
                        <th>DETAIL TYPE</th>
                        <th>BALANCE</th>
                        <th>STATUS</th>
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
                            <td>
                                <span class="font-mono text-[11px] text-gray-500">{{ $account->number }}</span>
                                {{ $account->name }}
                                @if ($account->is_system)<span class="be-badge">System</span>@endif
                            </td>
                            <td>{{ ucfirst($account->type) }}</td>
                            <td>{{ $account->subtype ?: '—' }}</td>
                            <td class="num text-gray-400">—</td>
                            <td>{{ $account->is_active ? 'Active' : 'Inactive' }}</td>
                            <td class="text-right whitespace-nowrap">
                                @unless ($account->is_system)
                                    <button type="button" class="be-link-btn" wire:click="editAccount({{ $account->id }})" @click.stop>Edit</button>
                                    <button type="button" class="be-link-btn" wire:click="toggleActive({{ $account->id }})" @click.stop>
                                        {{ $account->is_active ? 'Make Inactive' : 'Make Active' }}
                                    </button>
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
