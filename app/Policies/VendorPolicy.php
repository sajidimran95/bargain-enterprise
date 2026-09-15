<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vendor;

class VendorPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('vendor.view');
    }

    public function view(User $user, Vendor $vendor): bool
    {
        return $user->hasPermission('vendor.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('vendor.manage');
    }

    public function update(User $user, Vendor $vendor): bool
    {
        return $user->hasPermission('vendor.manage');
    }

    public function delete(User $user, Vendor $vendor): bool
    {
        return $user->hasPermission('vendor.manage');
    }
}
