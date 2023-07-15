<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OperationLogController;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ListController extends Controller
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
     * イベント一覧の表示
     *
     * 今日以降の日付で開催されるイベントを日付の降順でページネーションして表示します。
     *
     * @return \Illuminate\View\View
     */
    public function listUpcoming()
    {
        $events = Event::whereDate('date', '>=', Carbon::today())
            ->orderBy('date', 'desc')
            ->paginate(12);
        return view('event.list-upcoming', ['events' => $events]);
    }

    /**
     * 全てのイベント一覧の表示
     *
     * すべてのイベントを日付の降順でページネーションして表示します。
     *
     * @return \Illuminate\View\View
     */
    public function listAll()
    {
        $events = Event::orderBy('date', 'desc')
            ->paginate(12);
        return view('event.list-all', ['events' => $events]);
    }
}
