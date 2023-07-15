<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\UseCases\Event\EventEditUseCase;
use App\Http\Requests\Event\UpdateRequest;
use Illuminate\Http\RedirectResponse;

class EventEditController extends Controller
{
    /**
     * @var EventEditUseCase
     */
    private $eventEditUseCase;

    /**
     * EventEditUseCaseの新しいインスタンスを作成します。
     *
     * @param  EventEditUseCase  $eventEditUseCase
     * @return void
     */
    public function __construct(EventEditUseCase $eventEditUseCase)
    {
        $this->eventEditUseCase = $eventEditUseCase;
    }

    /**
     * イベントの編集
     *
     * 指定されたイベントを編集するためのビューを表示します。
     * 現在のユーザーがイベントの作成者でない場合は編集できません。
     *
     * @param  int  $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $event = $this->eventEditUseCase->getEditableEvent($id);

        if (!$event) {
            return redirect()->route('event.show', ['event_id' => $id])->with('status', 'unauthorized');
        }

        return view('event.edit', ['event' => $event]);
    }

    /**
     * イベントの更新
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントを更新します。
     * 現在のユーザーがイベントの作成者でない場合は更新できません。
     * 画像がアップロードされた場合は既存の画像を削除して新しい画像を保存します。
     *
     * @param  UpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, $id)
    {
        $updated = $this->eventEditUseCase->updateEvent($id, $request);

        if (!$updated) {
            return redirect()->route('event.show', ['event_id' => $id])->with('status', 'unauthorized');
        }

        return redirect()->route('event.show', ['event_id' => $id])->with('status', 'event-updated');
    }
}
