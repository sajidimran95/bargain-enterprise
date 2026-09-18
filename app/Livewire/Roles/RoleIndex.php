<?php

namespace App\Livewire\Roles;

use App\Livewire\Concerns\WithErpListActions;
use App\Models\Role;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Roles')]
class RoleIndex extends Component
{
    use WithErpListActions;
    use WithPagination;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('roles.manage'), 403);
    }

    public function openEdit(int $id): void
    {
        abort_unless(auth()->user()?->hasPermission('roles.manage'), 403);
        $role = Role::query()->findOrFail($id);
        $this->selectedLineId = $id;
        $this->openWorkspaceEdit('roles.edit', ['role' => $role->id], 'Role: '.$role->label);
    }

    public function render()
    {
        $roles = Role::query()
            ->withCount(['users', 'permissions'])
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('name', 'like', $like)
                        ->orWhere('label', 'like', $like)
                        ->orWhere('description', 'like', $like);
                });
            })
            ->orderBy('label')
            ->paginate(30);

        return view('livewire.roles.role-index', [
            'roles' => $roles,
        ])->layoutData([
            'title' => 'Roles',
            'windowTitle' => 'Roles',
        ]);
    }
}
