<?php

namespace App\Http\Repositories;

use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Collection;

class TaskStatusRepository {

    public function index(): Collection {
        return TaskStatus::all();
    }

    public function existsByColumn(string $column, mixed $value): bool {
        return TaskStatus::where($column, $value)->exists();
    }

    public function show(int $id): TaskStatus {
        return TaskStatus::find($id);
    }

    public function store($data): TaskStatus {
        return TaskStatus::create($data);
    }

    public function update($taskStatus, $data): TaskStatus {
        $taskStatus->update($data);

        return $taskStatus;
    }

    public function destroy(int $id): void {
        TaskStatus::destroy($id);
    }
}
