<?php

namespace App\Policies;

use App\Models\GradingRule;
use App\Models\User;

class GradingRulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any GradingRule');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GradingRule $gradingrule): bool
    {
        return $user->checkPermissionTo('view GradingRule');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create GradingRule');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GradingRule $gradingrule): bool
    {
        return $user->checkPermissionTo('update GradingRule');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GradingRule $gradingrule): bool
    {
        return $user->checkPermissionTo('delete GradingRule');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GradingRule $gradingrule): bool
    {
        return $user->checkPermissionTo('restore GradingRule');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GradingRule $gradingrule): bool
    {
        return $user->checkPermissionTo('force-delete GradingRule');
    }
}
