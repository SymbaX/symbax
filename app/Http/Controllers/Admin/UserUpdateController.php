<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\UserUpdateUseCase;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;

/**
 * 管理者向けユーザー更新のコントローラクラス
 */
class UserUpdateController extends Controller
{
    /**
     * ユーザー更新のビジネスロジックを提供するユースケース
     * 
     * @var UserUpdateUseCase ユーザー更新に使用するUseCaseインスタンス
     */
    private $userUpdateUseCase;

    /**
     * UserUpdateControllerのコンストラクタ
     *
     * 使用するユースケースをインジェクション（注入）します。
     * 
     * @param UserUpdateUseCase $userUpdateUseCase ユーザー更新のユースケース
     */
    public function __construct(UserUpdateUseCase $userUpdateUseCase)
    {
        $this->userUpdateUseCase = $userUpdateUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * ユーザー情報の更新すをるメソッド
     * 
     * @param UserUpdateRequest $request 更新情報が含まれるリクエスト
     * @param User $user 対象ユーザーのモデル
     *
     * @return Response ユーザー情報更新後のユーザー一覧画面
     */
    public function updateUser(UserUpdateRequest $request, User $user)
    {
        return $this->userUpdateUseCase->updateUser($request, $user);
    }
}
