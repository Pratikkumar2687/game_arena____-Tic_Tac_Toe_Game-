<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameMatch extends Model
{
    protected $fillable = [
        'game_id',
        'player_one_id',
        'player_two_id',
        'current_turn_user_id',
        'state',
        'status',
        'winner_user_id',
        'last_move_at',
    ];

    protected $casts = [
        'state' => 'array',
        'last_move_at' => 'datetime',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function playerOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_one_id');
    }

    public function playerTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'player_two_id');
    }

    public function currentTurnUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_turn_user_id');
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'winner_user_id');
    }

    public function moves(): HasMany
    {
        return $this->hasMany(Move::class);
    }

    public function isPlayerTurn(User $user): bool
    {
        return $this->current_turn_user_id === $user->id;
    }
}
