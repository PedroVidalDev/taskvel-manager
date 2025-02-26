<?php

namespace App\Http\Repositories;

use App\Models\Comment;
use Illuminate\Contracts\Queue\EntityNotFoundException;
use Illuminate\Database\Eloquent\Collection;

class CommentRepository {

    public function index(): Collection {
        return Comment::all();
    }

    public function show(int $id): Comment {
        if(!Comment::where('id', $id)->exists()) {
            throw new EntityNotFoundException('Comment', $id);
        }
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
        if(!Comment::where('id', $id)->exists()) {
            throw new EntityNotFoundException('Comment', $id);
        }
        Comment::destroy($id);
    }


}
