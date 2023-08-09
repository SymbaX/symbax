<?php

namespace App\UseCases\Event;

use App\Models\EventParticipant;
use Illuminate\Support\Facades\Auth;

/**
 * イベント参加者のステータスをチェックするUseCase
 */
class CheckEventParticipantStatusUseCase
{
    /* =================== 以下メインの処理 =================== */

    /**
     * イベント参加者のステータスをチェックします。
     *
     * @param int $eventId イベントID
     * @return string|null イベント参加者のステータス（"approved"、"pending"、"rejected"のいずれか）を返します。
     *                     ユーザーが認証済みでない場合はnullを返します。
     */
    public function execute(int $eventId): ?string
    {
        // ユーザーが認証済みでない場合はnullを返す
        if (!Auth::check()) {
            return null;
        }

        $participant = EventParticipant::where('event_id', $eventId)
            ->where('user_id', Auth::id())
            ->first();

        return $participant ? $participant->status : null;
    }
}
