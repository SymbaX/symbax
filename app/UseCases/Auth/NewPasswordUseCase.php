<?php

namespace App\UseCases\Auth;

use App\UseCases\OperationLog\OperationLogUseCase;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

/**
 * パスワードリセットユースケースクラス
 *
 * このクラスは、パスワードリセットの処理を提供します。
 */
class NewPasswordUseCase
{
    /**
     * 操作ログを保存するためのビジネスロジックを提供するユースケース
     * このユースケースを利用して、システムの操作に関するログの記録処理を行います。
     * 
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * NewPasswordUseCaseのコンストラクタ
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
     * パスワードリセット処理を行います。
     *
     * @param Request $request リクエストオブジェクト
     * @return string パスワードリセットの結果
     */
    public function resetPassword(Request $request): string
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // ユーザーのパスワードリセットを試みます。成功した場合は実際のユーザーモデル上のパスワードを更新し、データベースに保存します。
        // 失敗した場合はエラーを解析し、結果を返します。
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        $user = User::where('email', $request->get('email'))->first();
        $userId = $user ? $user->id : null;

        if ($status == Password::PASSWORD_RESET) {
            $this->operationLogUseCase->store([
                'detail' => "email: {$request->get('email')}\n",
                'user_id' => null,
                'target_event_id' => null,
                'target_user_id' => $userId,
                'target_topic_id' => null,
                'action' => 'reset-password-success',
                'ip' => request()->ip(),
            ]);
        } else {
            $this->operationLogUseCase->store([
                'detail' => "email: {$request->get('email')}\n",
                'user_id' => null,
                'target_event_id' => null,
                'target_user_id' => $userId,
                'target_topic_id' => null,
                'action' => 'reset-password-failed',
                'ip' => request()->ip(),
            ]);
        }

        return $status;
    }
}
