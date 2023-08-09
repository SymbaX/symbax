<?php

namespace App\Http\Controllers;

use App\UseCases\OperationLog\OperationLogUseCase;
use App\Http\Requests\OperationLog\OperationLogStoreRequest;

/**
 * 操作ログに関するコントローラー
 */
class OperationLogController extends Controller
{
    /**
     * 操作ログのユースケースインスタンス
     * 
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * コンストラクタ
     *
     * @param OperationLogUseCase $operationLogUseCase 操作ログに関するユースケース
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * 操作ログを保存する
     * 
     * @param OperationLogStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveLog(OperationLogStoreRequest $request)
    {
        $this->operationLogUseCase->store($request->all());
        return redirect()->route('operation_logs.index');
    }
}
