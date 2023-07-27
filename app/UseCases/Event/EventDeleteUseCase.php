<?php

namespace App\UseCases\Event;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Services\CheckEventOrganizerService;
use Illuminate\Support\Facades\Storage;
use App\UseCases\OperationLog\OperationLogUseCase;

/**
 * イベント削除ユースケース
 *
 * イベントの削除に関するビジネスロジックを提供するユースケースクラスです。
 */
class EventDeleteUseCase
{
    /**
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * @var CheckEventOrganizerService
     */
    private $checkEventOrganizerService;

    /**
     * OperationLogUseCaseの新しいインスタンスを作成します。
     *
     * @param  OperationLogUseCase  $operationLogUseCase
     * @param  CheckEventOrganizerService  $checkEventOrganizerService
     * @return void
     */
    public function __construct(OperationLogUseCase $operationLogUseCase, CheckEventOrganizerService $checkEventOrganizerService)
    {
        $this->operationLogUseCase = $operationLogUseCase;
        $this->checkEventOrganizerService = $checkEventOrganizerService;
    }


    /**
     * イベントの削除
     *
     * 指定されたイベントを削除します。参加者がいる場合は削除できません。
     * 関連する画像ファイルも削除されます。
     *
     * @param  int  $event_id イベントID
     * @return bool イベントが正常に削除された場合はtrue、削除できない場合はfalseを返します。
     */
    public function deleteEvent($event_id)
    {
        $event = Event::findOrFail($event_id);

        if (!$this->checkEventOrganizerService->check($event_id)) {    // イベント作成者ではない場合
            return false;
        }

        // イベントに参加者が登録されているかどうかを確認します
        $participantCount = EventParticipant::where('event_id', $event_id)
            ->where(function ($query) {
                $query->where('status', 'approved')
                    ->orWhere('status', 'pending');
            })->count();

        if ($participantCount > 0) {
            return false;
        }

        // イベントを削除し、関連する画像ファイルを削除します
        Storage::delete($event->image_path);
        $event->delete();

        $this->operationLogUseCase->store([
            'detail' => null,
            'user_id' => null,
            'target_event_id' => $event_id,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'event-delete',
            'ip' => request()->ip(),
        ]);
        return true;
    }
}
