<?php

namespace App\Livewire\Users;

use App\Livewire\Concerns\WithErpListActions;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Users')]
class UserIndex extends Component
{
    use WithErpListActions;
    use WithPagination;

    public function mount(): void
    {
        abort_unless(auth()->user()?->hasPermission('users.manage'), 403);
    }

    public function openEdit(int $id): void
    {
        abort_unless(auth()->user()?->hasPermission('users.manage'), 403);
        $user = User::query()->findOrFail($id);
        $this->selectedLineId = $id;
        $this->openWorkspaceEdit('users.edit', ['user' => $user->id], 'User: '.$user->name);
    }

    public function render()
    {
        $users = User::query()
            ->with('roles')
            ->when($this->search, function ($q) {
                $like = '%'.$this->search.'%';
                $q->where(function ($inner) use ($like) {
                    $inner->where('name', 'like', $like)
                        ->orWhere('email', 'like', $like);
                });
            })
            ->orderBy('name')
            ->paginate(30);

        return view('livewire.users.user-index', [
            'users' => $users,
        ])->layoutData([
            'title' => 'Users',
            'windowTitle' => 'Users',
        ]);
    }
}
