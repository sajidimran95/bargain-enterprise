<?php

namespace App\Livewire\Users;

use App\Models\Role;
use App\Models\User;
use App\Support\SystemPermissionCatalog;
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

    /** @var array<int, string> */
    public array $selectedPermissions = [];

    public function mount(?User $user = null): void
    {
        abort_unless(auth()->user()?->hasPermission('users.manage'), 403);

        if ($user?->exists) {
            $user->loadMissing(['roles.permissions', 'permissions']);
            $this->editingId = (int) $user->id;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = (string) ($user->roles->first()?->name ?? '');

            if ($user->permissions->isNotEmpty()) {
                $this->selectedPermissions = $user->permissions->pluck('name')->values()->all();
            } elseif ($this->role !== '') {
                $this->applyRoleTemplate($this->role);
            }
        }
    }

    public function updatedRole(string $value): void
    {
        if ($value === '') {
            return;
        }

        $this->applyRoleTemplate($value);
    }

    public function togglePermission(string $permission): void
    {
        if (in_array($permission, $this->selectedPermissions, true)) {
            $this->selectedPermissions = array_values(array_filter(
                $this->selectedPermissions,
                fn (string $name) => $name !== $permission
            ));

            return;
        }

        $this->selectedPermissions[] = $permission;
    }

    public function toggleSubmenu(string $menu, string $submenu): void
    {
        $names = collect(SystemPermissionCatalog::forRoleForm()[$menu]['submenus'][$submenu] ?? [])
            ->pluck('name')
            ->all();

        if ($names === []) {
            return;
        }

        $allSelected = collect($names)->every(
            fn (string $name) => in_array($name, $this->selectedPermissions, true)
        );

        if ($allSelected) {
            $this->selectedPermissions = array_values(array_diff($this->selectedPermissions, $names));

            return;
        }

        $this->selectedPermissions = array_values(array_unique([...$this->selectedPermissions, ...$names]));
    }

    public function toggleMenu(string $menu): void
    {
        $names = [];
        foreach (SystemPermissionCatalog::forRoleForm()[$menu]['submenus'] ?? [] as $items) {
            foreach ($items as $item) {
                $names[] = $item['name'];
            }
        }

        if ($names === []) {
            return;
        }

        $allSelected = collect($names)->every(
            fn (string $name) => in_array($name, $this->selectedPermissions, true)
        );

        if ($allSelected) {
            $this->selectedPermissions = array_values(array_diff($this->selectedPermissions, $names));

            return;
        }

        $this->selectedPermissions = array_values(array_unique([...$this->selectedPermissions, ...$names]));
    }

    public function selectAll(): void
    {
        $this->selectedPermissions = SystemPermissionCatalog::allPermissionNames();
    }

    public function clearAll(): void
    {
        $this->selectedPermissions = [];
    }

    public function resetToRole(): void
    {
        if ($this->role === '') {
            return;
        }

        $this->applyRoleTemplate($this->role);
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
            'selectedPermissions' => ['array'],
            'selectedPermissions.*' => ['string', 'exists:permissions,name'],
        ];

        if ($this->editingId === null || $this->password !== '') {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $this->validate($rules);

        if ($this->selectedPermissions === []) {
            $this->applyRoleTemplate($this->role);
        }

        if ($this->editingId) {
            $user = User::query()->findOrFail($this->editingId);

            if ($user->isPrimaryAdmin()) {
                $this->email = $user->email;
                if (! in_array($this->role, ['admin', 'owner'], true)) {
                    $this->role = 'admin';
                }
            }

            $user->name = $this->name;
            $user->email = $this->email;
            if ($this->password !== '') {
                $user->password = Hash::make($this->password);
            }
            $user->save();
            $message = 'User updated.';
        } else {
            $user = User::query()->create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
            ]);
            $this->editingId = (int) $user->id;
            $message = 'User created.';
        }

        $user->syncRoles([$this->role]);
        $user->syncPermissions($this->selectedPermissions);

        $this->password = '';
        $this->password_confirmation = '';
        $this->dispatch('be-toast', message: $message);

        return $this->redirect(route('users.index'), navigate: true);
    }

    public function deleteUser(): mixed
    {
        abort_unless(auth()->user()?->hasPermission('users.manage'), 403);

        if (! $this->editingId) {
            return null;
        }

        $user = User::query()->findOrFail($this->editingId);
        $actor = auth()->user();

        if (! $user->canBeDeletedBy($actor instanceof User ? $actor : null)) {
            $message = $user->isPrimaryAdmin()
                ? 'The main admin account cannot be deleted.'
                : 'You cannot delete your own account.';
            $this->dispatch('be-toast', message: $message);

            return null;
        }

        $user->roles()->detach();
        $user->permissions()->detach();
        $user->delete();
        $this->dispatch('be-toast', message: 'User deleted.');

        return $this->redirect(route('users.index'), navigate: true);
    }

    protected function applyRoleTemplate(string $roleName): void
    {
        $role = Role::query()->where('name', $roleName)->with('permissions')->first();
        if (! $role) {
            return;
        }

        if (in_array($role->name, ['admin', 'owner'], true)) {
            $this->selectedPermissions = SystemPermissionCatalog::allPermissionNames();

            return;
        }

        $this->selectedPermissions = $role->permissions->pluck('name')->values()->all();
    }

    public function render()
    {
        $rolePermissionNames = [];
        if ($this->role !== '') {
            $role = Role::query()->where('name', $this->role)->with('permissions')->first();
            $rolePermissionNames = $role?->permissions->pluck('name')->all() ?? [];
        }

        $editingUser = $this->editingId
            ? User::query()->find($this->editingId)
            : null;

        return view('livewire.users.user-form', [
            'roleOptions' => ['' => 'Select role…'] + Role::query()
                ->orderBy('label')
                ->pluck('label', 'name')
                ->all(),
            'sections' => SystemPermissionCatalog::forRoleForm(),
            'rolePermissionNames' => $rolePermissionNames,
            'pageTitle' => $this->editingId ? 'Edit User' : 'Create User',
            'editingUser' => $editingUser,
            'canDelete' => $editingUser?->canBeDeletedBy(auth()->user()) ?? false,
            'isPrimaryAdmin' => $editingUser?->isPrimaryAdmin() ?? false,
        ])->layoutData([
            'title' => $this->editingId ? 'Edit User' : 'Create User',
            'windowTitle' => $this->editingId ? 'Edit User' : 'Create User',
        ]);
    }
}
