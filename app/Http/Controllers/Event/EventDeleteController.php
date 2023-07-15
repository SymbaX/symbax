<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\UseCases\Event\EventDeleteUseCase;
use Illuminate\Http\RedirectResponse;

class EventDeleteController extends Controller
{
    /**
     * イベントの削除
     *
     * 指定されたイベントを削除します。参加者がいる場合は削除できません。
     * 関連する画像ファイルも削除されます。
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $eventDeleteUseCase = new EventDeleteUseCase();
        $deleted = $eventDeleteUseCase->deleteEvent($id);

        if ($deleted) {
            return redirect()->route('index.upcoming')->with('status', 'event-deleted');
        } else {
            return redirect()->route('event.show', ['event_id' => $id])->with('status', 'cannot-delete-with-participants');
        }
    }
}
