<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    public function assignRole(string|Role $role): void
    {
        $role = $role instanceof Role
            ? $role
            : Role::query()->where('name', $role)->firstOrFail();

        $this->roles()->syncWithoutDetaching([$role->id]);
    }

    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;

        return $this->roles->whereIn('name', $roles)->isNotEmpty();
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->hasRole(['owner', 'admin'])) {
            return true;
        }

        $this->loadMissing(['permissions', 'roles.permissions']);

        // Per-user permission overrides (does not change the role).
        if ($this->permissions->isNotEmpty()) {
            return $this->permissions->contains('name', $permission);
        }

        return $this->roles
            ->flatMap(fn (Role $role) => $role->permissions)
            ->contains('name', $permission);
    }

    public function syncRoles(array $roleNames): void
    {
        $ids = Role::query()->whereIn('name', $roleNames)->pluck('id');
        $this->roles()->sync($ids);
    }

    /**
     * @param  list<string>  $permissionNames
     */
    public function syncPermissions(array $permissionNames): void
    {
        $ids = Permission::query()->whereIn('name', $permissionNames)->pluck('id');
        $this->permissions()->sync($ids);
    }

    public function primaryRoleLabel(): ?string
    {
        return $this->roles->first()?->label;
    }

    /**
     * The main system admin account that must never be deleted.
     */
    public function isPrimaryAdmin(): bool
    {
        $primaryEmail = strtolower((string) config('bargain.primary_admin_email', 'admin@gmail.com'));

        return strtolower((string) $this->email) === $primaryEmail;
    }

    public function canBeDeletedBy(?User $actor): bool
    {
        if ($this->isPrimaryAdmin()) {
            return false;
        }

        if ($actor && (int) $actor->id === (int) $this->id) {
            return false;
        }

        return true;
    }
}
