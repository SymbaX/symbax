<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OperationLog;
use Brick\Math\BigInteger;
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

    public function store(string $message, ?String $user_id = "不明")
    {
        // 操作履歴の作成処理
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
