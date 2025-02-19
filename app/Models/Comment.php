<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'comments';

    protected $fillable = ['content', 'user_id', 'task_id', 'created_at', 'updated_at'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function task() {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
