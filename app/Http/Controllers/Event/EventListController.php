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
     * @var EventListUseCase イベント一覧取得に使用するUseCaseのインスタンス
     */
    private $eventListUseCase;

    /**
     * EventListControllerの新しいインスタンスを作成します。
     *
     * @param EventListUseCase $eventListUseCase イベント一覧取得に使用するUseCaseのインスタンス
     */
    public function __construct(EventListUseCase $eventListUseCase)
    {
        $this->eventListUseCase = $eventListUseCase;
    }

    /**
     * イベント一覧の表示
     *
     * 今日以降の日付で開催されるイベントを日付の降順でページネーションして表示します。
     *
     * @return View イベント一覧のビュー
     */
    public function indexHome(): View
    {
        $events = $this->eventListUseCase->getUpcomingEvents();
        $newest_events = $this->eventListUseCase->getNewestEvents();
        return view('event.list-home', ['events' => $events, 'newest_events' => $newest_events]);
    }

    /**
     * 全てのイベント一覧の表示
     *
     * すべてのイベントを日付の降順でページネーションして表示します。
     *
     * @return View イベント一覧のビュー
     */
    public function indexAll(): View
    {
        $events = $this->eventListUseCase->getAllEvents();
        return view('event.list-all', ['events' => $events]);
    }

    /**
     * 参加しているイベント一覧の表示
     *
     * 参加しているイベントを日付の降順でページネーションして表示します。
     *
     * @return View イベント一覧のビュー
     */
    public function indexJoin(): View
    {
        $user = Auth::user();
        $events = $this->eventListUseCase->getJoinedEvents($user);
        return view('event.list-join', ['events' => $events]);
    }

    /**
     * 作成したイベント一覧の表示
     *
     * ユーザーが作成したイベントを日付の降順でページネーションして表示します。
     *
     * @return View イベント一覧のビュー
     */
    public function indexOrganizer(): View
    {
        $user = Auth::user();
        $events = $this->eventListUseCase->getOrganizedEvents($user);
        return view('event.list-organizer', ['events' => $events]);
    }
}
