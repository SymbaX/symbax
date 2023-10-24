<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\UseCases\Admin\EventDeleteUseCase;
use Illuminate\Http\Request;

/**
 * コントローラクラス
 */
class EventDeleteController extends Controller
{
    /**
     * ユーザー更新のビジネスロジックを提供するユースケース
     *
     * @var EventDeleteUseCase ユーザー更新に使用するUseCaseインスタンス
     */
    private $eventDeleteUseCase;

    /**
     * UserUpdateControllerのコンストラクタ
     *
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param EventDeleteUseCase $eventDeleteUseCase
     */
    public function __construct(EventDeleteUseCase $eventDeleteUseCase)
    {
        $this->eventDeleteUseCase = $eventDeleteUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    public function destroy(Request $request, Event $event)
    {
        $result = $this->eventDeleteUseCase->deleteEvent($event->id);

        if ($result) {
            return redirect()->route('admin.events')->with('status', 'Event successfully deleted.');
        }

        return redirect()->route('admin.events')->with('status', 'Error deleting event.');
    }
}
