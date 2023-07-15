<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OperationLogController;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\Storage;

class DeleteController extends Controller
{
    /**
     * @var OperationLogController
     */
    private $operationLogController;

    /**
     * OperationLogControllerの新しいインスタンスを作成します。
     *
     * @param  OperationLogController  $operationLogController
     * @return void
     */
    public function __construct(OperationLogController $operationLogController)
    {
        $this->operationLogController = $operationLogController;
    }

    /**
     * イベントの削除
     *
     * 指定されたイベントを削除します。参加者がいる場合は削除できません。
     * 関連する画像ファイルも削除されます。
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $event = Event::findOrFail($id);

        // イベントに参加者が登録されているかどうかを確認します
        $participantCount = EventParticipant::where('event_id', $id)->count();
        if ($participantCount > 0) {
            return redirect()->route('event.detail', ['id' => $id])->with('status', 'cannot-delete-with-participants');
        }

        // イベントを削除し、関連する画像ファイルを削除します
        Storage::delete($event->image_path);
        $event->delete();

        $this->operationLogController->store('ID:' . $event->id . 'のイベントを削除しました');

        return redirect()->route('list.upcoming')->with('status', 'event-deleted');
    }
}
