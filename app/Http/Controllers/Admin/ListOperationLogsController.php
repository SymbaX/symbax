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
     * 操作ログリストのビジネスロジックを提供するユースケース
     * 
     * @var ListOperationLogsUseCase
     */
    private $listOperationLogsUseCase;

    /**
     * ListOperationLogsControllerのコンストラクタ
     *
     * 使用するユースケースをインジェクション（注入）します。
     * 
     * @param ListOperationLogsUseCase $listOperationLogsUseCase 操作ログリストのユースケース
     */
    public function __construct(ListOperationLogsUseCase $listOperationLogsUseCase)
    {
        $this->listOperationLogsUseCase = $listOperationLogsUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * 操作ログのリストを表示するメソッド
     *
     * @return View 操作ログリストページを表示。ユースケースから取得した操作ログをビューに渡す。
     */
    public function showLogs()
    {
        $operation_logs = $this->listOperationLogsUseCase->fetchLogs();
        return view('admin.operation-logs', compact('operation_logs'));
    }
}
