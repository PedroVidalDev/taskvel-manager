<?php

namespace App\Services;

use App\Http\Repositories\UserRepository;
use App\Http\Resources\Task\TaskResource;
use App\Http\Resources\User\UserResource;
use App\Jobs\MailJob;
use App\Mail\Notification;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
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

        dispatch(new MailJob([
            'email' => $user->email,
            'title' => "Taskvel | Conta criada com sucesso!",
            'text' => "Olá $user->name, sua conta foi criada com sucesso no Taskvel! Sinta-se livre para organizar suas tarefas no nosso serviço!"
        ]));

        $randomString = bin2hex(random_bytes(10));
        $this->repository->createConfirmEmailUrl($user, $randomString);

        dispatch(new MailJob([
            'email' => $user->email,
            'title' => "Taskvel | Confirme seu email!",
            'text' => "Olá $user->name, para confirmar seu email, clique no link a seguir: http://localhost:8000/api/auth/users/$user->id/confirm?hash=$randomString"
        ]));

        return new UserResource($user);
    }

    public function update(int $id, mixed $data): UserResource {
        $user = $this->repository->show($id);
        $data['password'] = bcrypt($data['password']);

        return new UserResource($this->repository->update($user, $data));
    }

    public function confirmEmail(int $id, string $url) {
        $this->repository->confirmEmail($id, $url);

        $user = $this->repository->show($id);

        dispatch(new MailJob([
            'email' => $user->email,
            'title' => "Taskvel | Conta confirmada com sucesso!",
            'text' => "Olá $user->name, sua conta foi confirmada com sucesso no Taskvel! Sinta-se livre para organizar suas tarefas no nosso serviço!"
        ]));
    }

    public function destroy(int $id): void {
        $this->repository->destroy($id);
    }
}
