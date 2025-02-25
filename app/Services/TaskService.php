<?php

namespace App\Services;

use App\Http\Repositories\TaskRepository;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Task\TaskResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskService {

    public function __construct(private readonly TaskRepository $repository) {}

    public function index(): AnonymousResourceCollection {
        return TaskResource::collection($this->repository->index());
    }

    public function show(int $id): TaskResource {
        return new TaskResource($this->repository->show($id));
    }

    public function showComments(int $id): AnonymousResourceCollection {
        return CommentResource::collection($this->repository->show($id)->comments);
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

    public function update(int $id, mixed $data): TaskResource {
        $task = $this->repository->show($id);
        return new TaskResource($this->repository->update($task, $data));
    }

    public function destroy(int $id): void {
        $this->repository->destroy($id);
    }
}
