<?php

namespace App\Http\Repositories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository {

    public function index(): Collection {
        return Task::all();
    }

    public function show(int $id): Task {
        return Task::find($id);
    }

    public function store($data): Task {
        return Task::create($data);
    }

    public function update($task, $data): Task {
        $task->update($data);

        return $task;
    }

    public function destroy(int $id): void {
        Task::destroy($id);
    }


}
