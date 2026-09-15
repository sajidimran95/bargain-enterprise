<div class="be-page">
    <x-erp.toolbar>
        <a href="{{ route('items.index') }}" class="be-btn">Back to Items</a>
        <span class="ml-auto text-[11px] text-gray-500">Categories · Types · Units · Tax · Price Levels</span>
    </x-erp.toolbar>

    <div class="be-panel m-3">
        <div class="be-panel__header">
            <h1 class="be-panel__title">Master Lookups</h1>
        </div>
        <div class="be-panel__body">
            <div class="mb-3 flex flex-wrap gap-1">
                @foreach ([
                    'categories' => 'Categories',
                    'item_types' => 'Item Types',
                    'units' => 'Units',
                    'tax_codes' => 'Tax Codes',
                    'price_levels' => 'Price Levels',
                ] as $key => $label)
                    <button
                        type="button"
                        wire:click="setType('{{ $key }}')"
                        class="be-btn {{ $activeType === $key ? 'be-btn--primary' : '' }}"
                    >{{ $label }}</button>
                @endforeach
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                <div>
                    <table class="be-table be-table--line-select">
                        <thead>
                            <tr>
                                @if (in_array($activeType, ['categories', 'tax_codes']))
                                    <th>Code</th>
                                @endif
                                <th>Name</th>
                                @if ($activeType === 'tax_codes')
                                    <th class="text-right">Rate %</th>
                                @endif
                                @if ($activeType === 'price_levels')
                                    <th class="text-right">Adj %</th>
                                @endif
                                <th>Active</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($records as $record)
                                <tr
                                    wire:key="lookup-{{ $record->id }}"
                                    wire:click="selectLine({{ $record->id }})"
                                    class="{{ $selectedLineId === $record->id ? 'is-selected' : '' }}"
                                >
                                    @if (in_array($activeType, ['categories', 'tax_codes']))
                                        <td>{{ $record->code }}</td>
                                    @endif
                                    <td>{{ $record->label ?? $record->name }}</td>
                                    @if ($activeType === 'tax_codes')
                                        <td class="num">{{ number_format((float) $record->rate, 4) }}</td>
                                    @endif
                                    @if ($activeType === 'price_levels')
                                        <td class="num">{{ number_format((float) $record->adjustment_percent, 4) }}</td>
                                    @endif
                                    <td>{{ $record->is_active ? 'Yes' : 'No' }}</td>
                                    <td class="whitespace-nowrap">
                                        <button type="button" class="be-link-btn" wire:click="edit({{ $record->id }})" @click.stop>Edit</button>
                                        <button type="button" class="be-link-btn" wire:click="toggleActive({{ $record->id }})" @click.stop>{{ $record->is_active ? 'Deactivate' : 'Activate' }}</button>
                                        <button type="button" class="be-link-btn" wire:click="delete({{ $record->id }})" wire:confirm="Delete this lookup?" @click.stop>Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border p-3" style="border-color: var(--be-border);">
                    <h2 class="mb-2 text-[13px] font-semibold">{{ $editingId ? 'Edit' : 'New' }} {{ str_replace('_', ' ', $activeType) }}</h2>

                    @if ($activeType === 'categories')
                        <div class="be-field mb-2"><label class="be-field__label">Code *</label><x-erp.input wire:model="form.code" />@error('form.code') <span class="be-field__error">{{ $message }}</span> @enderror</div>
                        <div class="be-field mb-2"><label class="be-field__label">Name *</label><x-erp.input wire:model="form.name" />@error('form.name') <span class="be-field__error">{{ $message }}</span> @enderror</div>
                        <div class="be-field mb-2"><label class="be-field__label">Description</label><textarea wire:model="form.description" class="be-input" rows="2"></textarea></div>
                    @elseif ($activeType === 'item_types')
                        <div class="be-field mb-2"><label class="be-field__label">Key *</label><x-erp.input wire:model="form.name" />@error('form.name') <span class="be-field__error">{{ $message }}</span> @enderror</div>
                        <div class="be-field mb-2"><label class="be-field__label">Label *</label><x-erp.input wire:model="form.label" /></div>
                        <div class="be-field mb-2"><label class="be-field__label">Description</label><textarea wire:model="form.description" class="be-input" rows="2"></textarea></div>
                    @elseif ($activeType === 'units')
                        <div class="be-field mb-2"><label class="be-field__label">Name *</label><x-erp.input wire:model="form.name" /></div>
                        <div class="be-field mb-2"><label class="be-field__label">Abbreviation</label><x-erp.input wire:model="form.abbreviation" /></div>
                    @elseif ($activeType === 'tax_codes')
                        <div class="be-field mb-2"><label class="be-field__label">Code *</label><x-erp.input wire:model="form.code" /></div>
                        <div class="be-field mb-2"><label class="be-field__label">Name *</label><x-erp.input wire:model="form.name" /></div>
                        <div class="be-field mb-2"><label class="be-field__label">Rate % *</label><x-erp.input wire:model="form.rate" /></div>
                    @else
                        <div class="be-field mb-2"><label class="be-field__label">Name *</label><x-erp.input wire:model="form.name" /></div>
                        <div class="be-field mb-2"><label class="be-field__label">Description</label><x-erp.input wire:model="form.description" /></div>
                        <div class="be-field mb-2"><label class="be-field__label">Adjustment %</label><x-erp.input wire:model="form.adjustment_percent" /></div>
                    @endif

                    <label class="mb-3 inline-flex items-center gap-1 text-[12px]">
                        <input type="checkbox" wire:model="form.is_active"> Active
                    </label>

                    <div class="flex gap-1">
                        <x-erp.button variant="primary" wire:click="save">{{ $editingId ? 'Update' : 'Create' }}</x-erp.button>
                        @if ($editingId)
                            <x-erp.button wire:click="cancelEdit">Cancel Edit</x-erp.button>
                        @else
                            <x-erp.button wire:click="resetForm">Clear</x-erp.button>
                        @endif
                    </div>
                    @error('form') <p class="be-field__error mt-2">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>
</div>
