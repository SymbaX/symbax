<?php

namespace App\UseCases\Profile;

use App\Http\Requests\Profile\ProfileDeleteRequest;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use Illuminate\Support\Facades\Auth;

/**
 * プロフィールに関するユースケースクラス
 *
 * このクラスは、プロフィールの更新と削除に関連するビジネスロジックを提供します。
 */
class ProfileUseCase
{
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
        $user = $request->user();
        $user->delete();

        // ユーザーをログアウトします
        Auth::logout();

        // セッションを無効化し、新しいトークンを生成します
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
