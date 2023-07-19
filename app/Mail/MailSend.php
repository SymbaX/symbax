<?php

namespace App\Mail;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailSend extends Mailable
{
    use Queueable, SerializesModels;

    public $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function eventJoinRequest($event)
    {
        $this->event = $event;
        $buttonUrl = config('app.url') . '/event/' . $this->event->id;

        return $this->markdown('emails.join-request')
            ->subject('イベント参加リクエスト')
            ->with([
                'buttonUrl' => $buttonUrl,
                'event_name' => $this->event->name,
            ]);
    }

    public function eventChangeStatus($event, $status)
    {
        $this->event = $event;
        $buttonUrl = config('app.url') . '/event/' . $this->event->id;

        if ($status == 'approved') {
            return $this->markdown('emails.change-status-approved')
                ->subject('イベント参加リクエストが承認されました')
                ->with([
                    'buttonUrl' => $buttonUrl,
                    'event_name' => $this->event->name,
                ]);
        } elseif ($status == 'rejected') {
            return $this->markdown('emails.change-status-rejected')
                ->subject('イベント参加リクエストが却下されました')
                ->with([
                    'buttonUrl' => $buttonUrl,
                    'event_name' => $this->event->name,
                ]);
        }
    }
}
