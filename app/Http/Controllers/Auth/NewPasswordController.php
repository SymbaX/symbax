<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Http\Controllers\OperationLogController;
use App\Models\User;

/**
 * パスワードリセットコントローラー
 *
 * パスワードリセットに関連するコントローラー
 */
class NewPasswordController extends Controller
{
    private $operationLogController;

    public function __construct(OperationLogController $operationLogController)
    {
        $this->operationLogController = $operationLogController;
    }

    /**
     * パスワードリセットビューを表示する
     *
     * @param Request $request リクエスト
     * @return View パスワードリセットビューの表示
     */
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    /**
     * 新しいパスワードリクエストの処理を行う
     *
     * @param Request $request リクエスト
     * @return RedirectResponse リダイレクトレスポンス
     *
     * @throws \Illuminate\Validation\ValidationException バリデーション例外
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // ユーザーのパスワードリセットを試みます。成功した場合は実際のユーザーモデル上のパスワードを更新し、データベースに保存します。
        // 失敗した場合はエラーを解析し、レスポンスを返します。
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



        $user = User::where('email', $request->email)->first();

        if ($status == Password::PASSWORD_RESET) {
            $userId = $user->id;
            $this->operationLogController->store('Email: ' . $request->email . ' (ID: ' . $userId . ') のパスワードをリセットしました');
        } else {
            $userId = $user->id ?? '不明';
            $this->operationLogController->store('Email: ' . $request->email . ' (ID: ' . $userId . ') のパスワードリセットに失敗しました');
        }

        // パスワードが正常にリセットされた場合は、認証されたユーザーのアプリケーションホームビューにリダイレクトします。
        // エラーがある場合は、エラーメッセージとともに元のページにリダイレクトします。
        return $status == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))
            ->withErrors(['email' => __($status)]);
    }
}
