<div class="be-page" @keydown.window.ctrl.n.prevent="window.location.assign(@js(route('customers.create')))" x-data @be-focus-list-search.window="$refs.listSearch?.focus()">
    <x-erp.toolbar>
        @can('create', App\Models\Customer::class)
            <x-erp.workspace-link route="customers.create" class="be-btn be-btn--primary">New</x-erp.workspace-link>
        @endcan
        <x-erp.button type="button" wire:click="focusSearch">Find</x-erp.button>
        @if ($selected)
            @can('update', $selected)
                <x-erp.workspace-link route="customers.edit" :params="['customer' => $selected]" :title="'Customer: '.$selected->display_name" class="be-btn">Edit</x-erp.workspace-link>
            @endcan
            @can('create', App\Models\Customer::class)
                <x-erp.button wire:click="duplicate">Duplicate</x-erp.button>
            @endcan
            @can('update', $selected)
                @if ($selected->is_active)
                    <x-erp.button wire:click="deactivate" wire:confirm="Deactivate this customer?">Deactivate</x-erp.button>
                @else
                    <x-erp.button wire:click="activate">Activate</x-erp.button>
                @endif
            @endcan
        @endif
        <x-erp.button type="button" onclick="window.print()">Print</x-erp.button>
        <x-erp.button wire:click="exportExcel">Excel</x-erp.button>
        <span class="ml-auto text-[11px] text-gray-500">{{ $customers->total() }} customers</span>
    </x-erp.toolbar>

    <div class="be-split">
        <div class="be-split__list flex flex-col">
            <div class="be-doc-tabs border-b-0 px-2 pt-1" style="background: #d9d9d9;">
                <button type="button" class="{{ $listPane === 'customers' ? 'is-active' : '' }}" wire:click="$set('listPane', 'customers')">Customers &amp; Jobs</button>
                <button type="button" class="{{ $listPane === 'transactions' ? 'is-active' : '' }}" wire:click="$set('listPane', 'transactions')">Transactions</button>
            </div>
            <div class="border-b p-2" style="border-color: var(--be-border);">
                <div class="mb-1">
                    <x-erp.select
                        wire:model.live="status"
                        :options="['active' => 'Active Customers', 'inactive' => 'Inactive Customers', 'all' => 'All Customers']"
                        class="w-full"
                    />
                </div>
                <x-erp.input x-ref="listSearch" wire:model.live.debounce.300ms="search" placeholder="Search customers…" />
                <x-erp.loading-state message="Searching…" />
            </div>

            <div class="flex-1 overflow-auto">
                @if ($listPane === 'customers')
                <table class="be-table be-table--line-select">
                    <thead>
                        <tr>
                            <th wire:click="sortBy('display_name')" class="cursor-pointer">NAME</th>
                            <th wire:click="sortBy('balance')" class="cursor-pointer text-right">BALANCE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr
                                wire:key="customer-{{ $customer->id }}"
                                wire:click="selectCustomer({{ $customer->id }})"
                                class="cursor-pointer {{ $selectedId === $customer->id ? 'is-selected' : '' }}"
                                @dblclick="
                                    if (window.beWorkspace && window.parent !== window) {
                                        window.beWorkspace.open('customers.edit', { customer: {{ $customer->id }} }, @js('Customer: '.$customer->display_name));
                                    } else if (typeof beOpenWorkspace === 'function') {
                                        beOpenWorkspace('customers.edit', { customer: {{ $customer->id }} }, @js('Customer: '.$customer->display_name));
                                    } else {
                                        window.location.assign(@js(route('customers.edit', $customer)));
                                    }
                                "
                            >
                                <td>
                                    @if ($customer->customer_number)
                                        {{ $customer->customer_number }} ({{ $customer->display_name }})
                                    @else
                                        {{ $customer->display_name }}
                                    @endif
                                    @unless ($customer->is_active)
                                        <span class="be-badge">Inactive</span>
                                    @endunless
                                </td>
                                <td class="num">{{ number_format((float) $customer->balance, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">
                                    <x-erp.empty-state title="No customers" message="Click New Customer to create one.">
                                        @can('create', App\Models\Customer::class)
                                            <a href="{{ route('customers.create') }}" class="be-btn be-btn--primary mt-2 inline-flex">New Customer</a>
                                        @endcan
                                    </x-erp.empty-state>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @else
                <table class="be-table be-table--line-select">
                    <thead>
                        <tr>
                            <th>TYPE</th>
                            <th>NUM</th>
                            <th>DATE</th>
                            <th>CUSTOMER</th>
                            <th class="text-right">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTransactions as $txn)
                            <tr
                                wire:key="txn-{{ $txn['type'] }}-{{ $txn['id'] }}"
                                wire:click="selectCustomer({{ $txn['customer_id'] }})"
                                class="cursor-pointer {{ $selectedId === $txn['customer_id'] ? 'is-selected' : '' }}"
                            >
                                <td>{{ $txn['type'] }}</td>
                                <td>{{ $txn['number'] }}</td>
                                <td>{{ $txn['date'] }}</td>
                                <td>{{ $txn['customer'] }}</td>
                                <td class="num">{{ number_format((float) $txn['amount'], 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><x-erp.empty-state title="No transactions" /></td></tr>
                        @endforelse
                    </tbody>
                </table>
                @endif
            </div>
            @if ($listPane === 'customers')
                <x-erp.pagination :paginator="$customers" />
            @endif
        </div>

        <div class="be-split__detail flex flex-col">
            @if ($selected)
                <div class="be-detail-block">
                    <div class="flex items-start justify-between gap-2">
                        <h2 class="be-detail-block__title">Customer Information</h2>
                        <div class="flex gap-1">
                            @can('update', $selected)
                                <x-erp.workspace-link route="customers.edit" :params="['customer' => $selected]" :title="'Customer: '.$selected->display_name" class="be-btn be-btn--primary">Edit Customer</x-erp.workspace-link>
                            @endcan
                        </div>
                    </div>

                    <div class="grid gap-3 md:grid-cols-[1fr_180px]">
                        <div>
                            <div class="be-kv"><span class="be-kv__label">Company Name</span><span>{{ $selected->company_name }}</span></div>
                            <div class="be-kv"><span class="be-kv__label">Full Name</span><span>{{ $selected->fullName() }}</span></div>
                            <div class="be-kv"><span class="be-kv__label">Price Level</span><span>{{ $selected->priceLevel?->name ?? '—' }}</span></div>
                            <div class="be-kv"><span class="be-kv__label">Tax Code</span><span>{{ $selected->taxCode?->code ?? '—' }}</span></div>
                            <div class="be-kv"><span class="be-kv__label">Phone</span><span>{{ $selected->phone ?: '—' }}</span></div>
                            <div class="be-kv"><span class="be-kv__label">Email</span><span>{{ $selected->email ?: '—' }}</span></div>
                            <div class="be-kv">
                                <span class="be-kv__label">Bill To</span>
                                <span>
                                    @forelse ($selected->billToLines() as $line)
                                        {{ $line }}<br>
                                    @empty
                                        —
                                    @endforelse
                                    @if (count($selected->billToLines()))
                                        <a class="be-link-btn" href="https://maps.google.com/?q={{ urlencode(implode(', ', $selected->billToLines())) }}" target="_blank" rel="noopener">Map</a>
                                        |
                                        <a class="be-link-btn" href="https://maps.google.com/maps/dir/?api=1&destination={{ urlencode(implode(', ', $selected->billToLines())) }}" target="_blank" rel="noopener">Directions</a>
                                    @endif
                                </span>
                            </div>

                            @can('update', $selected)
                                <div class="mt-2">
                                    <label class="be-field__label">Pinned Note</label>
                                    <textarea wire:model="pinnedNote" class="be-input" rows="2"></textarea>
                                    <x-erp.button class="mt-1" wire:click="savePinnedNote">Save Note</x-erp.button>
                                </div>
                            @else
                                @if ($selected->pinned_note)
                                    <div class="mt-2 border p-2 text-[12px]" style="border-color: var(--be-border); background:#fffef5;">
                                        <strong>Note:</strong> {{ $selected->pinned_note }}
                                    </div>
                                @endif
                            @endcan
                        </div>
                        <div class="be-side-panel">
                            <div class="be-side-panel__title">Quick Actions</div>
                            <div class="be-side-panel__grid">
                                @can('update', $selected)
                                    <a href="{{ route('customers.edit', $selected) }}">Edit Customer</a>
                                @endcan
                                @can('create', App\Models\Customer::class)
                                    <button type="button" wire:click="duplicate">Duplicate</button>
                                @endcan
                                <a href="{{ route('lookups.index') }}">Price Levels / Tax</a>
                                <a href="{{ route('settings.index') }}">Settings</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex-1">
                    <x-erp.tabs
                        :tabs="[
                            'contacts' => 'Contacts',
                            'todos' => 'To Do’s',
                            'notes' => 'Notes',
                            'transactions' => 'Transactions',
                            'email' => 'Sent Email',
                        ]"
                        :active="$activeTab"
                    >
                        @if ($activeTab === 'contacts')
                            @can('update', $selected)
                                <div class="mb-2 flex flex-wrap gap-1">
                                    <x-erp.input wire:model="contactName" placeholder="Name *" class="max-w-[140px]" />
                                    <x-erp.input wire:model="contactTitle" placeholder="Title" class="max-w-[100px]" />
                                    <x-erp.input wire:model="contactPhone" placeholder="Phone" class="max-w-[110px]" />
                                    <x-erp.input wire:model="contactEmail" placeholder="Email" class="max-w-[160px]" />
                                    <x-erp.button variant="primary" wire:click="addContact">
                                        {{ $editingContactId ? 'Update' : 'Add' }}
                                    </x-erp.button>
                                    @if ($editingContactId)
                                        <x-erp.button wire:click="$set('editingContactId', null)">Cancel</x-erp.button>
                                    @endif
                                </div>
                                @error('contactName') <p class="be-field__error">{{ $message }}</p> @enderror
                                @error('contactEmail') <p class="be-field__error">{{ $message }}</p> @enderror
                            @endcan
                            <table class="be-table">
                                <thead><tr><th>Name</th><th>Title</th><th>Phone</th><th>Email</th><th></th></tr></thead>
                                <tbody>
                                    @forelse ($selected->contacts as $contact)
                                        <tr wire:key="contact-{{ $contact->id }}">
                                            <td>{{ $contact->name }} @if($contact->is_primary)<span class="be-badge">Primary</span>@endif</td>
                                            <td>{{ $contact->title }}</td>
                                            <td>{{ $contact->phone }}</td>
                                            <td>{{ $contact->email }}</td>
                                            <td class="whitespace-nowrap">
                                                @can('update', $selected)
                                                    <button type="button" class="be-link-btn" wire:click="editContact({{ $contact->id }})">Edit</button>
                                                    @unless ($contact->is_primary)
                                                        <button type="button" class="be-link-btn" wire:click="makePrimaryContact({{ $contact->id }})">Primary</button>
                                                    @endunless
                                                    <button type="button" class="be-link-btn" wire:click="deleteContact({{ $contact->id }})" wire:confirm="Delete this contact?">Delete</button>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5">No contacts yet. Add one above.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @elseif ($activeTab === 'todos')
                            @can('update', $selected)
                                <div class="mb-2 flex flex-wrap gap-1">
                                    <x-erp.input wire:model="todoTitle" placeholder="To Do title *" class="max-w-xs" />
                                    <x-erp.date-picker wire:model="todoDueDate" />
                                    <x-erp.button variant="primary" wire:click="addTodo">{{ $editingTodoId ? 'Update' : 'Add' }}</x-erp.button>
                                    @if ($editingTodoId)
                                        <x-erp.button wire:click="$set('editingTodoId', null)">Cancel</x-erp.button>
                                    @endif
                                </div>
                                @error('todoTitle') <p class="be-field__error">{{ $message }}</p> @enderror
                            @endcan
                            <table class="be-table">
                                <thead><tr><th>Title</th><th>Due</th><th>Done</th><th></th></tr></thead>
                                <tbody>
                                    @forelse ($selected->todos as $todo)
                                        <tr wire:key="todo-{{ $todo->id }}">
                                            <td>{{ $todo->title }}</td>
                                            <td>{{ optional($todo->due_date)->format('m/d/Y') }}</td>
                                            <td>
                                                @can('update', $selected)
                                                    <input type="checkbox" @checked($todo->is_completed) wire:click="toggleTodo({{ $todo->id }})">
                                                @else
                                                    {{ $todo->is_completed ? 'Yes' : 'No' }}
                                                @endcan
                                            </td>
                                            <td class="whitespace-nowrap">
                                                @can('update', $selected)
                                                    <button type="button" class="be-link-btn" wire:click="editTodo({{ $todo->id }})">Edit</button>
                                                    <button type="button" class="be-link-btn" wire:click="deleteTodo({{ $todo->id }})" wire:confirm="Delete this to-do?">Delete</button>
                                                @endcan
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4">No to-dos yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @elseif ($activeTab === 'notes')
                            @can('update', $selected)
                                <div class="mb-2 flex gap-1">
                                    <textarea wire:model="noteBody" class="be-input max-w-xl" rows="2" placeholder="Add a note…"></textarea>
                                    <x-erp.button variant="primary" wire:click="addNote">{{ $editingNoteId ? 'Update' : 'Add' }}</x-erp.button>
                                    @if ($editingNoteId)
                                        <x-erp.button wire:click="$set('editingNoteId', null)">Cancel</x-erp.button>
                                    @endif
                                </div>
                                @error('noteBody') <p class="be-field__error">{{ $message }}</p> @enderror
                            @endcan
                            @forelse ($selected->notes as $note)
                                <div wire:key="note-{{ $note->id }}" class="mb-2 border p-2 text-[12px]" style="border-color: var(--be-border);">
                                    <div class="mb-1 flex items-center justify-between text-[11px] text-gray-500">
                                        <span>
                                            {{ $note->created_at?->format('m/d/Y g:i A') }}
                                            @if ($note->user) — {{ $note->user->name }} @endif
                                        </span>
                                        @can('update', $selected)
                                            <span>
                                                <button type="button" class="be-link-btn" wire:click="editNote({{ $note->id }})">Edit</button>
                                                <button type="button" class="be-link-btn" wire:click="deleteNote({{ $note->id }})" wire:confirm="Delete this note?">Delete</button>
                                            </span>
                                        @endcan
                                    </div>
                                    {{ $note->body }}
                                </div>
                            @empty
                                <x-erp.empty-state title="No notes" message="Add notes for this customer above." />
                            @endforelse
                        @elseif ($activeTab === 'transactions')
                            <table class="be-table">
                                <thead>
                                    <tr>
                                        <th>TYPE</th>
                                        <th>NUM</th>
                                        <th>DATE</th>
                                        <th class="text-right">AMOUNT</th>
                                        <th class="text-right">BALANCE</th>
                                        <th>STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($selected->invoices as $invoice)
                                        <tr wire:key="ctx-inv-{{ $invoice->id }}">
                                            <td>Invoice</td>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->invoice_date?->format('m/d/Y') }}</td>
                                            <td class="num">{{ number_format((float) $invoice->total, 2) }}</td>
                                            <td class="num">{{ number_format((float) $invoice->balance_due, 2) }}</td>
                                            <td>{{ $invoice->status }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="6">No invoices for this customer yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        @else
                            <x-erp.empty-state title="No sent email" message="Email history will appear when email delivery is enabled." />
                        @endif
                    </x-erp.tabs>
                </div>
            @else
                <div class="p-6">
                    <x-erp.empty-state title="Select a customer" message="Choose a customer from the list, or create a new one.">
                        @can('create', App\Models\Customer::class)
                            <a href="{{ route('customers.create') }}" class="be-btn be-btn--primary mt-2 inline-flex">New Customer</a>
                        @endcan
                    </x-erp.empty-state>
                </div>
            @endif
        </div>
    </div>
</div>
