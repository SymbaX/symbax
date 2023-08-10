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
     * イベント詳細のビジネスロジックを提供するユースケース
     * 
     * @var EventDetailUseCase イベント詳細情報取得に使用するUseCaseインスタンス
     */
    private $eventDetailUseCase;

    /**
     * EventDetailControllerのコンストラクタ
     *
     * 使用するユースケースをインジェクション（注入）します。
     * 
     * @param EventDetailUseCase $eventDetailUseCase イベント詳細情報取得に使用するUseCase
     */
    public function __construct(EventDetailUseCase $eventDetailUseCase)
    {
        $this->eventDetailUseCase = $eventDetailUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * イベントの詳細情報を表示するメソッド
     * 
     * 指定されたイベントIDに関連する詳細情報を取得し、ビューに渡して表示します。
     *
     * @param int $id 表示するイベントのID
     * @return View イベント詳細画面のビュー
     */
    public function show($id)
    {
        $eventDetail = $this->eventDetailUseCase->getEventDetail($id);

        return view('event.detail', $eventDetail);
    }
}
