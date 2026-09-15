<?php

namespace App\Livewire\Accounting;

use App\Actions\Accounting\UpsertAccountAction;
use App\Livewire\Concerns\WithErpListActions;
use App\Models\Account;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.app')]
#[Title('Chart of Accounts')]
class ChartOfAccounts extends Component
{
    use WithErpListActions;
    use WithPagination;

    #[Url]
    public string $typeFilter = 'all';

    public bool $showForm = false;

    public ?int $editingId = null;

    /** @var array<string, mixed> */
    public array $form = [
        'number' => '',
        'name' => '',
        'type' => 'asset',
        'subtype' => '',
        'is_active' => true,
    ];

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('accounting.view'), 403);
    }

    public function updatingTypeFilter(): void
    {
        $this->resetPage();
    }

    public function newAccount(): void
    {
        abort_unless(auth()->user()?->hasPermission('accounting.manage'), 403);
        $this->editingId = null;
        $this->resetForm();
        $this->showForm = true;
    }

    public function editAccount(int $id): void
    {
        abort_unless(auth()->user()?->hasPermission('accounting.manage'), 403);

        $account = Account::query()->findOrFail($id);

        if ($account->is_system) {
            $this->dispatch('be-toast', message: 'System accounts are read-only.');

            return;
        }

        $this->editingId = $account->id;
        $this->form = [
            'number' => $account->number,
            'name' => $account->name,
            'type' => $account->type,
            'subtype' => $account->subtype ?? '',
            'is_active' => $account->is_active,
        ];
        $this->showForm = true;
    }

    public function cancelForm(): void
    {
        $this->showForm = false;
        $this->editingId = null;
        $this->resetForm();
    }

    public function saveAccount(UpsertAccountAction $action): void
    {
        abort_unless(auth()->user()?->hasPermission('accounting.manage'), 403);

        try {
            $action->handle($this->form, $this->editingId);
        } catch (ValidationException $e) {
            throw $e;
        } catch (RuntimeException $e) {
            $this->dispatch('be-toast', message: $e->getMessage());

            return;
        }

        $this->dispatch('be-toast', message: $this->editingId ? 'Account updated.' : 'Account created.');
        $this->cancelForm();
    }

    public function toggleActive(int $id): void
    {
        abort_unless(auth()->user()?->hasPermission('accounting.manage'), 403);

        $account = Account::query()->findOrFail($id);

        if ($account->is_system) {
            $this->dispatch('be-toast', message: 'System accounts cannot be deactivated.');

            return;
        }

        $account->update(['is_active' => ! $account->is_active]);
        $this->dispatch('be-toast', message: $account->is_active ? 'Account activated.' : 'Account deactivated.');
    }

    public function exportExcel(): StreamedResponse
    {
        abort_unless(auth()->user()?->hasPermission('accounting.view'), 403);

        $rows = Account::query()
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('number', 'like', $like)
                        ->orWhere('name', 'like', $like);
                });
            })
            ->when($this->typeFilter !== 'all', fn ($q) => $q->where('type', $this->typeFilter))
            ->orderBy('number')
            ->get()
            ->map(fn (Account $account) => [
                $account->number,
                $account->name,
                $account->type,
                $account->subtype,
                $account->is_active ? 'Yes' : 'No',
            ]);

        return $this->csvDownload('chart-of-accounts.csv', ['Number', 'Name', 'Type', 'Subtype', 'Active'], $rows);
    }

    protected function resetForm(): void
    {
        $this->form = [
            'number' => '',
            'name' => '',
            'type' => 'asset',
            'subtype' => '',
            'is_active' => true,
        ];
    }

    public function render()
    {
        $accounts = Account::query()
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('number', 'like', $like)
                        ->orWhere('name', 'like', $like);
                });
            })
            ->when($this->typeFilter !== 'all', fn ($q) => $q->where('type', $this->typeFilter))
            ->orderBy('number')
            ->paginate(40);

        return view('livewire.accounting.chart-of-accounts', [
            'accounts' => $accounts,
        ])->layoutData([
            'title' => 'Chart of Accounts',
            'windowTitle' => 'Chart of Accounts',
        ]);
    }
}
