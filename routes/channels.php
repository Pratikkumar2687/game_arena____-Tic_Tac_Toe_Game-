<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\GameMatch;

Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('match.{matchId}', function ($user, $matchId) {
    $match = GameMatch::find($matchId);

    return $match && (
        $user->id === $match->player_one_id ||
        $user->id === $match->player_two_id
    );
});

// Default Laravel user channel (optional but safe)
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});