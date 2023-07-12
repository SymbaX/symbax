<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperationLog;
use Brick\Math\BigInteger;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;
use PhpParser\Node\Expr\Cast\String_;

class OperationLogController extends Controller
{
    /**
     * 操作履歴の一覧を表示します。
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $logs = OperationLog::all();
        return view('operation_logs.index', ['logs' => $logs]);
    }

    /**
     * 操作履歴を作成します。
     *
     * @param  string  $message
     * @param  string|null  $user_id
     * @return void
     */
    public function store(string $message, ?String $user_id = "不明")
    {
        $log = new OperationLog;

        if (auth()->check()) {
            $log->user_id = auth()->user()->id;
        } else {
            $log->user_id = $user_id;
        }

        $log->action = $message;
        $log->save();
    }
}
