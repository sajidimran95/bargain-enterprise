<?php

namespace App\Livewire\Vendors;

use App\Actions\Vendors\ActivateVendorAction;
use App\Actions\Vendors\DeactivateVendorAction;
use App\Actions\Vendors\DuplicateVendorAction;
use App\Models\Vendor;
use App\Models\VendorContact;
use App\Models\VendorNote;
use App\Support\XlsxExporter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Vendor Center')]
class VendorCenter extends Component
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

    public string $noteBody = '';

    public ?int $editingNoteId = null;

    public function focusSearch(): void
    {
        $this->dispatch('be-focus-list-search');
    }

    public string $contactName = '';

    public string $contactPhone = '';

    public string $contactEmail = '';

    public string $contactTitle = '';

    public ?int $editingContactId = null;

    public function mount(?Vendor $vendor = null): void
    {
        $this->authorize('viewAny', Vendor::class);

        if ($vendor?->exists) {
            $this->selectedId = $vendor->id;
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function selectVendor(int $id): void
    {
        $this->selectedId = $id;
        $this->activeTab = 'contacts';
        $this->reset(['noteBody', 'editingNoteId', 'contactName', 'contactPhone', 'contactEmail', 'contactTitle', 'editingContactId']);
    }

    public function deactivate(DeactivateVendorAction $action): void
    {
        $vendor = $this->requireSelected();
        $this->authorize('delete', $vendor);
        $action->handle($vendor);
        $this->dispatch('be-toast', message: 'Vendor deactivated.');
    }

    public function activate(ActivateVendorAction $action): void
    {
        $vendor = $this->requireSelected();
        $this->authorize('update', $vendor);
        $action->handle($vendor);
        $this->dispatch('be-toast', message: 'Vendor activated.');
    }

    public function duplicate(DuplicateVendorAction $action): mixed
    {
        $vendor = $this->requireSelected();
        $this->authorize('create', Vendor::class);
        $copy = $action->handle($vendor);

        return redirect()->route('vendors.edit', $copy);
    }

    public function addContact(): void
    {
        $vendor = $this->requireSelected();
        $this->authorize('update', $vendor);
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
            VendorContact::query()
                ->where('vendor_id', $vendor->id)
                ->whereKey($this->editingContactId)
                ->firstOrFail()
                ->update($payload);
        } else {
            VendorContact::query()->create($payload + [
                'vendor_id' => $vendor->id,
                'is_primary' => $vendor->contacts()->count() === 0,
            ]);
        }

        $this->reset(['contactName', 'contactPhone', 'contactEmail', 'contactTitle', 'editingContactId']);
        $this->dispatch('be-toast', message: 'Contact saved.');
    }

    public function editContact(int $id): void
    {
        $contact = VendorContact::query()->where('vendor_id', $this->selectedId)->findOrFail($id);
        $this->editingContactId = $contact->id;
        $this->contactName = $contact->name;
        $this->contactTitle = (string) ($contact->title ?? '');
        $this->contactPhone = (string) ($contact->phone ?? '');
        $this->contactEmail = (string) ($contact->email ?? '');
        $this->activeTab = 'contacts';
    }

    public function deleteContact(int $id): void
    {
        $vendor = $this->requireSelected();
        $this->authorize('update', $vendor);
        VendorContact::query()->where('vendor_id', $vendor->id)->whereKey($id)->delete();
        $this->dispatch('be-toast', message: 'Contact deleted.');
    }

    public function addNote(): void
    {
        $vendor = $this->requireSelected();
        $this->authorize('update', $vendor);
        $this->validate(['noteBody' => ['required', 'string', 'max:5000']]);

        if ($this->editingNoteId) {
            VendorNote::query()
                ->where('vendor_id', $vendor->id)
                ->whereKey($this->editingNoteId)
                ->firstOrFail()
                ->update(['body' => $this->noteBody]);
        } else {
            VendorNote::query()->create([
                'vendor_id' => $vendor->id,
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
        $note = VendorNote::query()->where('vendor_id', $this->selectedId)->findOrFail($id);
        $this->editingNoteId = $note->id;
        $this->noteBody = $note->body;
        $this->activeTab = 'notes';
    }

    public function deleteNote(int $id): void
    {
        $vendor = $this->requireSelected();
        $this->authorize('update', $vendor);
        VendorNote::query()->where('vendor_id', $vendor->id)->whereKey($id)->delete();
        $this->dispatch('be-toast', message: 'Note deleted.');
    }

    public function exportExcel(XlsxExporter $exporter): StreamedResponse
    {
        $this->authorize('viewAny', Vendor::class);

        $rows = Vendor::query()
            ->when($this->status === 'active', fn ($q) => $q->active())
            ->when($this->status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->search($this->search)
            ->orderBy('display_name')
            ->get()
            ->map(fn (Vendor $v) => [
                $v->vendor_number,
                $v->display_name,
                $v->company_name,
                $v->email,
                $v->phone,
                $v->balance,
                $v->is_active ? 'Active' : 'Inactive',
            ]);

        return $exporter->download('vendors.xlsm', [
            'Vendor #', 'Display Name', 'Company', 'Email', 'Phone', 'Balance', 'Status',
        ], $rows, title: 'Vendor Center');
    }

    public function selectedVendor(): ?Vendor
    {
        if (! $this->selectedId) {
            return null;
        }

        return Vendor::query()
            ->with(['contacts', 'notesRelation.user'])
            ->find($this->selectedId);
    }

    protected function requireSelected(): Vendor
    {
        $vendor = $this->selectedVendor();
        abort_unless($vendor, 404);

        return $vendor;
    }

    public function render()
    {
        $vendors = Vendor::query()
            ->when($this->status === 'active', fn ($q) => $q->active())
            ->when($this->status === 'inactive', fn ($q) => $q->where('is_active', false))
            ->search($this->search)
            ->orderBy('display_name')
            ->orderBy('id')
            ->paginate(25);

        if (! $this->selectedId && $vendors->count() > 0) {
            $this->selectedId = $vendors->first()->id;
        }

        return view('livewire.vendors.vendor-center', [
            'vendors' => $vendors,
            'selected' => $this->selectedVendor(),
        ])->layoutData([
            'title' => 'Vendor Center',
            'windowTitle' => 'Vendor Center',
        ]);
    }
}
