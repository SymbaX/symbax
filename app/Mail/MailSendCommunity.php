<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailSendCommunity extends Mailable
{
    use Queueable, SerializesModels;

    public function eventMention($event_name, $event_id, $send_name)
    {
        $buttonUrl = config('app.url') . '/event/' . $event_id . '/community';

        return $this->markdown('emails.event-mention')
            ->subject('メンションされました')
            ->with([
                'event_name' => $event_name,
                'send_name' => $send_name,
                'buttonUrl' => $buttonUrl,
            ]);
    }
}
