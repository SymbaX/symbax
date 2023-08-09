<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\UseCases\Profile\ProfileUseCase;
use App\Http\Requests\Profile\ProfileDeleteRequest;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * プロフィールコントローラークラス
 * 
 * ユーザーのプロフィールに関連する操作を提供するコントローラーです。
 */
class ProfileController extends Controller
{
    /**
     * プロフィールのビジネスロジックを提供するユースケース
     * 
     * @var ProfileUseCase
     */
    private $profileUseCase;

    /**
     * ProfileControllerのコンストラクタ
     * 
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param ProfileUseCase $profileUseCase プロフィールのユースケースインスタンス
     */
    public function __construct(ProfileUseCase $profileUseCase)
    {
        $this->profileUseCase = $profileUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * プロフィール編集画面を表示するメソッド
     * 
     * ログイン中のユーザーのプロフィール編集画面を表示します。
     * 
     * @param Request $request HTTPリクエスト
     * @return View プロフィール編集画面のViewインスタンス
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
     * プロフィールを更新するメソッド
     * 
     * ユーザーが入力した新しい情報を元にプロフィールを更新します。
     * 
     * @param ProfileUpdateRequest $request プロフィール更新のリクエスト
     * @return RedirectResponse プロフィール編集画面へのリダイレクトレスポンス
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $this->profileUseCase->update($request);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * プロフィールを削除するメソッド
     * 
     * ユーザーのプロフィールを削除します。
     * 
     * @param ProfileDeleteRequest $request プロフィール削除のリクエスト
     * @return RedirectResponse ルートURLへのリダイレクトレスポンス
     */
    public function destroy(ProfileDeleteRequest $request): RedirectResponse
    {
        $this->profileUseCase->destroy($request);

        return Redirect::to('/');
    }


    public function show($loginId)
    {
        try {
            $user = $this->profileUseCase->getByLoginId($loginId);
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }

        return view('profile.profile_page', compact('user'));
    }
}
