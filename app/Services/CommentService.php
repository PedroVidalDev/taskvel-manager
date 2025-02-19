<?php

namespace App\Services;

use App\Http\Repositories\CommentRepository;
use App\Http\Resources\CommentResource;

class CommentService {

    public function __construct(private readonly CommentRepository $repository) {}

    public function show(int $id): CommentResource {
        return new CommentResource($this->repository->show($id));
    }

    public function update(int $id, mixed $data): CommentResource {
        $comment = $this->repository->show($id);

        return new CommentResource($this->repository->update($comment, $data));
    }

    public function store(mixed $data): CommentResource {
        return new CommentResource($this->repository->store($data));
    }

    public function destroy(int $id): void {
        $this->repository->destroy($id);
    }
}
