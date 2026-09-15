<div class="be-page" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.toolbar>
        @can('create', App\Models\Vendor::class)
            <a href="{{ route('vendors.create') }}" class="be-btn be-btn--primary">New</a>
        @endcan
        <x-erp.button type="button" wire:click="focusSearch">Find</x-erp.button>
        @if ($selected)
            @can('update', $selected)
                <a href="{{ route('vendors.edit', $selected) }}" class="be-btn">Edit</a>
                @if ($selected->is_active)
                    <x-erp.button wire:click="deactivate" wire:confirm="Deactivate this vendor?">Deactivate</x-erp.button>
                @else
                    <x-erp.button wire:click="activate">Activate</x-erp.button>
                @endif
            @endcan
            @can('create', App\Models\Vendor::class)
                <x-erp.button wire:click="duplicate">Duplicate</x-erp.button>
            @endcan
        @endif
        <x-erp.button type="button" onclick="window.print()">Print</x-erp.button>
        <x-erp.button wire:click="exportExcel">Excel</x-erp.button>
        <span class="ml-auto text-[11px] text-gray-500">{{ $vendors->total() }} vendors</span>
    </x-erp.toolbar>

    <div class="be-split">
        <div class="be-split__list flex flex-col">
            <div class="border-b p-2" style="border-color: var(--be-border);">
                <x-erp.select
                    wire:model.live="status"
                    :options="['active' => 'Active Vendors', 'inactive' => 'Inactive Vendors', 'all' => 'All Vendors']"
                    class="mb-1 w-full"
                />
                <x-erp.input x-ref="listSearch" wire:model.live.debounce.300ms="search" placeholder="Search vendors…" />
            </div>
            <div class="flex-1 overflow-auto">
                <table class="be-table be-table--line-select">
                    <thead>
                        <tr>
                            <th>NAME</th>
                            <th class="text-right">BALANCE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vendors as $vendor)
                            <tr
                                wire:key="vendor-{{ $vendor->id }}"
                                wire:click="selectVendor({{ $vendor->id }})"
                                class="cursor-pointer {{ $selectedId === $vendor->id ? 'is-selected' : '' }}"
                                @dblclick="window.location.assign(@js(route('vendors.edit', $vendor)))"
                            >
                                <td>
                                    {{ $vendor->display_name }}
                                    @unless ($vendor->is_active)
                                        <span class="be-badge">Inactive</span>
                                    @endunless
                                </td>
                                <td class="num">{{ number_format((float) $vendor->balance, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2">
                                <x-erp.empty-state title="No vendors" message="Click New Vendor to create one.">
                                    @can('create', App\Models\Vendor::class)
                                        <a href="{{ route('vendors.create') }}" class="be-btn be-btn--primary mt-2 inline-flex">New Vendor</a>
                                    @endcan
                                </x-erp.empty-state>
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <x-erp.pagination :paginator="$vendors" />
        </div>

        <div class="be-split__detail">
            @if ($selected)
                <div class="be-detail-block">
                    <div class="flex items-start justify-between">
                        <h2 class="be-detail-block__title">Vendor Information</h2>
                        @can('update', $selected)
                            <a href="{{ route('vendors.edit', $selected) }}" class="be-btn be-btn--primary">Edit Vendor</a>
                        @endcan
                    </div>
                    <div class="be-kv"><span class="be-kv__label">Company</span><span>{{ $selected->company_name }}</span></div>
                    <div class="be-kv"><span class="be-kv__label">Contact</span><span>{{ $selected->fullName() }}</span></div>
                    <div class="be-kv"><span class="be-kv__label">Phone</span><span>{{ $selected->phone ?: '—' }}</span></div>
                    <div class="be-kv"><span class="be-kv__label">Email</span><span>{{ $selected->email ?: '—' }}</span></div>
                    <div class="be-kv"><span class="be-kv__label">Account #</span><span>{{ $selected->account_number ?: '—' }}</span></div>
                    <div class="be-kv"><span class="be-kv__label">Terms</span><span>{{ $selected->terms ?: '—' }}</span></div>
                </div>

                <x-erp.tabs
                    :tabs="['contacts' => 'Contacts', 'notes' => 'Notes', 'history' => 'Purchase History']"
                    :active="$activeTab"
                >
                    @if ($activeTab === 'contacts')
                        @can('update', $selected)
                            <div class="mb-2 flex flex-wrap gap-1">
                                <x-erp.input wire:model="contactName" placeholder="Name *" class="max-w-[140px]" />
                                <x-erp.input wire:model="contactTitle" placeholder="Title" class="max-w-[100px]" />
                                <x-erp.input wire:model="contactPhone" placeholder="Phone" class="max-w-[110px]" />
                                <x-erp.input wire:model="contactEmail" placeholder="Email" class="max-w-[160px]" />
                                <x-erp.button variant="primary" wire:click="addContact">{{ $editingContactId ? 'Update' : 'Add' }}</x-erp.button>
                            </div>
                            @error('contactName') <p class="be-field__error">{{ $message }}</p> @enderror
                        @endcan
                        <table class="be-table">
                            <thead><tr><th>Name</th><th>Phone</th><th>Email</th><th></th></tr></thead>
                            <tbody>
                                @forelse ($selected->contacts as $contact)
                                    <tr wire:key="vc-{{ $contact->id }}">
                                        <td>{{ $contact->name }}</td>
                                        <td>{{ $contact->phone }}</td>
                                        <td>{{ $contact->email }}</td>
                                        <td class="whitespace-nowrap">
                                            @can('update', $selected)
                                                <button type="button" class="be-link-btn" wire:click="editContact({{ $contact->id }})">Edit</button>
                                                <button type="button" class="be-link-btn" wire:click="deleteContact({{ $contact->id }})" wire:confirm="Delete contact?">Delete</button>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4">No contacts yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    @elseif ($activeTab === 'notes')
                        @can('update', $selected)
                            <div class="mb-2 flex gap-1">
                                <textarea wire:model="noteBody" class="be-input max-w-xl" rows="2"></textarea>
                                <x-erp.button variant="primary" wire:click="addNote">{{ $editingNoteId ? 'Update' : 'Add' }}</x-erp.button>
                            </div>
                        @endcan
                        @forelse ($selected->notesRelation as $note)
                            <div class="mb-2 border p-2 text-[12px]" style="border-color: var(--be-border);">
                                <div class="mb-1 flex justify-between text-[11px] text-gray-500">
                                    <span>{{ $note->created_at?->format('m/d/Y g:i A') }}</span>
                                    @can('update', $selected)
                                        <span>
                                            <button type="button" class="be-link-btn" wire:click="editNote({{ $note->id }})">Edit</button>
                                            <button type="button" class="be-link-btn" wire:click="deleteNote({{ $note->id }})" wire:confirm="Delete note?">Delete</button>
                                        </span>
                                    @endcan
                                </div>
                                {{ $note->body }}
                            </div>
                        @empty
                            <x-erp.empty-state title="No notes" />
                        @endforelse
                    @else
                        <x-erp.empty-state title="No purchase history yet" message="Bills and payments will appear when Purchasing is built. Vendor master data is fully editable now." />
                    @endif
                </x-erp.tabs>
            @else
                <div class="p-6"><x-erp.empty-state title="Select a vendor" /></div>
            @endif
        </div>
    </div>
</div>
