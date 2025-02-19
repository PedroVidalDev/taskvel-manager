<?php

namespace App\Services;

use App\Http\Repositories\UserRepository;
use App\Http\Resources\User\UserResource;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService {

    public function __construct(private readonly UserRepository $repository) {}

    public function login(mixed $data): array {
        if(!$token = JWTAuth::attempt($data)) {
            throw new JWTException("Invalid credentials");
        }

        return ['token' => $token];
    }

    public function store(mixed $data): UserResource {
        $data['password'] = bcrypt($data['password']);

        return new UserResource($this->repository->store($data));
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
