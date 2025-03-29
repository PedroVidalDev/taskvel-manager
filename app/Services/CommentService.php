<?php

namespace App\Services;

use App\Http\Repositories\CommentRepository;
use App\Http\Resources\Comment\CommentResource;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CommentService {

    public function __construct(private readonly CommentRepository $repository) {}

    public function update(int $id, mixed $data): CommentResource {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Comment', $id);
        }

        $comment = $this->repository->show($id);

        $data['user_id'] = auth()->user()->id;

        return new CommentResource($this->repository->update($comment, $data));
    }

    public function store(mixed $data): CommentResource {
        $data['user_id'] = auth()->user()->id;
        return new CommentResource($this->repository->store($data));
    }

    public function destroy(int $id): void {
        if(!$this->repository->existsByColumn('id', $id)) {
            throw new EntityNotFoundException('Comment', $id);
        }
        $this->repository->destroy($id);
    }
}
