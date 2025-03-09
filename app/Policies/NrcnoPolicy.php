<?php

namespace App\Policies;

use App\Models\Nrcno;
use App\Models\User;

class NrcnoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Nrcno');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Nrcno $nrcno): bool
    {
        return $user->checkPermissionTo('view Nrcno');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Nrcno');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Nrcno $nrcno): bool
    {
        return $user->checkPermissionTo('update Nrcno');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Nrcno $nrcno): bool
    {
        return $user->checkPermissionTo('delete Nrcno');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Nrcno $nrcno): bool
    {
        return $user->checkPermissionTo('restore Nrcno');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Nrcno $nrcno): bool
    {
        return $user->checkPermissionTo('force-delete Nrcno');
    }
}
