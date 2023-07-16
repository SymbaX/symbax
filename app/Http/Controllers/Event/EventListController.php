<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\UseCases\Event\EventListUseCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EventListController extends Controller
{
    /**
     * @var EventListUseCase
     */
    private $eventListUseCase;

    /**
     * EventListUseCaseの新しいインスタンスを作成します。
     *
     * @param  EventListUseCase  $eventListUseCase
     * @return void
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
     * @return \Illuminate\View\View
     */
    public function indexUpcoming(): View
    {
        $events = $this->eventListUseCase->getUpcomingEvents();
        return view('event.list-upcoming', ['events' => $events]);
    }

    /**
     * 全てのイベント一覧の表示
     *
     * すべてのイベントを日付の降順でページネーションして表示します。
     *
     * @return \Illuminate\View\View
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
     * @return \Illuminate\View\View
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
     * @return \Illuminate\View\View
     */
    public function indexOrganizer(): View
    {
        $user = Auth::user();
        $events = $this->eventListUseCase->getOrganizedEvents($user);
        return view('event.list-organizer', ['events' => $events]);
    }
}
