<?php

namespace App\UseCases\Event;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;

/**
 * イベント作成者をチェックするUseCase
 */
class CheckEventOrganizerUseCase
{
    /**
     * イベント作成者であるかどうかをチェックします。
     *
     * @param int $eventId イベントID
     * @return bool|null イベント作成者の場合はtrue、ユーザーが認証済みでない場合はnull、イベントが存在しない場合はnullを返します。
     */
    public function execute(int $eventId): ?bool
    {
        // ユーザーが認証済みでない場合はnullを返す
        if (!Auth::check()) {
            return null;
        }

        // イベントを取得し、存在しない場合はnullを返す
        $event = Event::find($eventId);
        if (!$event) {
            return null;
        }

        return $event->organizer_id === Auth::id();
    }
}
