<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $table = 'tasks';

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'due_date',
        'created_at',
        'updated_at'
    ];

    public function subtasks(): BelongsToMany {
        return $this->belongsToMany(Task::class, 'subtasks_relation', 'main_task_id', 'sub_task_id');
    }

    public function mainTask(): BelongsToMany {
        return $this->belongsToMany(Task::class, 'subtasks_relation', 'sub_task_id', 'main_task_id');
    }

    public function comments(): HasMany {
        return $this->hasMany(Comment::class, 'task_id', 'id');
    }
}
