<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     */
    public function show(User $user, Task $task): bool
    {
        return auth()->user()->id === $task->project->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task): bool
    {
        return auth()->user()->id === $task->project->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function destroy(User $user, Task $task): bool
    {
        return auth()->user()->id === $task->project->user_id;
    }

    public function getAllSubtasks(User $user, Task $task): bool
    {
        return auth()->user()->id === $task->project->user_id;
    }

    public function storeSubtask(User $user, Task $task): bool
    {
        return auth()->user()->id === $task->project->user_id;
    }

    public function showComments(User $user, Task $task): bool
    {
        return auth()->user()->id === $task->project->user_id;
    }
}
