<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\UseCases\Event\EventStatusUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class EventStatusController extends Controller
{
    /**
     * @var EventStatusUseCase
     */
    private $eventStatusUseCase;

    /**
     * EventStatusUseCaseの新しいインスタンスを作成します。
     *
     * @param  EventStatusUseCase  $eventStatusUseCase
     * @return void
     */
    public function __construct(EventStatusUseCase $eventStatusUseCase)
    {
        $this->eventStatusUseCase = $eventStatusUseCase;
    }

    /**
     * イベントへの参加リクエスト
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントに参加リクエストを送信します。
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function joinRequest(Request $request): RedirectResponse
    {
        $status = $this->eventStatusUseCase->joinRequest($request);
        $event_id = $request->input('event_id');
        return redirect()->route('event.show', ['id' => $event_id])->with('status', $status);
    }

    /**
     * イベントへの参加のキャンセル
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントへの参加をキャンセルします。
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function cancelJoin(Request $request): RedirectResponse
    {
        $status = $this->eventStatusUseCase->cancelJoin($request);
        $event_id = $request->input('event_id');
        return redirect()->route('event.show', ['id' => $event_id])->with('status', $status);
    }

    /**
     * イベントへの参加ステータスを変更
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントへの参加ステータスを変更します。
     *
     * @param  Request  $request
     * @return RedirectResponse
     */
    public function changeStatus(Request $request): RedirectResponse
    {
        $status = $this->eventStatusUseCase->changeStatus($request);
        $event_id = $request->input('event_id');
        return redirect()->route('event.show', ['id' => $event_id])->with('status', $status);
    }
}
