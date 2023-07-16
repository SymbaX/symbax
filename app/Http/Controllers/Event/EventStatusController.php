<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\UseCases\Event\EventStatusUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

/**
 * イベントステータスコントローラー
 *
 * イベントへの参加ステータスに関連するアクションを提供するコントローラーです。
 */
class EventStatusController extends Controller
{
    /**
     * @var EventStatusUseCase イベントステータスの操作に使用するUseCaseのインスタンス
     */
    private $eventStatusUseCase;

    /**
     * EventStatusControllerの新しいインスタンスを作成します。
     *
     * @param EventStatusUseCase $eventStatusUseCase イベントステータスの操作に使用するUseCaseのインスタンス
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
     * @param Request $request 参加リクエストのためのリクエストデータ
     * @return RedirectResponse イベント詳細ページへのリダイレクトレスポンス
     */
    public function joinRequest(Request $request): RedirectResponse
    {
        $status = $this->eventStatusUseCase->joinRequest($request);
        $event_id = $request->input('event_id');
        return redirect()->route('event.show', ['event_id' => $event_id])->with('status', $status);
    }

    /**
     * イベントへの参加のキャンセル
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントへの参加をキャンセルします。
     *
     * @param Request $request キャンセルリクエストのためのリクエストデータ
     * @return RedirectResponse イベント詳細ページへのリダイレクトレスポンス
     */
    public function cancelJoin(Request $request): RedirectResponse
    {
        $status = $this->eventStatusUseCase->cancelJoin($request);
        $event_id = $request->input('event_id');
        return redirect()->route('event.show', ['event_id' => $event_id])->with('status', $status);
    }

    /**
     * イベントへの参加ステータスを変更
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントへの参加ステータスを変更します。
     *
     * @param Request $request ステータス変更リクエストのためのリクエストデータ
     * @return RedirectResponse イベント詳細ページへのリダイレクトレスポンス
     */
    public function changeStatus(Request $request): RedirectResponse
    {
        $status = $this->eventStatusUseCase->changeStatus($request);
        $event_id = $request->input('event_id');
        return redirect()->route('event.show', ['event_id' => $event_id])->with('status', $status);
    }
}
