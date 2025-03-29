<?php

namespace App\Http\Repositories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Collection;

class ProjectRepository {

    public function index(): Collection {
        return Project::all();
    }

    public function existsByColumn(string $column, mixed $value): bool {
        return Project::where($column, $value)->exists();
    }

    public function show(int $id): Project {
        return Project::find($id);
    }

    public function store($data): Project {
        return Project::create($data);
    }

    public function update($project, $data): Project {
        $project->update($data);

        return $project;
    }

    public function destroy(int $id): void {
        Project::destroy($id);
    }
}
