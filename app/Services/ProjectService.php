<?php

namespace App\Services;

use App\Http\Repositories\ProjectRepository;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\Task\TaskResource;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectService {
    public function __construct(private readonly ProjectRepository $repository) {}

    public function index(): AnonymousResourceCollection {
        return ProjectResource::collection($this->repository->index());
    }

    public function show(int $id): ProjectResource {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Project', $id);
        }
        return new ProjectResource($this->repository->show($id));
    }

    public function update(int $id, mixed $data): ProjectResource {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Project', $id);
        }

        $project = $this->repository->show($id);

        $user = auth()->user();
        $data['user_id'] = $user->id;

        return new ProjectResource($this->repository->update($project, $data));
    }

    public function store(mixed $data): ProjectResource {
        $user = auth()->user();
        $data['user_id'] = $user->id;
        return new ProjectResource($this->repository->store($data));
    }

    public function destroy(int $id): void {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Project', $id);
        }
        $this->repository->destroy($id);
    }

    public function tasks(int $id): AnonymousResourceCollection {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Project', $id);
        }

        $project = $this->repository->show($id);
        return TaskResource::collection($project->tasks);
    }

    public function tasksByStatus(int $id) {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Project', $id);
        }

        $project = $this->repository->show($id);
        return $project->taskStatus()->with('tasks')->get();
    }
}
