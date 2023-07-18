<?php

namespace App\UseCases\Profile;

use App\Http\Requests\Profile\ProfileDeleteRequest;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Support\Facades\Auth;


/**
 * プロフィールに関するユースケースクラス
 *
 * このクラスは、プロフィールの更新と削除に関連するビジネスロジックを提供します。
 */
class ProfileUseCase
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
     * プロフィールを更新します。
     *
     * @param ProfileUpdateRequest $request プロフィール更新のリクエスト
     * @return void
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $user->fill($request->validated());

        // メールアドレスが変更された場合、メール確認日時をリセットします
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $this->operationLogUseCase->store('プロフィールを更新しました');

        $user->save();
    }

    /**
     * プロフィールを削除します。
     *
     * @param ProfileDeleteRequest $request プロフィール削除のリクエスト
     * @return void
     */
    public function destroy(ProfileDeleteRequest $request)
    {
        $this->operationLogUseCase->store('アカウントを削除しました');

        $user = $request->user();

        // ユーザーをログアウトします
        Auth::logout();

        $user->delete();


        // セッションを無効化し、新しいトークンを生成します
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
