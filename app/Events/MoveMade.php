<?php

namespace App\Events;

use App\Models\GameMatch;
use App\Models\Move;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MoveMade implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public GameMatch $match,
        public Move $move
    ) {
        $this->match->load(['game', 'playerOne', 'playerTwo']);
        $this->move->load('user');
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('match.' . $this->match->id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'match' => $this->match,
            'move' => $this->move,
        ];
    }
}
