<?php

namespace App\Livewire\Customers;

use App\Actions\Customers\ActivateCustomerAction;
use App\Actions\Customers\DeactivateCustomerAction;
use App\Actions\Customers\DuplicateCustomerAction;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\CustomerNote;
use App\Models\CustomerTodo;
use App\Models\Invoice;
use App\Models\Payment;
use App\Support\XlsxExporter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Customer Center')]
class CustomerCenter extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = 'active';

    #[Url]
    public ?int $selectedId = null;

    public string $activeTab = 'contacts';

    public string $listPane = 'customers';

    public string $sortField = 'display_name';

    public function focusSearch(): void
    {
        $this->dispatch('be-focus-list-search');
    }

    public string $sortDirection = 'asc';

    public string $noteBody = '';

    public ?int $editingNoteId = null;

    public string $todoTitle = '';

    public ?string $todoDueDate = null;

    public ?int $editingTodoId = null;

    public string $contactName = '';

    public string $contactPhone = '';

    public string $contactEmail = '';

    public string $contactTitle = '';

    public ?int $editingContactId = null;

    public string $pinnedNote = '';

    public function mount(?Customer $customer = null): void
    {
        $this->authorize('viewAny', Customer::class);

        if ($customer?->exists) {
            $this->selectedId = $customer->id;
            $this->pinnedNote = (string) ($customer->pinned_note ?? '');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function selectCustomer(int $id): void
    {
        $this->selectedId = $id;
        $this->activeTab = 'contacts';
        $this->resetEditorState();
        $customer = $this->selectedCustomer();
        $this->pinnedNote = (string) ($customer?->pinned_note ?? '');
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function deactivate(DeactivateCustomerAction $action): void
    {
        $customer = $this->requireSelected();
        $this->authorize('delete', $customer);
        $action->handle($customer);
        $this->dispatch('be-toast', message: 'Customer deactivated.');
    }

    public function activate(ActivateCustomerAction $action): void
    {
        $customer = $this->requireSelected();
        $this->authorize('update', $customer);
        $action->handle($customer);
        $this->dispatch('be-toast', message: 'Customer activated.');
    }

    public function duplicate(DuplicateCustomerAction $action): mixed
    {
        $customer = $this->requireSelected();
        $this->authorize('create', Customer::class);
        $copy = $action->handle($customer);

        return redirect()->route('customers.edit', $copy);
    }

    public function savePinnedNote(): void
    {
        $customer = $this->requireSelected();
        $this->authorize('update', $customer);
        $customer->update(['pinned_note' => $this->pinnedNote ?: null]);
        $this->dispatch('be-toast', message: 'Pinned note saved.');
    }

    public function addNote(): void
    {
        $customer = $this->requireSelected();
        $this->authorize('update', $customer);
        $this->validate(['noteBody' => ['required', 'string', 'max:5000']]);

        if ($this->editingNoteId) {
            CustomerNote::query()
                ->where('customer_id', $customer->id)
                ->whereKey($this->editingNoteId)
                ->firstOrFail()
                ->update(['body' => $this->noteBody]);
        } else {
            CustomerNote::query()->create([
                'customer_id' => $customer->id,
                'user_id' => auth()->id(),
                'body' => $this->noteBody,
            ]);
        }

        $this->noteBody = '';
        $this->editingNoteId = null;
        $this->activeTab = 'notes';
        $this->dispatch('be-toast', message: 'Note saved.');
    }

    public function editNote(int $id): void
    {
        $note = CustomerNote::query()->where('customer_id', $this->selectedId)->findOrFail($id);
        $this->editingNoteId = $note->id;
        $this->noteBody = $note->body;
        $this->activeTab = 'notes';
    }

    public function deleteNote(int $id): void
    {
        $customer = $this->requireSelected();
        $this->authorize('update', $customer);
        CustomerNote::query()->where('customer_id', $customer->id)->whereKey($id)->delete();
        if ($this->editingNoteId === $id) {
            $this->editingNoteId = null;
            $this->noteBody = '';
        }
        $this->dispatch('be-toast', message: 'Note deleted.');
    }

    public function addTodo(): void
    {
        $customer = $this->requireSelected();
        $this->authorize('update', $customer);
        $this->validate([
            'todoTitle' => ['required', 'string', 'max:255'],
            'todoDueDate' => ['nullable', 'date'],
        ]);

        $payload = [
            'title' => $this->todoTitle,
            'due_date' => $this->todoDueDate ?: null,
            'assigned_to' => auth()->id(),
        ];

        if ($this->editingTodoId) {
            CustomerTodo::query()
                ->where('customer_id', $customer->id)
                ->whereKey($this->editingTodoId)
                ->firstOrFail()
                ->update($payload);
        } else {
            CustomerTodo::query()->create($payload + [
                'customer_id' => $customer->id,
                'is_completed' => false,
            ]);
        }

        $this->todoTitle = '';
        $this->todoDueDate = null;
        $this->editingTodoId = null;
        $this->activeTab = 'todos';
        $this->dispatch('be-toast', message: 'To Do saved.');
    }

    public function editTodo(int $id): void
    {
        $todo = CustomerTodo::query()->where('customer_id', $this->selectedId)->findOrFail($id);
        $this->editingTodoId = $todo->id;
        $this->todoTitle = $todo->title;
        $this->todoDueDate = optional($todo->due_date)?->format('Y-m-d');
        $this->activeTab = 'todos';
    }

    public function toggleTodo(int $id): void
    {
        $customer = $this->requireSelected();
        $this->authorize('update', $customer);
        $todo = CustomerTodo::query()->where('customer_id', $customer->id)->findOrFail($id);
        $todo->update(['is_completed' => ! $todo->is_completed]);
    }

    public function deleteTodo(int $id): void
    {
        $customer = $this->requireSelected();
        $this->authorize('update', $customer);
        CustomerTodo::query()->where('customer_id', $customer->id)->whereKey($id)->delete();
        if ($this->editingTodoId === $id) {
            $this->editingTodoId = null;
            $this->todoTitle = '';
            $this->todoDueDate = null;
        }
        $this->dispatch('be-toast', message: 'To Do deleted.');
    }

    public function addContact(): void
    {
        $customer = $this->requireSelected();
        $this->authorize('update', $customer);
        $this->validate([
            'contactName' => ['required', 'string', 'max:255'],
            'contactTitle' => ['nullable', 'string', 'max:100'],
            'contactPhone' => ['nullable', 'string', 'max:50'],
            'contactEmail' => ['nullable', 'email', 'max:255'],
        ]);

        $payload = [
            'name' => $this->contactName,
            'title' => $this->contactTitle ?: null,
            'phone' => $this->contactPhone ?: null,
            'email' => $this->contactEmail ?: null,
        ];

        if ($this->editingContactId) {
            CustomerContact::query()
                ->where('customer_id', $customer->id)
                ->whereKey($this->editingContactId)
                ->firstOrFail()
                ->update($payload);
        } else {
            CustomerContact::query()->create($payload + [
                'customer_id' => $customer->id,
                'is_primary' => $customer->contacts()->count() === 0,
            ]);
        }

        $this->reset(['contactName', 'contactPhone', 'contactEmail', 'contactTitle', 'editingContactId']);
        $this->activeTab = 'contacts';
        $this->dispatch('be-toast', message: 'Contact saved.');
    }

    public function editContact(int $id): void
    {
        $contact = CustomerContact::query()->where('customer_id', $this->selectedId)->findOrFail($id);
        $this->editingContactId = $contact->id;
        $this->contactName = $contact->name;
        $this->contactTitle = (string) ($contact->title ?? '');
        $this->contactPhone = (string) ($contact->phone ?? '');
        $this->contactEmail = (string) ($contact->email ?? '');
        $this->activeTab = 'contacts';
    }

    public function makePrimaryContact(int $id): void
    {
        $customer = $this->requireSelected();
        $this->authorize('update', $customer);
        $customer->contacts()->update(['is_primary' => false]);
        CustomerContact::query()->where('customer_id', $customer->id)->whereKey($id)->update(['is_primary' => true]);
        $this->dispatch('be-toast', message: 'Primary contact updated.');
    }

    public function deleteContact(int $id): void
    {
        $customer = $this->requireSelected();
        $this->authorize('update', $customer);
        CustomerContact::query()->where('customer_id', $customer->id)->whereKey($id)->delete();
        if ($this->editingContactId === $id) {
            $this->reset(['contactName', 'contactPhone', 'contactEmail', 'contactTitle', 'editingContactId']);
        }
        $this->dispatch('be-toast', message: 'Contact deleted.');
    }

    public function exportExcel(XlsxExporter $exporter): StreamedResponse
    {
        $this->authorize('viewAny', Customer::class);

        $rows = Customer::query()
            ->when($this->status === 'active', fn ($q) => $q->active())
            ->when($this->status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->search($this->search)
            ->orderBy($this->sortField, $this->sortDirection)
            ->orderBy('id')
            ->get()
            ->map(fn (Customer $c) => [
                $c->customer_number,
                $c->display_name,
                $c->company_name,
                $c->fullName(),
                $c->email,
                $c->phone,
                $c->bill_to_street1,
                $c->bill_to_city,
                $c->bill_to_state,
                $c->bill_to_zip,
                $c->balance,
                $c->is_active ? 'Active' : 'Inactive',
            ]);

        return $exporter->download('customers.xlsm', [
            'Customer #', 'Display Name', 'Company', 'Full Name', 'Email', 'Phone',
            'Street', 'City', 'State', 'ZIP', 'Balance', 'Status',
        ], $rows, title: 'Customer Center');
    }

    public function selectedCustomer(): ?Customer
    {
        if (! $this->selectedId) {
            return null;
        }

        return Customer::query()
            ->with(['priceLevel', 'taxCode', 'contacts', 'notes.user', 'todos', 'invoices' => fn ($q) => $q->latest('invoice_date')->limit(25)])
            ->find($this->selectedId);
    }

    protected function requireSelected(): Customer
    {
        $customer = $this->selectedCustomer();
        abort_unless($customer, 404);

        return $customer;
    }

    protected function resetEditorState(): void
    {
        $this->reset([
            'noteBody', 'editingNoteId', 'todoTitle', 'todoDueDate', 'editingTodoId',
            'contactName', 'contactPhone', 'contactEmail', 'contactTitle', 'editingContactId',
        ]);
    }

    public function render()
    {
        $customers = Customer::query()
            ->when($this->status === 'active', fn ($q) => $q->active())
            ->when($this->status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->search($this->search)
            ->orderBy($this->sortField, $this->sortDirection)
            ->orderBy('id')
            ->paginate(25);

        if (! $this->selectedId && $customers->count() > 0) {
            $this->selectedId = $customers->first()->id;
            $this->pinnedNote = (string) ($customers->first()->pinned_note ?? '');
        }

        $recentTransactions = collect();
        if ($this->listPane === 'transactions') {
            $invoices = Invoice::query()
                ->with('customer')
                ->when($this->search !== '', function ($q) {
                    $like = '%'.$this->search.'%';
                    $q->where(function ($inner) use ($like) {
                        $inner->where('invoice_number', 'like', $like)
                            ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                    });
                })
                ->orderByDesc('invoice_date')
                ->limit(40)
                ->get()
                ->map(fn (Invoice $invoice) => [
                    'id' => $invoice->id,
                    'type' => 'Invoice',
                    'number' => $invoice->invoice_number,
                    'date' => $invoice->invoice_date?->format('m/d/Y'),
                    'customer' => $invoice->customer?->display_name,
                    'customer_id' => $invoice->customer_id,
                    'amount' => $invoice->total,
                    'sort' => $invoice->invoice_date?->timestamp ?? 0,
                ]);

            $payments = Payment::query()
                ->with('customer')
                ->when($this->search !== '', function ($q) {
                    $like = '%'.$this->search.'%';
                    $q->where(function ($inner) use ($like) {
                        $inner->where('payment_number', 'like', $like)
                            ->orWhereHas('customer', fn ($c) => $c->where('display_name', 'like', $like));
                    });
                })
                ->orderByDesc('payment_date')
                ->limit(40)
                ->get()
                ->map(fn (Payment $payment) => [
                    'id' => $payment->id,
                    'type' => 'Payment',
                    'number' => $payment->payment_number,
                    'date' => $payment->payment_date?->format('m/d/Y'),
                    'customer' => $payment->customer?->display_name,
                    'customer_id' => $payment->customer_id,
                    'amount' => $payment->amount,
                    'sort' => $payment->payment_date?->timestamp ?? 0,
                ]);

            $recentTransactions = $invoices->concat($payments)
                ->sortByDesc('sort')
                ->take(50)
                ->values();
        }

        return view('livewire.customers.customer-center', [
            'customers' => $customers,
            'selected' => $this->selectedCustomer(),
            'recentTransactions' => $recentTransactions,
        ])->layoutData([
            'title' => 'Customer Center',
            'windowTitle' => 'Customer Center',
        ]);
    }
}
