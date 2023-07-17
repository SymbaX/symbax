<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\ListOperationLogsUseCase;

class ListOperationLogsController extends Controller
{
    private $listOperationLogsUseCase;

    public function __construct(ListOperationLogsUseCase $listOperationLogsUseCase)
    {
        $this->listOperationLogsUseCase = $listOperationLogsUseCase;
    }

    public function __invoke()
    {
        $operation_logs = $this->listOperationLogsUseCase->execute();
        return view('admin.operation-logs', compact('operation_logs'));
    }
}
