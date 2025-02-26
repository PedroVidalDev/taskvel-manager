<?php

namespace App\Http\Repositories;

use App\Models\Comment;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Database\Eloquent\Collection;

class CommentRepository {

    public function index(): Collection {
        return Comment::all();
    }

    public function existsByColumn(string $column, mixed $value): bool {
        return Comment::where($column, $value)->exists();
    }

    public function show(int $id): Comment {
        return Comment::find($id);
    }

    public function store($data): Comment {
        return Comment::create($data);
    }

    public function update($comment, $data): Comment {
        $comment->update($data);

        return $comment;
    }

    public function destroy(int $id): void {
        Comment::destroy($id);
    }


}
