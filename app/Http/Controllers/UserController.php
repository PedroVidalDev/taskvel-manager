<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Http\Requests\User\LoginUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UserRequest;
use App\Http\Responses\CreatedResponse;
use App\Http\Responses\NoContentResponse;
use App\Http\Responses\SuccessResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class UserController extends Controller {

    public function __construct(private readonly UserService $service) {}

    public function showTasks(int $id): SuccessResponse {
        $tasks = $this->service->getTasks($id);

        return new SuccessResponse($tasks);
    }

    public function register(UserRequest $request): CreatedResponse {
        $user = $this->service->store($request->validated());

        return new CreatedResponse($user);
    }

    public function update(int $id, UpdateUserRequest $request): SuccessResponse {
        $user = $this->service->update($id, $request->validated());

        return new SuccessResponse($user);
    }

    public function destroy(int $id): NoContentResponse {
        $this->service->destroy($id);

        return new NoContentResponse();
    }

    public function login(LoginUserRequest $request): SuccessResponse {
        $token = $this->service->login($request->validated());

        return new SuccessResponse($token);
    }

    public function confirmEmail(int $id, Request $request) {
        Log::info("Confirming email for user $id");

        $url = $request->query('hash');

        $this->service->confirmEmail($id, $url);

        return new NoContentResponse();
    }
}
