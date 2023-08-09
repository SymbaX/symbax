<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\UseCases\Event\EventEditUseCase;
use App\Http\Requests\Event\UpdateRequest;
use Illuminate\Http\RedirectResponse;

/**
 * イベント編集コントローラー
 *
 * イベントの編集に関連するアクションを提供するコントローラーです。
 */
class EventEditController extends Controller
{
    /**
     * @var EventEditUseCase イベント編集に使用するUseCaseのインスタンス
     */
    private $eventEditUseCase;

    /**
     * コンストラクタ
     *
     * @param EventEditUseCase $eventEditUseCase イベント編集に使用するUseCaseのインスタンス
     */
    public function __construct(EventEditUseCase $eventEditUseCase)
    {
        $this->eventEditUseCase = $eventEditUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * イベントの編集画面を表示
     *
     * 指定されたイベントを編集するためのビューを表示します。
     * 現在のユーザーがイベントの作成者でない場合は編集できません。
     *
     * @param int $id 編集するイベントのID
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $view = $this->eventEditUseCase->edit($id);

        if (!$view) {
            return redirect()->route('event.show', ['event_id' => $id])->with('status', 'unauthorized');
        }

        return $view;
    }

    /**
     * イベントの更新
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントを更新します。
     * 現在のユーザーがイベントの作成者でない場合は更新できません。
     * 画像がアップロードされた場合は既存の画像を削除して新しい画像を保存します。
     *
     * @param UpdateRequest $request イベント更新のためのリクエスト
     * @param int $id 更新するイベントのID
     * @return RedirectResponse イベント詳細ページへのリダイレクトレスポンス
     */
    public function update(UpdateRequest $request, $id): RedirectResponse
    {
        $updated = $this->eventEditUseCase->updateEvent($id, $request);

        if (!$updated) {
            return redirect()->route('event.show', ['event_id' => $id])->with('status', 'unauthorized');
        }

        return redirect()->route('event.show', ['event_id' => $id])->with('status', 'event-updated');
    }
}
