<?php

namespace App\UseCases\Event;

use App\Http\Requests\Event\UpdateRequest;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\UseCases\OperationLog\OperationLogUseCase;

/**
 * イベント編集ユースケース
 *
 * イベントの編集に関するユースケースを提供するクラスです。
 */
class EventEditUseCase
{
    /**
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * OperationLogUseCaseの新しいインスタンスを作成します。
     *
     * @param  OperationLogUseCase  $operationLogUseCase
     * @return void
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /**
     * イベントの編集可能なインスタンスを取得します。
     *
     * 指定されたイベントIDに基づいて、編集可能なイベントインスタンスを取得します。
     * 現在のユーザーがイベントの作成者でない場合、編集できない場合はnullを返します。
     *
     * @param int $id イベントID
     * @return Event|null 編集可能なイベントインスタンスまたはnull
     */
    public function getEditableEvent($id): ?Event
    {
        $event = Event::findOrFail($id);

        if ($event->organizer_id !== Auth::id()) {
            return null;
        }

        return $event;
    }

    /**
     * イベントの更新を行います。
     *
     * 指定されたイベントIDに基づいて、イベントのデータをリクエストから受け取ったデータで更新します。
     * 現在のユーザーがイベントの作成者でない場合、または更新に失敗した場合はfalseを返します。
     * 画像がアップロードされた場合は、既存の画像を削除して新しい画像を保存します。
     *
     * @param int $id イベントID
     * @param UpdateRequest $request 更新リクエスト
     * @return bool 更新が成功した場合はtrue、それ以外の場合はfalse
     */
    public function updateEvent($id, UpdateRequest $request): bool
    {
        // イベント作成者であるかどうかをチェック
        $eventOrganizerUseCase = new CheckEventOrganizerUseCase();
        $isEventOrganizer = $eventOrganizerUseCase->execute($id);

        // ユーザーがイベント作成者でない場合はエラーとする
        if (!$isEventOrganizer) {
            return false;
        }

        $event = $this->getEditableEvent($id);

        if (!$event) {
            return false;
        }

        $validatedData = $request->validated();

        if ($request->hasFile('image_path')) {
            Storage::delete($event->image_path);
            $validatedData['image_path'] = $request->file('image_path')->store('public/events');
        } else {
            $validatedData['image_path'] = $event->image_path; // 元の画像パスを使用
        }

        $event->update($validatedData);

        $this->operationLogUseCase->store([
            'detail' => 'イベントを編集しました',
            'user_id' => null,
            'target_event_id' => $event->id,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'event-update',
            'ip' => request()->ip(),
        ]);

        return true;
    }
}
