<?php

namespace App\UseCases\Event;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class CheckEventOrganizerUseCase
{
    /**
     * イベント作成者であるかどうかをチェックします。
     *
     * @param int $eventId イベントID
     * @return bool
     */
    public function execute(int $eventId): bool
    {
        $event = Event::findOrFail($eventId);

        return $event->organizer_id === Auth::id();
    }
}
