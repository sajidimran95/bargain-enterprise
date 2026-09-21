<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <div class="be-doc-toolbar">
        <x-erp.button variant="primary" type="button" wire:click="newMethod">New Method</x-erp.button>
        <x-erp.button type="button" wire:click="focusSearch">Find</x-erp.button>
        <x-erp.workspace-link route="lookups.index" :params="['activeType' => 'payment_methods']" class="be-btn">All Lookups</x-erp.workspace-link>
        <span class="be-doc-toolbar__title">Payment Method List</span>
        <span class="ml-auto text-[11px] text-gray-500">{{ $methods->count() }} method(s) — used on Enter Bills, Pay Bills, Invoices, Receive Payments</span>
    </div>

    <div class="grid gap-3 p-3 lg:grid-cols-2">
        <div class="be-panel be-list-panel">
            <div class="be-panel__body">
                <div class="be-look-for mb-2">
                    <div class="be-field">
                        <label class="be-field__label">Look for</label>
                        <input
                            class="be-input w-56"
                            x-ref="listSearch"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Code / name…"
                        >
                    </div>
                    <label class="inline-flex items-center gap-2 text-[12px]">
                        <input type="checkbox" wire:model.live="includeInactive">
                        Include inactive
                    </label>
                </div>

                <table class="be-table be-table--line-select">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th class="text-right">Sort</th>
                            <th>Active</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($methods as $method)
                            <tr
                                wire:key="pm-{{ $method->id }}"
                                wire:click="selectLine({{ $method->id }})"
                                wire:dblclick="openEdit({{ $method->id }})"
                                class="{{ $selectedLineId === $method->id ? 'is-selected' : '' }} cursor-pointer"
                            >
                                <td><code class="text-[11px]">{{ $method->code }}</code></td>
                                <td>{{ $method->name }}</td>
                                <td class="num">{{ $method->sort_order }}</td>
                                <td>{{ $method->is_active ? 'Yes' : 'No' }}</td>
                                <td class="whitespace-nowrap">
                                    <button type="button" class="be-link-btn" wire:click="openEdit({{ $method->id }})" @click.stop>Edit</button>
                                    <button type="button" class="be-link-btn" wire:click="toggleActive({{ $method->id }})" @click.stop>
                                        {{ $method->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                    <button type="button" class="be-link-btn" wire:click="delete({{ $method->id }})" wire:confirm="Delete this payment method?" @click.stop>Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><x-erp.empty-state title="No payment methods" description="Create Cash, Check, Card, or custom methods here." /></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="be-panel">
            <div class="be-panel__body space-y-2">
                <h2 class="text-[13px] font-semibold">{{ $editingId ? 'Edit Payment Method' : 'New Payment Method' }}</h2>
                <p class="text-[11px] text-gray-600">Active methods appear automatically on Enter Bills, Pay Bills, Invoices, and Receive Payments.</p>

                <div class="be-field">
                    <label class="be-field__label">Code *</label>
                    <x-erp.input wire:model="code" placeholder="venmo" :disabled="(bool) $editingId" />
                    @error('code') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-field">
                    <label class="be-field__label">Name *</label>
                    <x-erp.input wire:model="name" placeholder="Venmo" />
                    @error('name') <span class="be-field__error">{{ $message }}</span> @enderror
                </div>
                <div class="be-field">
                    <label class="be-field__label">Sort order</label>
                    <x-erp.input type="number" wire:model="sort_order" />
                </div>
                <label class="inline-flex items-center gap-2 text-[12px]">
                    <input type="checkbox" wire:model="is_active">
                    Active (show in payment dropdowns)
                </label>

                <div class="flex flex-wrap gap-1 pt-1">
                    <x-erp.button variant="primary" type="button" wire:click="save">{{ $editingId ? 'Update' : 'Create' }}</x-erp.button>
                    <x-erp.button type="button" wire:click="resetForm">Clear</x-erp.button>
                </div>
            </div>
        </div>
    </div>
</div>
