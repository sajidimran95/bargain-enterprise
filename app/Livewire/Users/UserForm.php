<?php

namespace App\Livewire\Users;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('User')]
class UserForm extends Component
{
    public ?int $editingId = null;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $role = '';

    public function mount(?User $user = null): void
    {
        abort_unless(auth()->user()?->hasPermission('users.manage'), 403);

        if ($user?->exists) {
            $user->loadMissing('roles');
            $this->editingId = (int) $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = (string) ($user->roles->first()?->name ?? '');
        }
    }

    public function save(): mixed
    {
        abort_unless(auth()->user()?->hasPermission('users.manage'), 403);

        $rules = [
            'name' => ['required', 'string', 'max:120'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->editingId),
            ],
            'role' => ['required', 'exists:roles,name'],
        ];

        if ($this->editingId === null || $this->password !== '') {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $this->validate($rules);

        if ($this->editingId) {
            $user = User::query()->findOrFail($this->editingId);
            $user->name = $this->name;
            $user->email = $this->email;
            if ($this->password !== '') {
                $user->password = Hash::make($this->password);
            }
            $user->save();
            $user->syncRoles([$this->role]);
            $message = 'User updated.';
        } else {
            $user = User::query()->create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            $user->syncRoles([$this->role]);
            $this->editingId = (int) $user->id;
            $message = 'User created.';
        }

        $this->password = '';
        $this->password_confirmation = '';
        $this->dispatch('be-toast', message: $message);

        return $this->redirect(route('users.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.users.user-form', [
            'roleOptions' => ['' => 'Select role…'] + Role::query()
                ->orderBy('label')
                ->pluck('label', 'name')
                ->all(),
            'pageTitle' => $this->editingId ? 'Edit User' : 'Create User',
        ])->layoutData([
            'title' => $this->editingId ? 'Edit User' : 'Create User',
            'windowTitle' => $this->editingId ? 'Edit User' : 'Create User',
        ]);
    }
}
