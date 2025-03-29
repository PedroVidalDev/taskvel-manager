<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function show(User $user, Project $project): bool
    {
        return auth()->user()->id === $project->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return auth()->user()->id === $project->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user, Project $project): bool
    {
        return auth()->user()->id === $project->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function tasks(User $user, Project $project): bool
    {
        return auth()->user()->id === $project->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function tasksByStatus(User $user, Project $project): bool
    {
        return auth()->user()->id === $project->user_id;
    }
}
