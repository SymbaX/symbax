<?php

namespace App\Http\Controllers;

use App\UseCases\OperationLog\OperationLogUseCase;
use App\Http\Requests\OperationLog\OperationLogStoreRequest;

class OperationLogController extends Controller
{
    private $operationLogUseCase;

    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    public function index()
    {
        $logs = $this->operationLogUseCase->index();
        return view('operation_logs.index', ['logs' => $logs]);
    }

    public function store(OperationLogStoreRequest $request)
    {
        $this->operationLogUseCase->store(
            $request->input('message'),
            $request->input('user_id')
        );

        return redirect()->route('operation_logs.index');
    }
}
