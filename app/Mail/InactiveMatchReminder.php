<?php

namespace App\Mail;

use App\Models\GameMatch;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InactiveMatchReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public GameMatch $match)
    {
    }

    public function build()
    {
        return $this->subject('Your match is waiting!')
            ->markdown('emails.inactive-match-reminder');
    }

}
