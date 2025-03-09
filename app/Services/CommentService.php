<?php

namespace App\Services;

use App\Http\Repositories\CommentRepository;
use App\Http\Resources\Comment\CommentResource;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentService {

    public function __construct(private readonly CommentRepository $repository) {}

    public function index(): AnonymousResourceCollection {
        return CommentResource::collection($this->repository->index());
    }

    public function show(int $id): CommentResource {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Comment', $id);
        }
        return new CommentResource($this->repository->show($id));
    }

    public function update(int $id, mixed $data): CommentResource {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Comment', $id);
        }

        $comment = $this->repository->show($id);

        return new CommentResource($this->repository->update($comment, $data));
    }

    public function store(mixed $data): CommentResource {
        return new CommentResource($this->repository->store($data));
    }

    public function destroy(int $id): void {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Comment', $id);
        }
        $this->repository->destroy($id);
    }
}
