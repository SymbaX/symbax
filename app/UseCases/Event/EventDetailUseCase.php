<?php

namespace App\UseCases\Event;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use App\Models\EventCategories;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Auth;

/**
 * イベントの詳細取得ユースケース
 *
 * イベントの詳細情報を取得するためのユースケースクラスです。
 */
class EventDetailUseCase
{
    /**
     * イベントの詳細を取得します。
     *
     * 指定されたイベントIDに基づいて、イベントの詳細情報を取得します。
     * イベントの詳細はMarkdown形式で保存されているため、表示の際にパースされます。
     * イベントに参加しているユーザーやイベントの作成者を判定し、関連情報も取得します。
     *
     * @param int $id イベントID
     * @return array イベントの詳細情報を含む連想配列
     */
    public function getEventDetail($id): array
    {
        $event = Event::findOrFail($id);
        $detail_markdown = Markdown::parse(e($event->detail));

        $participants = EventParticipant::where('event_id', $id)
            ->join('users', 'users.id', '=', 'event_participants.user_id')
            ->select('users.id as user_id', 'users.name', 'event_participants.status')
            ->get();

        $organizer_name = User::where('id', $event->organizer_id)->value('name');

        // 現在のユーザーがイベントの作成者であるかをチェック
        $is_organizer = $event->organizer_id === Auth::id();

        // 現在のユーザーがイベントに参加しているかをチェック
        $is_join = $event->organizer_id !== Auth::id() && !$participants->pluck('user_id')->contains(Auth::id());

        $your_status = $participants->where('user_id', Auth::id())->first()->status ?? 'not-join';

        $categories_name = EventCategories::where('id', $event->id)->value('category');

        return [
            'event' => $event,
            'detail_markdown' => $detail_markdown,
            'participants' => $participants,
            'is_organizer' => $is_organizer,
            'is_join' => $is_join,
            'your_status' => $your_status,
            'organizer_name'  => $organizer_name,
            'categories_name' => $categories_name,
        ];
    }
}
