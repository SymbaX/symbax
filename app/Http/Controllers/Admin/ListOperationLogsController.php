<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\ListOperationLogsUseCase;

/**
 * Class ListOperationLogsController
 * 
 * 管理者用操作ログリストのコントローラーです。
 */
class ListOperationLogsController extends Controller
{
    /**
     * @var ListOperationLogsUseCase
     * 
     * 操作ログリストのユースケース
     */
    private $listOperationLogsUseCase;

    /**
     * ListOperationLogsController constructor.
     *
     * @param ListOperationLogsUseCase $listOperationLogsUseCase
     * 
     * 操作ログリストのユースケースを注入します。
     */
    public function __construct(ListOperationLogsUseCase $listOperationLogsUseCase)
    {
        $this->listOperationLogsUseCase = $listOperationLogsUseCase;
    }

    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     * 
     * 操作ログリストページを表示します。ユースケースから取得した操作ログをビューに渡します。
     */
    public function __invoke()
    {
        $operation_logs = $this->listOperationLogsUseCase->execute();
        return view('admin.operation-logs', compact('operation_logs'));
    }
}
