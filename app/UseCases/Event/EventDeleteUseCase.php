<?php

namespace App\UseCases\Event;

use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\Storage;

class EventDeleteUseCase
{
    /**
     * イベントの削除
     *
     * 指定されたイベントを削除します。参加者がいる場合は削除できません。
     * 関連する画像ファイルも削除されます。
     *
     * @param  int  $id
     * @return bool true if the event was successfully deleted; otherwise, false.
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
