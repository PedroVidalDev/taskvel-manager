<?php

namespace App\Http\Repositories;

use App\Models\Task;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository {

    public function index($userId): Collection {
        return Task::where('user_id', $userId)->get();
    }

    public function show(int $id): Task {
        if(!Task::where('id', $id)->exists()) {
            throw new EntityNotFoundException('Task', $id);
        }
        return Task::find($id);
    }

    public function store($data): Task {
        return Task::create($data);
    }

    public function update(Task $task, mixed $data): Task {
        $task->update($data);

        return $task;
    }

    public function destroy(int $id): void {
        if(!Task::where('id', $id)->exists()) {
            throw new EntityNotFoundException('Task', $id);
        }
        Task::destroy($id);
    }
}
