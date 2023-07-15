<?php

namespace App\UseCases\Event;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Auth;

class EventDetailUseCase
{
    /**
     * イベントの詳細を取得します。
     *
     * @param int $id
     * @return array
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

        return [
            'event' => $event,
            'detail_markdown' => $detail_markdown,
            'participants' => $participants,
            'is_organizer' => $is_organizer,
            'is_join' => $is_join,
            'your_status' => $your_status,
            'organizer_name'  => $organizer_name
        ];
    }
}
