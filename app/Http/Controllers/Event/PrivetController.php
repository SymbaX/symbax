<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivetController extends Controller
{
    public function create(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // ログインユーザーの参加ステータスをチェックし、approvedでない場合アクセスを制限する
        $participant = EventParticipant::where('event_id', $id)
            ->where('user_id', auth()->user()->id)
            ->first();

        if (!$participant || $participant->status !== 'approved' and $event->organizer_id !== Auth::id()) {
            abort(403);
        }

        // ここから、approvedのユーザーまたはイベントの作成者のみがアクセスできるページのコードを記述する
        // ...

        return view('event.approved-users-and-organizer-only', ['event' => $id]);
    }
}
