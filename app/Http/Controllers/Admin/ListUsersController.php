<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\ListUsersUseCase;

/**
 * 管理者向けユーザーリストのコントローラクラス
 */
class ListUsersController extends Controller
{
    /**
     * ユーザーリスト表示のためのユースケース
     * 
     * @var ListUsersUseCase
     */
    private $listUsersUseCase;

    /**
     * ListUsersControllerのコンストラクタ
     *
     * @param ListUsersUseCase $listUsersUseCase ユーザーリスト表示のためのユースケース
     * 
     * ユーザーリストのユースケースを注入します。
     */
    public function __construct(ListUsersUseCase $listUsersUseCase)
    {
        $this->listUsersUseCase = $listUsersUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * ユーザーリストのページを表示するメソッド
     *
     * @return \Illuminate\Http\Response ユーザーリストのビューページ
     */
    public function listUsers()
    {
        $data = $this->listUsersUseCase->fetchUsersData();
        return view('admin.users-list', $data);
    }
}
