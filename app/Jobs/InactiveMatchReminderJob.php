<?php

namespace App\Jobs;

use App\Mail\InactiveMatchReminder;
use App\Models\GameMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class InactiveMatchReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public GameMatch $match)
    {
    }

    public function handle(): void
    {
        // Check if match is still active and inactive for 10+ minutes
        if (
            $this->match->status === 'active' &&
            $this->match->last_move_at->diffInMinutes(now()) >= 10
        ) {
            Mail::to($this->match->playerOne->email)
                ->send(new InactiveMatchReminder($this->match));

            Mail::to($this->match->playerTwo->email)
                ->send(new InactiveMatchReminder($this->match));
        }
    }
}
