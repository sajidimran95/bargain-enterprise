<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;

class ItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('item.view');
    }

    public function view(User $user, Item $item): bool
    {
        return $user->hasPermission('item.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('item.manage');
    }

    public function update(User $user, Item $item): bool
    {
        return $user->hasPermission('item.manage');
    }

    public function delete(User $user, Item $item): bool
    {
        return $user->hasPermission('item.manage');
    }
}
