<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\UseCases\Event\EventListUseCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * イベント一覧表示コントローラー
 *
 * イベントの一覧表示に関連するアクションを提供するコントローラーです。
 */
class EventListController extends Controller
{
    /**
     * イベント一覧表示のビジネスロジックを提供するユースケース
     * 
     * @var EventListUseCase イベント一覧表示に使用するUseCaseインスタンス
     */
    private $eventListUseCase;

    /**
     * EventListControllerのコンストラクタ
     *
     * 使用するユースケースをインジェクション（注入）します。
     * 
     * @param EventListUseCase $eventListUseCase イベント一覧取得に使用するUseCaseのインスタンス
     */
    public function __construct(EventListUseCase $eventListUseCase)
    {
        $this->eventListUseCase = $eventListUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * 今日以降のイベント一覧を表示するメソッド
     * 
     * 今日以降に開催されるイベントを取得し、ビューに渡して表示します。
     * 
     * @return View イベント一覧画面のビュー
     */
    public function indexHome(): View
    {
        $events = $this->eventListUseCase->getUpcomingEvents();
        $newest_events = $this->eventListUseCase->getNewestEvents();
        return view('event.list-home', ['events' => $events, 'newest_events' => $newest_events]);
    }

    /**
     * 全てのイベント一覧を表示するメソッド
     * 
     * 登録されている全てのイベントを取得し、ビューに渡して表示します。
     * 
     * @return View イベント一覧画面のビュー
     */
    public function indexAll(): View
    {
        $events = $this->eventListUseCase->getAllEvents();
        return view('event.list-all', ['events' => $events]);
    }

    /**
     * ユーザーが参加しているイベント一覧を表示するメソッド
     * 
     * ログイン中のユーザーが参加しているイベントを取得し、ビューに渡して表示します。
     * 
     * @return View イベント一覧画面のビュー
     */
    public function indexJoin(): View
    {
        $user = Auth::user();
        $events = $this->eventListUseCase->getJoinedEvents($user);
        return view('event.list-join', ['events' => $events]);
    }

    /**
     * ユーザーが作成したイベント一覧を表示するメソッド
     * 
     * ログイン中のユーザーが作成したイベントを取得し、ビューに渡して表示します。
     * 
     * @return View イベント一覧画面のビュー
     */
    public function indexOrganizer(): View
    {
        $user = Auth::user();
        $events = $this->eventListUseCase->getOrganizedEvents($user);
        return view('event.list-organizer', ['events' => $events]);
    }
}
