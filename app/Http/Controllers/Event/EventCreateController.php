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
     * @var EventCreateUseCase イベント作成に使用するUseCaseのインスタンス
     */
    private $eventCreateUseCase;

    /**
     * EventCreateControllerの新しいインスタンスを作成します。
     *
     * @param EventCreateUseCase $eventCreateUseCase イベント作成に使用するUseCaseのインスタンス
     */
    public function __construct(EventCreateUseCase $eventCreateUseCase)
    {
        $this->eventCreateUseCase = $eventCreateUseCase;
    }

    /**
     * イベント作成フォームの表示
     *
     * イベント作成フォームを表示します。
     *
     * @return View イベント作成フォームのビュー
     */
    public function create(): View
    {
        return $this->eventCreateUseCase->create();
    }

    /**
     * イベントの作成
     *
     * リクエストから受け取ったデータを検証し、新しいイベントを作成します。
     *
     * @param CreateRequest $request イベント作成のためのリクエスト
     * @return RedirectResponse 作成されたイベントの詳細ページへのリダイレクトレスポンス
     */
    public function store(CreateRequest $request): RedirectResponse
    {
        $event_id = $this->eventCreateUseCase->store($request);
        return redirect()->route('event.show', ['event_id' => $event_id])
            ->with('status', 'event-create');
    }
}
