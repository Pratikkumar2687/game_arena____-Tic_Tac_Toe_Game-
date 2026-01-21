<?php

namespace App\Jobs;

use App\Mail\DailyMatchSummary;
use App\Models\GameMatch;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class DailyMatchSummaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $today = now()->startOfDay();

        // Total matches created today
        $totalMatches = GameMatch::whereDate('created_at', $today)->count();

        // Wins per user
        $winsPerUser = GameMatch::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->whereNotNull('winner_user_id')
            ->selectRaw('winner_user_id, count(*) as wins')
            ->groupBy('winner_user_id')
            ->get()
            ->mapWithKeys(function ($item) {
                $user = User::find($item->winner_user_id);
                return [$user->name => $item->wins];
            });

        // Abandoned matches
        $abandonedMatches = GameMatch::whereDate('created_at', $today)
            ->where('status', 'abandoned')
            ->count();

        // Send summary to admin
        $admin = User::where('email', 'admin@example.com')->first();

        if ($admin) {
            Mail::to($admin->email)->send(
                new DailyMatchSummary($totalMatches, $winsPerUser, $abandonedMatches)
            );
        }
    }
}
