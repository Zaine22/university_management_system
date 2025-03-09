<?php

namespace App\Policies;

use App\Models\User;

class PermissionPolicy
{
    public static function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }
}
