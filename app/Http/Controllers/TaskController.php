<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\TaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Responses\CreatedResponse;
use App\Http\Responses\NoContentResponse;
use App\Http\Responses\SuccessResponse;
use App\Services\TaskService;

class TaskController extends Controller {

    public function __construct(private readonly TaskService $service){}

    public function show(int $id): SuccessResponse {
        $task = $this->service->show($id);

        return new SuccessResponse($task);
    }

    public function showComments(int $id): SuccessResponse {
        $task = $this->service->showComments($id);

        return new SuccessResponse($task);
    }

    public function store(TaskRequest $request): CreatedResponse {
        $task = $this->service->store($request->validated());

        return new CreatedResponse($task);
    }

    public function storeSubtask(int $id, TaskRequest $request): CreatedResponse {
        $task = $this->service->storeSubtask($id, $request->validated());

        return new CreatedResponse($task);
    }

    public function getAllSubtasks(int $id): SuccessResponse {
        $subtasks = $this->service->getAllSubtasks($id);

        return new SuccessResponse($subtasks);
    }

    public function update(int $id, UpdateTaskRequest $request): SuccessResponse {
        $task = $this->service->update($id, $request->validated());

        return new SuccessResponse($task);
    }

    public function destroy(int $id): NoContentResponse {
        $this->service->destroy($id);

        return new NoContentResponse();
    }


}
