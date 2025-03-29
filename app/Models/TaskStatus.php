<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskStatus extends Model
{
    protected $table = 'task_status';

    protected $fillable = ['name', 'project_id', 'created_at', 'updated_at'];

    public function project(): BelongsTo {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function tasks(): HasMany {
        return $this->hasMany(Task::class, 'status', 'id');
    }
}
