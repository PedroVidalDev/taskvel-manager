<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskUpdateHistoric extends Model
{
    protected $table = 'task_update_historic';

    protected $fillable = [
        'task_id',
    ];

    public function task(): BelongsTo {
        return $this->belongsTo(Task::class);
    }
}
