<?php

namespace App\Livewire\Roles;

use App\Models\Permission;
use App\Models\Role;
use App\Support\SystemPermissionCatalog;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Role')]
class RoleForm extends Component
{
    public ?int $editingId = null;

    public string $name = '';

    public string $label = '';

    public string $description = '';

    /** @var array<int, string> */
    public array $selectedPermissions = [];

    public bool $isSystemAdmin = false;

    public function mount(?Role $role = null): void
    {
        abort_unless(auth()->user()?->hasPermission('roles.manage'), 403);

        if ($role?->exists) {
            $role->loadMissing('permissions');
            $this->editingId = (int) $role->id;
            $this->name = $role->name;
            $this->label = $role->label;
            $this->description = (string) ($role->description ?? '');
            $this->isSystemAdmin = in_array($role->name, ['admin', 'owner'], true);
            $this->selectedPermissions = $role->permissions->pluck('name')->values()->all();
        }
    }

    public function togglePermission(string $permission): void
    {
        if ($this->isSystemAdmin) {
            return;
        }

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
        if ($this->isSystemAdmin) {
            return;
        }

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
        if ($this->isSystemAdmin) {
            return;
        }

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
        if ($this->isSystemAdmin) {
            return;
        }

        $this->selectedPermissions = SystemPermissionCatalog::allPermissionNames();
    }

    public function clearAll(): void
    {
        if ($this->isSystemAdmin) {
            return;
        }

        $this->selectedPermissions = [];
    }

    public function save(): mixed
    {
        abort_unless(auth()->user()?->hasPermission('roles.manage'), 403);

        $this->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('roles', 'name')->ignore($this->editingId),
            ],
            'label' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:255'],
            'selectedPermissions' => ['array'],
            'selectedPermissions.*' => ['string', 'exists:permissions,name'],
        ]);

        if ($this->editingId) {
            $role = Role::query()->findOrFail($this->editingId);
            if (! in_array($role->name, ['admin', 'owner'], true)) {
                $role->name = $this->name;
            }
            $role->label = $this->label;
            $role->description = $this->description ?: null;
            $role->save();
        } else {
            $role = Role::query()->create([
                'name' => $this->name,
                'label' => $this->label,
                'description' => $this->description ?: null,
            ]);
            $this->editingId = (int) $role->id;
        }

        if (in_array($role->name, ['admin', 'owner'], true)) {
            $role->permissions()->sync(Permission::query()->pluck('id'));
        } else {
            $ids = Permission::query()
                ->whereIn('name', $this->selectedPermissions)
                ->pluck('id');
            $role->permissions()->sync($ids);
        }

        $this->dispatch('be-toast', message: 'Role saved.');

        return $this->redirect(route('roles.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.roles.role-form', [
            'sections' => SystemPermissionCatalog::forRoleForm(),
            'pageTitle' => $this->editingId ? 'Edit Role' : 'Create Role',
        ])->layoutData([
            'title' => $this->editingId ? 'Edit Role' : 'Create Role',
            'windowTitle' => $this->editingId ? 'Edit Role' : 'Create Role',
        ]);
    }
}
