<?php

namespace App\Http\Controllers;

use App\Http\Requests\Project\ProjectRequest;
use App\Http\Responses\CreatedResponse;
use App\Http\Responses\NoContentResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\ProjectService;

class ProjectController extends Controller {
    public function __construct(private readonly ProjectService $service) {}

    public function index(): SuccessResponse {
        $comments = $this->service->index();

        return new SuccessResponse($comments);
    }

    public function show(int $id): SuccessResponse {
        $comment = $this->service->show($id);

        return new SuccessResponse($comment);
    }

    public function store(ProjectRequest $request): CreatedResponse {
        $comment = $this->service->store($request->validated());

        return new CreatedResponse($comment);
    }

    public function update(int $id, ProjectRequest $data): SuccessResponse {
        $comment = $this->service->update($id, $data->validated());

        return new SuccessResponse($comment);
    }

    public function destroy(int $id): NoContentResponse {
        $this->service->destroy($id);

        return new NoContentResponse();
    }

    public function tasks(int $id): SuccessResponse {
        $tasks = $this->service->tasks($id);

        return new SuccessResponse($tasks);
    }

    public function tasksByStatus(int $id): SuccessResponse {
        $tasks = $this->service->tasksByStatus($id);

        return new SuccessResponse($tasks);
    }
}
