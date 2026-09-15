<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.list-toolbar heading="Inventory Adjustments" :title="$transactions->total().' adjustments'">
        <x-slot:new>
            <x-erp.button type="button" variant="primary" wire:click="newAdjustment">New</x-erp.button>
        </x-slot:new>
    </x-erp.list-toolbar>

    @if ($showForm)
        <div class="be-panel be-list-panel">
            <div class="be-panel__header">
                <h2 class="be-panel__title">New Inventory Adjustment</h2>
            </div>
            <div class="be-panel__body">
                <div class="grid gap-3 md:grid-cols-2 lg:grid-cols-3">
                    <div class="md:col-span-2">
                        <label class="be-label">Item</label>
                        <x-erp.select wire:model="form.item_id" :options="['' => 'Select item…'] + $itemOptions" />
                        @error('form.item_id') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="be-label">Direction</label>
                        <x-erp.select
                            wire:model="form.direction"
                            :options="['in' => 'Increase (qty in)', 'out' => 'Decrease (qty out)']"
                        />
                    </div>
                    <div>
                        <label class="be-label">Quantity</label>
                        <x-erp.input type="number" step="0.0001" min="0.0001" wire:model="form.qty" />
                        @error('form.qty') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="be-label">Unit Cost (for increases)</label>
                        <x-erp.input type="number" step="0.01" min="0" wire:model="form.unit_cost" placeholder="Uses avg cost if blank" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="be-label">Memo</label>
                        <x-erp.input wire:model="form.memo" placeholder="Optional" />
                    </div>
                </div>
                <div class="mt-3 flex gap-2">
                    <x-erp.button type="button" variant="primary" wire:click="saveAdjustment">Post Adjustment</x-erp.button>
                    <x-erp.button type="button" wire:click="cancelForm">Cancel</x-erp.button>
                </div>
            </div>
        </div>
    @endif

    <div class="be-panel be-list-panel">
        <div class="be-panel__body">
            <x-erp.look-for placeholder="SKU / memo…" />
            <table class="be-table be-table--line-select">
                <thead>
                    <tr>
                        <th>When</th>
                        <th>SKU</th>
                        <th>Item</th>
                        <th class="text-right">Qty In</th>
                        <th class="text-right">Qty Out</th>
                        <th class="text-right">Balance</th>
                        <th>Memo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $tx)
                        <tr
                            wire:key="adj-{{ $tx->id }}"
                            wire:click="selectLine({{ $tx->id }})"
                            class="{{ $selectedLineId === $tx->id ? 'is-selected' : '' }}"
                        >
                            <td>{{ $tx->occurred_at?->format('m/d/Y H:i') }}</td>
                            <td>{{ $tx->item?->sku }}</td>
                            <td>{{ $tx->item?->name }}</td>
                            <td class="num">{{ number_format((float) $tx->qty_in, 4) }}</td>
                            <td class="num">{{ number_format((float) $tx->qty_out, 4) }}</td>
                            <td class="num">{{ number_format((float) $tx->balance_after, 4) }}</td>
                            <td>{{ $tx->memo }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7"><x-erp.empty-state title="No adjustments yet" /></td></tr>
                    @endforelse
                </tbody>
            </table>
            <x-erp.pagination :paginator="$transactions" />
        </div>
    </div>
</div>
