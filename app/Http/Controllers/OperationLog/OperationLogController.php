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
        $this->operationLogUseCase->store([
            'detail' => $request->input('detail'),
            'user_id' => $request->input('user_id'),
            'target_event_id' => $request->input('target_event_id'),
            'target_user_id' => $request->input('target_user_id'),
            'target_topic_id' => $request->input('target_topic_id'),
            'action' => $request->input('action'),
            'ip' => $request->ip(),
        ]);

        return redirect()->route('operation_logs.index');
    }
}
