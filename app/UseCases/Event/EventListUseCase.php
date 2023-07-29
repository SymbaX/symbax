<?php

namespace App\UseCases\Event;

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

/**
 * イベント一覧ユースケース
 *
 * イベントの一覧取得に関連するユースケースを提供するクラスです。
 */
class EventListUseCase
{
    /**
     * 今後のイベント一覧の取得
     *
     * 今日以降の日付で開催されるイベントを日付の降順で指定されたページ数毎に取得します。
     *
     * @param int $perPage 1ページ当たりの表示件数
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator 今後のイベントのページネーションオブジェクト
     */
    public function getUpcomingEvents($perPage = 12)
    {
        return Event::whereDate('date', '>=', Carbon::today())
            ->orderBy('date', 'desc')
            ->paginate($perPage);
    }

    public function getNewestEvents($limit = 6)
    {
        return Event::whereDate('date', '>=', Carbon::today())
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * 全てのイベント一覧の取得
     *
     * すべてのイベントを日付の降順で指定されたページ数毎に取得します。
     *
     * @param int $perPage 1ページ当たりの表示件数
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator 全てのイベントのページネーションオブジェクト
     */
    public function getAllEvents($perPage = 12)
    {
        return Event::orderBy('date', 'desc')
            ->paginate($perPage);
    }

    /**
     * ユーザーが参加しているイベント一覧の取得
     *
     * 指定したユーザーが参加している今後のイベントを日付の降順で指定されたページ数毎に取得します。
     *
     * @param User $user ユーザー
     * @param int $perPage 1ページ当たりの表示件数
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator ユーザーが参加しているイベントのページネーションオブジェクト
     */
    public function getJoinedEvents(User $user, $perPage = 12)
    {
        return $user->joinedEvents()
            ->whereDate('date', '>=', Carbon::today())
            ->orderBy('date', 'desc')
            ->paginate($perPage);
    }

    /**
     * ユーザーが作成したイベント一覧の取得
     *
     * 指定したユーザーが作成したイベントを日付の降順で指定されたページ数毎に取得します。
     *
     * @param User $user ユーザー
     * @param int $perPage 1ページ当たりの表示件数
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator ユーザーが作成したイベントのページネーションオブジェクト
     */
    public function getOrganizedEvents(User $user, $perPage = 12)
    {
        return $user->organizedEvents()->orderBy('date', 'desc')->paginate($perPage);
    }
}
