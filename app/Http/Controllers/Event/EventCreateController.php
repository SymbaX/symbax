<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\CreateRequest;
use App\UseCases\Event\EventCreateUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * イベント作成コントローラー
 *
 * イベント作成に関連するアクションを提供するコントローラーです。
 */
class EventCreateController extends Controller
{
    /**
     * イベント作成のビジネスロジックを提供するユースケース
     * 
     * @var EventCreateUseCase イベント作成に使用するUseCaseインスタンス
     */
    private $eventCreateUseCase;

    /**
     * EventCreateControllerのコンストラクタ。
     *
     * 使用するユースケースをインジェクション（注入）します。
     * 
     * @param EventCreateUseCase $eventCreateUseCase イベント作成に使用するUseCaseのインスタンス
     */
    public function __construct(EventCreateUseCase $eventCreateUseCase)
    {
        $this->eventCreateUseCase = $eventCreateUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * イベント新規作成フォームを表示するメソッド
     *
     * @return View 
     */
    public function create(): View
    {
        return $this->eventCreateUseCase->create();
    }

    /**
     * 新しいイベントをデータベースに保存するメソッド
     *
     * フォームから受け取ったイベント情報を検証し、新しいイベントとしてデータベースに保存します。
     *
     * @param CreateRequest $request イベント情報を持つリクエストオブジェクト
     * @return RedirectResponse 新しいイベントの詳細ページへリダイレクトするレスポンス
     */
    public function store(CreateRequest $request): RedirectResponse
    {
        $event_id = $this->eventCreateUseCase->store($request);
        return redirect()->route('event.show', ['event_id' => $event_id])
            ->with('status', 'event-create');
    }
}
