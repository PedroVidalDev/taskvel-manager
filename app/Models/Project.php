<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ScopedBy([UserScope::class])]
class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = ['name', 'user_id', 'created_at', 'updated_at'];

    public function tasks(): HasMany {
        return $this->hasMany(Task::class, 'project_id', 'id');
    }

    public function taskStatus(): HasMany {
        return $this->hasMany(TaskStatus::class, 'project_id', 'id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
