<?php

namespace App\Policies;

use App\Models\Rollcall;
use App\Models\User;

class RollcallPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Rollcall');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Rollcall $rollcall): bool
    {
        return $user->checkPermissionTo('view Rollcall');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Rollcall');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Rollcall $rollcall): bool
    {
        return $user->checkPermissionTo('update Rollcall');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Rollcall $rollcall): bool
    {
        return $user->checkPermissionTo('delete Rollcall');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Rollcall $rollcall): bool
    {
        return $user->checkPermissionTo('restore Rollcall');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Rollcall $rollcall): bool
    {
        return $user->checkPermissionTo('force-delete Rollcall');
    }
}
