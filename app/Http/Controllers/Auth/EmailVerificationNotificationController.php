<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\UseCases\OperationLog\OperationLogUseCase;

/**
 * メール検証通知コントローラー
 *
 * メール検証通知に関連するコントローラー
 */
class EmailVerificationNotificationController extends Controller
{
    /**
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * OperationLogUseCaseの新しいインスタンスを作成します。
     *
     * @param  OperationLogUseCase  $operationLogUseCase
     * @return void
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }


    /**
     * 新しいメール検証通知を送信する
     *
     * @param Request $request リクエスト
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function store(Request $request): RedirectResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        $this->operationLogUseCase->store('検証メールを再送信しました');


        return back()->with('status', 'verification-link-sent');
    }
}
