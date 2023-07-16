<?php

namespace App\UseCases\Event;

use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\Storage;

/**
 * イベント削除ユースケース
 *
 * イベントの削除に関するビジネスロジックを提供するユースケースクラスです。
 */
class EventDeleteUseCase
{
    /**
     * イベントの削除
     *
     * 指定されたイベントを削除します。参加者がいる場合は削除できません。
     * 関連する画像ファイルも削除されます。
     *
     * @param  int  $id イベントID
     * @return bool イベントが正常に削除された場合はtrue、削除できない場合はfalseを返します。
     */
    public function deleteEvent($id)
    {
        $event = Event::findOrFail($id);

        // イベントに参加者が登録されているかどうかを確認します
        $participantCount = EventParticipant::where('event_id', $id)->count();
        if ($participantCount > 0) {
            return false;
        }

        // イベントを削除し、関連する画像ファイルを削除します
        Storage::delete($event->image_path);
        $event->delete();

        return true;
    }
}
