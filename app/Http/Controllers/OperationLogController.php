<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperationLog;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;
use PhpParser\Node\Expr\Cast\String_;

class OperationLogController extends Controller
{
    //
    public function index()
    {
        // 操作履歴の一覧を取得する処理
        $logs = OperationLog::all();

        // ビューを表示するなど、適切な処理を追加してください

        return view('operation_logs.index', ['logs' => $logs]);
    }

    public function store(String $message)
    {
        // 操作履歴の作成処理
        $log = new OperationLog;
        $log->user_id = auth()->id();
        $log->action = $message;
        $log->save();
    }
}
