<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
{
    public static function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin');
    }
}
