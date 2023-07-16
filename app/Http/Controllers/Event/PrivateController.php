<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\View\View;

/**
 * プライベートコントローラー
 *
 * 認可されたユーザーのみがアクセスできるページを提供するコントローラーです。
 */
class PrivateController extends Controller
{
    /**
     * プライベートページの表示
     *
     * 認可されたユーザーのみがアクセスできるページの表示を行います。
     * 参加ステータスが"approved"のイベント参加者またはイベントの作成者のみがアクセスできます。
     * アクセス権がない場合は403 Forbiddenエラーを返します。
     *
     * @param Request $request リクエストデータ
     * @param int $id イベントID
     * @return View
     */
    public function create(Request $request, $id): View
    {
        $event = Event::findOrFail($id);

        // ログインユーザーの参加ステータスをチェックし、approvedでない場合アクセスを制限する
        $participant = EventParticipant::where('event_id', $id)
            ->where('user_id', auth()->user()->id)
            ->first();

        if (!$participant || $participant->status !== 'approved' && $event->organizer_id !== Auth::id()) {
            abort(403);
        }

        // ここから、approvedのユーザーまたはイベントの作成者のみがアクセスできるページのコードを記述する
        // ...

        return view('event.approved-users-and-organizer-only', ['event' => $id]);
    }
}
