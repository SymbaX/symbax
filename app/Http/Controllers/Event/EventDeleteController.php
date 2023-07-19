<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\UseCases\Event\EventDeleteUseCase;
use Illuminate\Http\RedirectResponse;

/**
 * イベント削除コントローラー
 *
 * イベントの削除に関連するアクションを提供するコントローラーです。
 */
class EventDeleteController extends Controller
{
    /**
     * @var EventDeleteUseCase
     */
    private $eventDeleteUseCase;

    /**
     * EventDeleteControllerの新しいインスタンスを作成します。
     *
     * @param EventDeleteUseCase $eventDeleteUseCase イベント削除に使用するUseCase
     */
    public function __construct(EventDeleteUseCase $eventDeleteUseCase)
    {
        $this->eventDeleteUseCase = $eventDeleteUseCase;
    }

    /**
     * イベントの削除
     *
     * 指定されたイベントを削除します。参加者がいる場合は削除できません。
     * 関連する画像ファイルも削除されます。
     *
     * @param int $id 削除対象のイベントID
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function deleteEvent($id): RedirectResponse
    {
        $deleted = $this->eventDeleteUseCase->deleteEvent($id);

        if ($deleted) {
            return redirect()->route('index.upcoming')->with('status', 'event-deleted');
        } else {
            return redirect()->route('event.show', ['event_id' => $id])->with('status', 'cannot-delete-event');
        }
    }
}
