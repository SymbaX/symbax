<?php

namespace App\UseCases\Event;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

class EventListUseCase
{
    /**
     * イベント一覧の取得
     *
     * 今日以降の日付で開催されるイベントを日付の降順で取得します。
     *
     * @param  int  $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUpcomingEvents($perPage = 12)
    {
        return Event::whereDate('date', '>=', Carbon::today())
            ->orderBy('date', 'desc')
            ->paginate($perPage);
    }

    /**
     * 全てのイベント一覧の取得
     *
     * すべてのイベントを日付の降順で取得します。
     *
     * @param  int  $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllEvents($perPage = 12)
    {
        return Event::orderBy('date', 'desc')
            ->paginate($perPage);
    }



    /**
     * Get the events that the user has joined.
     *
     * @param  User  $user
     * @param  int  $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getJoinedEvents(User $user, $perPage = 12)
    {
        return $user->joinedEvents()
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date', 'desc')
            ->paginate($perPage);
    }


    /**
     * 作成したイベント一覧の取得
     *
     * ユーザーが作成したイベントを日付の降順で取得します。
     *
     * @param  User  $user
     * @param  int  $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getOrganizedEvents(User $user, $perPage = 12)
    {
        return $user->organizedEvents()->orderBy('date', 'desc')->paginate($perPage);
    }
}
