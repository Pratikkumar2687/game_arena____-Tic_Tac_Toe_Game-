<?php

namespace App\Console\Commands;

use App\Jobs\InactiveMatchReminderJob;
use App\Models\GameMatch;
use Illuminate\Console\Command;

class CheckInactiveMatches extends Command
{
    protected $signature = 'matches:check-inactive';
    protected $description = 'Check for inactive matches and send reminders';

    public function handle()
    {
        $inactiveMatches = GameMatch::where('status', 'active')
            ->where('last_move_at', '<=', now()->subMinutes(10))
            ->get();

        foreach ($inactiveMatches as $match) {
            InactiveMatchReminderJob::dispatch($match);
        }

        $this->info("Checked {$inactiveMatches->count()} inactive matches");
    }
}
