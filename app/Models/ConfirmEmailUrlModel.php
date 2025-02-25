<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConfirmEmailUrlModel extends Model
{
    protected $table = 'confirm_email_urls';

    protected $fillable = [
        'user_id',
        'url',
        'is_used'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
