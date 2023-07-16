<?php

namespace App\UseCases\Event;

use App\Models\EventParticipant;
use Illuminate\Support\Facades\Auth;

class CheckEventParticipantStatusUseCase
{
    /**
     * イベント参加者のステータスをチェックします。
     *
     * @param int $eventId イベントID
     * @return string|null
     */
    public function execute(int $eventId): ?string
    {
        $participant = EventParticipant::where('event_id', $eventId)
            ->where('user_id', Auth::id())
            ->first();

        return $participant ? $participant->status : null;
    }
}
