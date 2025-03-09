<?php

namespace App\Services;

use App\Http\Repositories\UserRepository;
use App\Http\Resources\User\UserResource;
use App\Jobs\MailJob;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class UserService {

    public function __construct(private readonly UserRepository $repository) {}

    public function login(mixed $data): array {
        if(!$token = JWTAuth::attempt($data)) {
            throw new JWTException("Invalid credentials");
        }

        return ['token' => $token];
    }

    public function store(mixed $data): UserResource {
        $data['password'] = Hash::make($data['password']);

        Log::info($data);

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
        if($this->repository->existsByColumn('id', $id) === false) {
            throw new EntityNotFoundException('User', $id);
        }

        $user = $this->repository->show($id);
        $data['password'] = Hash::make($data['password']);

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
        if($this->repository->existsByColumn('id', $id) === false) {
            throw new EntityNotFoundException('User', $id);
        }

        $this->repository->destroy($id);
    }
}
