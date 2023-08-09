<?php

namespace App\UseCases\Profile;

use App\Http\Requests\Profile\ProfileDeleteRequest;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Models\User;
use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    /* =================== 以下メインの処理 =================== */

    /**
     * プロフィールを更新します。
     *
     * @param ProfileUpdateRequest $request プロフィール更新のリクエスト
     * @return void
     */
    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();

        // ユーザーデータのコピーを作成
        $originalUser = clone $user;

        $user->fill($request->validated());

        if ($request->hasFile('picture')) {
            if ($user->profile_photo_path != null) {
                Storage::delete('public/' . $user->profile_photo_path);
            }
            $path = $request->file('picture')->store('profile-icons', 'public');
            $user->profile_photo_path = $path;
        }

        $fields = array_keys($request->validated());
        $fields[] = 'profile_photo_path'; // 写真のパスも対象に含める
        $detail = "";

        foreach ($fields as $field) {
            $originalValue = $originalUser->$field;
            $updatedValue = $user->$field;
            if ($originalValue != $updatedValue) {
                $detail .= "▼ {$field}: {$originalValue} ▶ {$updatedValue}\n";
            }
        }

        if (!empty($detail)) { // 変更がある場合のみログを保存
            $this->operationLogUseCase->store([
                'detail' => $detail,
                'user_id' => auth()->user()->id,
                'target_event_id' => null,
                'target_user_id' => $user->id,
                'target_topic_id' => null,
                'action' => 'update-profile',
                'ip' => request()->ip(),
            ]);
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
        $this->operationLogUseCase->store([
            'detail' => null,
            'user_id' => null,
            'target_event_id' => null,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'delete-profile',
            'ip' => request()->ip(),
        ]);

        $user = $request->user();

        // ユーザーをログアウトします
        Auth::logout();

        $user->delete();


        // セッションを無効化し、新しいトークンを生成します
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    /**
     * ログインIDを元にユーザープロフィールを取得します。
     *
     * @param string $loginId
     * @return User|null
     * @throws \Exception
     */
    public function getByLoginId(string $loginId): ?User
    {
        $user = User::where('login_id', $loginId)->first();

        if ($user === null) {
            throw new \Exception('The specified user was not found.');
        }

        return $user;
    }
}
