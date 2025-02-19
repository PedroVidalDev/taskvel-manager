<?php

namespace App\Services;

use App\Http\Repositories\UserRepository;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\User\UserResource;
use App\Mail\Notification;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService {

    public function __construct(private readonly UserRepository $repository) {}

    public function getTasks(int $id): AnonymousResourceCollection {
        $user = $this->repository->show($id);

        return TaskResource::collection($user->tasks);
    }

    public function login(mixed $data): array {
        if(!$token = JWTAuth::attempt($data)) {
            throw new JWTException("Invalid credentials");
        }

        return ['token' => $token];
    }

    public function store(mixed $data): UserResource {
        $data['password'] = bcrypt($data['password']);

        $user = $this->repository->store($data);

        Mail::to($user->email)->send(new Notification("Taskvel | Conta criada com sucesso!", "Olá $user->name, sua conta foi criada com sucesso no Taskvel! Sinta-se livre para organizar suas tarefas no nosso serviço!"));

        return new UserResource($user);
    }

    public function update(int $id, mixed $data): UserResource {
        $user = $this->repository->show($id);
        $data['password'] = bcrypt($data['password']);

        return new UserResource($this->repository->update($user, $data));
    }

    public function destroy(int $id): void {
        $this->repository->destroy($id);
    }
}
