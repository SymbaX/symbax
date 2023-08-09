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
     * @param int $per_page 1ページ当たりの表示件数
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator 今後のイベントのページネーションオブジェクト
     */
    public function getUpcomingEvents($per_page = 12)
    {
        return Event::whereDate('date', '>=', Carbon::today())
            ->where('is_deleted', false)
            ->orderBy('date', 'desc')
            ->paginate($per_page);
    }

    public function getNewestEvents($limit = 6)
    {
        return Event::whereDate('date', '>=', Carbon::today())
            ->where('is_deleted', false)
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }

    /**
     * 全てのイベント一覧の取得
     *
     * すべてのイベントを日付の降順で指定されたページ数毎に取得します。
     *
     * @param int $per_page 1ページ当たりの表示件数
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator 全てのイベントのページネーションオブジェクト
     */
    public function getAllEvents($per_page = 12)
    {
        return Event::where('is_deleted', false)
            ->orderBy('date', 'desc')
            ->paginate($per_page);
    }

    /**
     * ユーザーが参加しているイベント一覧の取得
     *
     * 指定したユーザーが参加している今後のイベントを日付の降順で指定されたページ数毎に取得します。
     *
     * @param User $user ユーザー
     * @param int $per_page 1ページ当たりの表示件数
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator ユーザーが参加しているイベントのページネーションオブジェクト
     */
    public function getJoinedEvents(User $user, $per_page = 12)
    {
        return $user->joinedEvents()
            ->whereDate('date', '>=', Carbon::today())
            ->where('is_deleted', false)
            ->orderBy('date', 'desc')
            ->paginate($per_page);
    }

    /**
     * ユーザーが作成したイベント一覧の取得
     *
     * 指定したユーザーが作成したイベントを日付の降順で指定されたページ数毎に取得します。
     *
     * @param User $user ユーザー
     * @param int $per_page 1ページ当たりの表示件数
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator ユーザーが作成したイベントのページネーションオブジェクト
     */
    public function getOrganizedEvents(User $user, $per_page = 12)
    {
        return $user->organizedEvents()
            ->where('is_deleted', false)
            ->orderBy('date', 'desc')
            ->paginate($per_page);
    }
}
