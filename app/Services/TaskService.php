<?php

namespace App\Services;

use App\Http\Repositories\TaskRepository;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskService {

    public function __construct(private readonly TaskRepository $repository) {}

    public function index(): AnonymousResourceCollection {
        return TaskResource::collection($this->repository->index());
    }

    public function show(int $id): TaskResource {
        return new TaskResource($this->repository->show($id));
    }

    public function store(mixed $data): TaskResource {
        return new TaskResource($this->repository->store($data));
    }

    public function storeSubtask(int $id, mixed $data): TaskResource {
        $mainTask = $this->repository->show($id);
        $subTask = $this->repository->store($data);

        $mainTask->subtasks()->attach($subTask->id);

        return new TaskResource($subTask);
    }

    public function getAllSubtasks(int $id): AnonymousResourceCollection {
        $task = $this->repository->show($id);

        return TaskResource::collection($task->subtasks);
    }

    public function update($id, $data): TaskResource {
        return new TaskResource($this->repository->update($id, $data));
    }

    public function destroy(int $id): void {
        $this->repository->destroy($id);
    }
}
