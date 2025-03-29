<?php

namespace App\Services;

use App\Http\Repositories\ProjectRepository;
use App\Http\Resources\Project\ProjectResource;
use App\Http\Resources\Task\TaskResource;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class ProjectService {
    public function __construct(private readonly ProjectRepository $repository) {}

    public function index(): AnonymousResourceCollection {
        return ProjectResource::collection($this->repository->index());
    }

    public function show(int $id): ProjectResource {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Project', $id);
        }

        $project = $this->repository->show($id);
        Gate::authorize('show', $project);

        return new ProjectResource($project);
    }

    public function update(int $id, mixed $data): ProjectResource {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Project', $id);
        }

        $project = $this->repository->show($id);
        Gate::authorize('update', $project);

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

        $project = $this->repository->show($id);
        Gate::authorize('destroy', $project);

        $this->repository->destroy($id);
    }

    public function tasks(int $id): AnonymousResourceCollection {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Project', $id);
        }

        $project = $this->repository->show($id);
        Gate::authorize('tasks', $project);

        return TaskResource::collection($project->tasks);
    }

    public function tasksByStatus(int $id) {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Project', $id);
        }

        $project = $this->repository->show($id);
        Gate::authorize('tasksByStatus', $project);

        return $project->taskStatus()->with('tasks')->get();
    }
}
