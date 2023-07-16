<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\UseCases\Event\CheckEventParticipantStatusUseCase;
use App\UseCases\Event\CheckEventOrganizerUseCase;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * イベントのプライベートページを表示するコントローラー
 */
class EventPrivateController extends Controller
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

        if ($isParticipantApproved || $isEventOrganizer) {
            // approvedのイベント参加者またはイベントの作成者の場合は、ページを表示する
            return view('event.approved-users-and-organizer-only', ['event' => $id]);
        }

        // アクセス権がない場合は403 Forbiddenエラーを返す
        abort(403);
    }
}
