<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OperationLogController;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Auth;

class DetailController extends Controller
{
    /**
     * @var OperationLogController
     */
    private $operationLogController;

    /**
     * OperationLogControllerの新しいインスタンスを作成します。
     *
     * @param  OperationLogController  $operationLogController
     * @return void
     */
    public function __construct(OperationLogController $operationLogController)
    {
        $this->operationLogController = $operationLogController;
    }

    /**
     * イベントの詳細表示
     *
     * 指定されたイベントの詳細情報を表示します。
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function detail($id)
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

        return view('event.detail', [
            'event' => $event,
            'detail_markdown' => $detail_markdown,
            'participants' => $participants,
            'is_organizer' => $is_organizer,
            'is_join' => $is_join,
            'your_status' => $your_status,
            'organizer_name'  => $organizer_name
        ]);
    }
}
