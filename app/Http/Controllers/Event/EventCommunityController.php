<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\TopicRequest;
use App\Models\Topic;
use App\UseCases\Event\CheckEventParticipantStatusUseCase;
use App\UseCases\Event\CheckEventOrganizerUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * イベントのプライベートページを表示するコントローラー
 */
class EventCommunityController extends Controller
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
     * @param CheckEventParticipantStatusUseCase $checkParticipantStatus
     * @param CheckEventOrganizerUseCase $checkEventOrganizer
     * @return View
     */
    public function create(
        Request $request,
        $id,
        CheckEventParticipantStatusUseCase $checkParticipantStatus,
        CheckEventOrganizerUseCase $checkEventOrganizer
    ): View {
        $isParticipantApproved = $checkParticipantStatus->execute($id);
        $isEventOrganizer = $checkEventOrganizer->execute($id);

        if ($isParticipantApproved === "approved" || $isEventOrganizer) {
            // approvedのイベント参加者またはイベントの作成者の場合は、ページを表示する
            $topics  = Topic::where("event_id", $id)->latest()->get();
            return view('event.community', ['event' => $id], ["topics" => $topics]);
        }

        // アクセス権がない場合は403 Forbiddenエラーを返す
        abort(403);
    }

    public function save(TopicRequest $request)
    {

        //Topicを受け入れるための箱を作る
        $topic = new Topic();

        //nameとcontentが指定されている場合保存する
        if ($request->content) {
            $topic->user_id = Auth::id();
            $topic->event_id = $request->event_id;
            $topic->content = $request->content;
            $topic->save();
        }

        return redirect()->route('event.community', ['event_id' => $topic->event_id]);
    }
}
