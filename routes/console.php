<?php

use Illuminate\Support\Facades\Schedule;
use App\Jobs\DailyMatchSummaryJob;

// Check inactive matches every 5 minutes
Schedule::command('matches:check-inactive')->everyFiveMinutes();

// Daily summary at 11 PM
Schedule::job(new DailyMatchSummaryJob)->dailyAt('23:00');

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
