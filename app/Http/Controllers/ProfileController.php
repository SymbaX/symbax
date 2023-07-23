<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Http\Controllers\OperationLogController;

/**
 * プロフィールコントローラークラス
 * 
 * このクラスはプロフィールに関する処理を行うコントローラーです。
 */
class ProfileController extends Controller
{
    /**
     * @var OperationLogController
     */
    private $operationLogController;

    /**
     * OperationLogControllerの新しいインスタンスを作成します。
     *
     * @param  OperationLogController  $operationLogController
     * @return void
     */
    public function __construct(OperationLogController $operationLogController)
    {
        $this->operationLogController = $operationLogController;
    }

    /**
     * プロフィール編集フォームの表示
     *
     * 現在のユーザーのプロフィール情報を含むフォームを表示します。
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request): View
    {
        $user = $request->user()->load('college', 'department');

        return view('profile.edit', [
            'user' => $user,
            'college' => $user->college,
            'department' => $user->department,
        ]);
    }

    /**
     * プロフィールの更新
     *
     * プロフィール情報を更新します。更新がある場合、メールアドレスの確認はリセットされます。
     *
     * @param  ProfileUpdateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {

        $request->user()->fill($request->safe()->only(['name', 'email']));

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $path = null;
        if ($request->hasFile('picture')) {
            $path = $request->file('picture')->store('profile-icons', 'public');
            $request->user()->profile_photo_path = $path;
        }

        $request->user()->save();

        $this->operationLogController->store('プロフィールを更新しました');


        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * アカウントの削除
     *
     * アカウントを削除します。削除する前にパスワードの確認が必要です。
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        $this->operationLogController->store('アカウントを削除しました', auth()->id());

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();


        return Redirect::to('/');
    }
}
