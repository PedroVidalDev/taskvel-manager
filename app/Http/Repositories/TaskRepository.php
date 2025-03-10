<?php

namespace App\Http\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository {

    public function index($userId): Collection {
        return Task::where('user_id', $userId)->get();
    }

    public function existsByColumn(string $column, string $value) {
        return Task::where($column, $value)->exists();
    }

    public function show(int $id): Task {
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
        Task::destroy($id);
    }
}
