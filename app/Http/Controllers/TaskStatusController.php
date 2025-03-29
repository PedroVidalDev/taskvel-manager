<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStatus\TaskStatusRequest;
use App\Http\Responses\CreatedResponse;
use App\Http\Responses\NoContentResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\TaskStatusService;

class TaskStatusController extends Controller {

    public function __construct(private readonly TaskStatusService $service) {}

    public function store(TaskStatusRequest $request): CreatedResponse {
        $comment = $this->service->store($request->validated());

        return new CreatedResponse($comment);
    }

    public function update(int $id, TaskStatusRequest $data): SuccessResponse {
        $comment = $this->service->update($id, $data->validated());

        return new SuccessResponse($comment);
    }

    public function destroy(int $id): NoContentResponse {
        $this->service->destroy($id);

        return new NoContentResponse();
    }
}
