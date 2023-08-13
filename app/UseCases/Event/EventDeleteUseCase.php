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
     * 操作ログを保存するためのビジネスロジックを提供するユースケース
     * 
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * イベントオーガナイザーを確認するためのサービス
     * 
     * @var CheckEventOrganizerService
     */
    private $checkEventOrganizerService;

    /**
     * EventDeleteUseCaseのコンストラクタ
     * 
     * 使用するユースケースとサービスをインジェクション（注入）します。
     * 
     * @param OperationLogUseCase $operationLogUseCase 操作ログに関するユースケース
     * @param CheckEventOrganizerService $checkEventOrganizerService イベントオーガナイザーを確認するためのサービス
     */
    public function __construct(OperationLogUseCase $operationLogUseCase, CheckEventOrganizerService $checkEventOrganizerService)
    {
        $this->operationLogUseCase = $operationLogUseCase;
        $this->checkEventOrganizerService = $checkEventOrganizerService;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * イベントの削除
     *
     * 指定されたイベントを削除します。参加者がいる場合は削除できません。
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

        // イベントに削除フラグを立てます
        $event->is_deleted = true;
        $event->save();

        // 操作ログを保存
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
