<?php

namespace App\Policies;

use App\Models\Batch;
use App\Models\User;

class BatchPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Batch');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Batch $batch): bool
    {
        return $user->checkPermissionTo('view Batch');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Batch');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Batch $batch): bool
    {
        return $user->checkPermissionTo('update Batch');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Batch $batch): bool
    {
        return $user->checkPermissionTo('delete Batch');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Batch $batch): bool
    {
        return $user->checkPermissionTo('restore Batch');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Batch $batch): bool
    {
        return $user->checkPermissionTo('force-delete Batch');
    }
}
