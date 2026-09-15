<?php

namespace App\Livewire\Audit;

use App\Models\AuditLog;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Audit Log')]
class AuditLogIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $action = '';

    #[Url]
    public string $modelType = '';

    #[Url]
    public string $userId = '';

    #[Url]
    public string $from = '';

    #[Url]
    public string $to = '';

    public ?int $selectedId = null;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('audit.view'), 403);

        if ($this->from === '') {
            $this->from = now()->subDays(30)->toDateString();
        }

        if ($this->to === '') {
            $this->to = now()->toDateString();
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedAction(): void
    {
        $this->resetPage();
    }

    public function updatedModelType(): void
    {
        $this->resetPage();
    }

    public function updatedUserId(): void
    {
        $this->resetPage();
    }

    public function updatedFrom(): void
    {
        $this->resetPage();
    }

    public function updatedTo(): void
    {
        $this->resetPage();
    }

    public function selectEntry(int $id): void
    {
        $this->selectedId = $id;
    }

    public function clearSelection(): void
    {
        $this->selectedId = null;
    }

    public function render()
    {
        $logs = AuditLog::query()
            ->with('user')
            ->when($this->from !== '', fn ($q) => $q->whereDate('created_at', '>=', $this->from))
            ->when($this->to !== '', fn ($q) => $q->whereDate('created_at', '<=', $this->to))
            ->when($this->action !== '', fn ($q) => $q->where('action', $this->action))
            ->when($this->modelType !== '', fn ($q) => $q->where('model_type', $this->modelType))
            ->when($this->userId !== '', fn ($q) => $q->where('user_id', $this->userId))
            ->when($this->search !== '', function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('action', 'like', $like)
                        ->orWhere('model_type', 'like', $like)
                        ->orWhere('model_id', 'like', $like)
                        ->orWhere('old_values', 'like', $like)
                        ->orWhere('new_values', 'like', $like)
                        ->orWhereHas('user', fn ($user) => $user->where('name', 'like', $like)->orWhere('email', 'like', $like));
                });
            })
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(40);

        $selected = $this->selectedId
            ? AuditLog::query()->with('user')->find($this->selectedId)
            : null;

        $actions = AuditLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $modelTypes = AuditLog::query()
            ->select('model_type')
            ->distinct()
            ->orderBy('model_type')
            ->pluck('model_type');

        $userOptions = ['' => 'All users'] + User::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->mapWithKeys(fn (User $user) => [(string) $user->id => $user->name])
            ->all();

        return view('livewire.audit.audit-log-index', [
            'logs' => $logs,
            'selected' => $selected,
            'actionOptions' => ['' => 'All actions'] + $actions->mapWithKeys(fn ($a) => [$a => $a])->all(),
            'modelTypeOptions' => ['' => 'All models'] + $modelTypes->mapWithKeys(
                fn ($type) => [$type => class_basename($type)]
            )->all(),
            'userOptions' => $userOptions,
        ])->layoutData([
            'title' => 'Audit Log',
            'windowTitle' => 'Audit Log',
        ]);
    }
}
