<?php

namespace App\Services;

use App\Http\Repositories\TaskStatusRepository;
use App\Http\Resources\TaskStatus\TaskStatusResource;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaskStatusService {

    public function __construct(private readonly TaskStatusRepository $repository) {}

    public function update(int $id, mixed $data): TaskStatusResource {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('TaskStatus', $id);
        }

        $taskStatus = $this->repository->show($id);

        return new TaskStatusResource($this->repository->update($taskStatus, $data));
    }

    public function store(mixed $data): TaskStatusResource {
        return new TaskStatusResource($this->repository->store($data));
    }

    public function destroy(int $id): void {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('TaskStatus', $id);
        }
        $this->repository->destroy($id);
    }
}
