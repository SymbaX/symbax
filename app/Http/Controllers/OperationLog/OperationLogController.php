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
     * 操作ログのビジネスロジックを提供するユースケース
     * 
     * @var OperationLogUseCase 操作ログに使用するUseCaseインスタンス
     */
    private $operationLogUseCase;

    /**
     * コンストラクタ
     *
     * 使用するユースケースをインジェクション（注入）します。
     * 
     * @param OperationLogUseCase $operationLogUseCase 操作ログに関するユースケース
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * 操作ログを保存するメソッド
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
