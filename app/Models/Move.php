<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Move extends Model
{
    protected $fillable = ['match_id', 'user_id', 'move_data'];

    protected $casts = [
        'move_data' => 'array',
    ];

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
