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
     * イベント編集のビジネスロジックを提供するユースケース
     * 
     * @var EventEditUseCase イベント編集に使用するUseCaseインスタンス
     */
    private $eventEditUseCase;

    /**
     * EventEditControllerのコンストラクタ
     *
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param EventEditUseCase $eventEditUseCase イベント編集に使用するUseCaseのインスタンス
     */
    public function __construct(EventEditUseCase $eventEditUseCase)
    {
        $this->eventEditUseCase = $eventEditUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * イベント編集画面を表示するメソッド
     * 
     * 指定されたイベントIDに関連する編集画面を表示します。
     * ただし、編集権限がない場合はエラーメッセージとともに詳細ページにリダイレクトします。
     *
     * @param int $id 編集対象のイベントID
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
     * イベント情報を更新するメソッド
     * 
     * 指定されたイベントIDの情報をリクエストに基づき更新します。
     * 更新権限がない場合や、更新に失敗した場合はエラーメッセージとともに詳細ページにリダイレクトします。
     *
     * @param UpdateRequest $request イベント情報更新のためのリクエスト
     * @param int $id 更新対象のイベントID
     * @return RedirectResponse イベント詳細ページへのリダイレクト
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
