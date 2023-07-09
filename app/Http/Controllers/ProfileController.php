<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * プロフィールコントローラークラス
 * 
 * このクラスはプロフィールに関する処理を行うコントローラーです。
 */
class ProfileController extends Controller
{
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
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * プロフィールの削除
     *
     * プロフィールを削除します。削除する前にパスワードの確認が必要です。
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

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
