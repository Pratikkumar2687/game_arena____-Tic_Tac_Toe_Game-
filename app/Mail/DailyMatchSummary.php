<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyMatchSummary extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public int $totalMatches,
        public $winsPerUser,
        public int $abandonedMatches
    ) {
    }

    public function build()
    {
        return $this->subject('Daily Match Summary')
            ->markdown('emails.daily-match-summary');
    }
}
