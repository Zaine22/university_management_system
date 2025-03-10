<?php

namespace App\Policies;

use App\Models\Result;
use App\Models\User;

class ResultPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Result');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Result $result): bool
    {
        return $user->checkPermissionTo('view Result');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Result');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Result $result): bool
    {
        return $user->checkPermissionTo('update Result');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Result $result): bool
    {
        return $user->checkPermissionTo('delete Result');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Result $result): bool
    {
        return $user->checkPermissionTo('restore Result');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Result $result): bool
    {
        return $user->checkPermissionTo('force-delete Result');
    }
}
