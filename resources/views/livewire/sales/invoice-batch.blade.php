<div class="be-page">
    <x-erp.toolbar>
        <x-erp.workspace-link route="invoices.create" class="be-btn">Create Invoices</x-erp.workspace-link>
        <x-erp.workspace-link route="invoices.index" class="be-btn">Invoice List</x-erp.workspace-link>
        <span class="ml-auto text-[11px] text-gray-600">
            Create a Batch = invoices marked Print Later / Email Later (like QB Print Forms)
        </span>
    </x-erp.toolbar>

    <div class="be-panel m-3">
        <div class="be-panel__header flex flex-wrap items-center gap-2">
            <h1 class="be-panel__title">Select Invoices to Print / Email</h1>
            <div class="ml-auto flex flex-wrap gap-1">
                <button type="button" class="be-btn {{ $queue === 'print' ? 'be-btn--primary' : '' }}" wire:click="$set('queue', 'print')">Print Later</button>
                <button type="button" class="be-btn {{ $queue === 'email' ? 'be-btn--primary' : '' }}" wire:click="$set('queue', 'email')">Email Later</button>
                <button type="button" class="be-btn {{ $queue === 'both' ? 'be-btn--primary' : '' }}" wire:click="$set('queue', 'both')">Both</button>
            </div>
        </div>
        <div class="be-panel__body">
            <div class="mb-3 flex flex-wrap gap-1">
                <x-erp.button variant="primary" wire:click="printSelected">Print Selected ({{ $selectedCount }})</x-erp.button>
                <x-erp.button wire:click="emailSelected">Email Selected</x-erp.button>
                <x-erp.button wire:click="selectAllVisible">Select All</x-erp.button>
                <x-erp.button wire:click="clearSelection">Clear Selection</x-erp.button>
            </div>

            <table class="be-table">
                <thead>
                    <tr>
                        <th style="width:2rem;"></th>
                        <th>Date</th>
                        <th>Num</th>
                        <th>Customer</th>
                        <th>Queue</th>
                        <th class="text-right">Amount</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($invoices as $invoice)
                        <tr wire:key="batch-inv-{{ $invoice->id }}">
                            <td>
                                <input type="checkbox" wire:model.live="selected.{{ $invoice->id }}">
                            </td>
                            <td>{{ $invoice->invoice_date?->format('m/d/Y') }}</td>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->customer?->display_name }}</td>
                            <td class="text-[11px]">
                                @if ($invoice->print_later)<span class="be-badge">Print</span>@endif
                                @if ($invoice->email_later)<span class="be-badge">Email</span>@endif
                            </td>
                            <td class="num">{{ number_format((float) $invoice->total, 2) }}</td>
                            <td class="whitespace-nowrap">
                                <a href="{{ route('invoices.pdf', $invoice) }}" class="be-link-btn" target="_blank">PDF</a>
                                <button type="button" class="be-link-btn" wire:click="removeFromQueue({{ $invoice->id }})">Remove</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                No invoices in this queue. On Create Invoices, check <strong>Print Later</strong> or <strong>Email Later</strong>, Save, then open Create a Batch.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
