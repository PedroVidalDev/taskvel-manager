<?php

namespace App\Services;

use App\Http\Repositories\TaskRepository;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Task\TaskResource;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;

class TaskService {

    public function __construct(private readonly TaskRepository $repository) {}

    public function show(int $id): TaskResource {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Task', $id);
        }
        return new TaskResource($this->repository->show($id));
    }

    public function showComments(int $id): AnonymousResourceCollection {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Task', $id);
        }
        return CommentResource::collection($this->repository->show($id)->comments);
    }

    public function store(mixed $data): TaskResource {
        return new TaskResource($this->repository->store($data));
    }

    public function storeSubtask(int $id, mixed $data): TaskResource {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Task', $id);
        }

        $mainTask = $this->repository->show($id);
        $subTask = $this->repository->store($data);

        $mainTask->subtasks()->attach($subTask->id);

        return new TaskResource($subTask);
    }

    public function getAllSubtasks(int $id): AnonymousResourceCollection {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Task', $id);
        }

        $task = $this->repository->show($id);

        return TaskResource::collection($task->subtasks);
    }

    public function update(int $id, mixed $data): TaskResource {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Task', $id);
        }
        $task = $this->repository->show($id);
        return new TaskResource($this->repository->update($task, $data));
    }

    public function destroy(int $id): void {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Task', $id);
        }
        $this->repository->destroy($id);
    }
}
