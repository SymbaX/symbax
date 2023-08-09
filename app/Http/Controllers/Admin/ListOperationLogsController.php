<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\ListOperationLogsUseCase;

/**
 * 管理者用操作ログリストのコントローラクラス
 */
class ListOperationLogsController extends Controller
{
    /**
     * 操作ログリストのユースケース
     * 
     * @var ListOperationLogsUseCase
     */
    private $listOperationLogsUseCase;

    /**
     * ListOperationLogsControllerのコンストラクタ
     *
     * @param ListOperationLogsUseCase $listOperationLogsUseCase 操作ログリストのユースケース
     */
    public function __construct(ListOperationLogsUseCase $listOperationLogsUseCase)
    {
        $this->listOperationLogsUseCase = $listOperationLogsUseCase;
    }

    /**
     * 操作ログのリストを表示
     *
     * @return View 操作ログリストページを表示。ユースケースから取得した操作ログをビューに渡す。
     */
    public function showLogs()
    {
        $operation_logs = $this->listOperationLogsUseCase->fetchLogs();
        return view('admin.operation-logs', compact('operation_logs'));
    }
}
