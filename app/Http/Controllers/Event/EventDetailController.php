<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\UseCases\Event\EventDetailUseCase;

/**
 * イベント詳細表示コントローラー
 *
 * イベントの詳細情報を表示するアクションを提供するコントローラーです。
 */
class EventDetailController extends Controller
{
    /**
     * @var EventDetailUseCase
     */
    private $eventDetailUseCase;

    /**
     * コンストラクタ
     *
     * @param EventDetailUseCase $eventDetailUseCase イベント詳細情報取得に使用するUseCase
     */
    public function __construct(EventDetailUseCase $eventDetailUseCase)
    {
        $this->eventDetailUseCase = $eventDetailUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * イベントの詳細表示
     *
     * 指定されたイベントの詳細情報を表示します。
     *
     * @param int $id 表示するイベントのID
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $eventDetail = $this->eventDetailUseCase->getEventDetail($id);

        return view('event.detail', $eventDetail);
    }
}
