<?php

namespace App\Events;

use App\Models\GameMatch;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;


class MatchCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public GameMatch $match)
    {
        $this->match->load(['game', 'playerOne', 'playerTwo']);
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('user.' . $this->match->player_two_id),
        ];
    }

    public function broadcastWith(): array
    {
        return ['match' => $this->match];
    }

}
