<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\AdminDashboardUseCase;

/**
 * 管理者用ダッシュボードのコントローラー
 */
class AdminDashboardController extends Controller
{
    /**
     * 管理者ダッシュボードのビジネスロジックを提供するユースケース
     * 
     * @var AdminDashboardUseCase
     */
    private $adminDashboardUseCase;

    /**
     * AdminDashboardControllerのコンストラクタ
     * 
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param AdminDashboardUseCase $adminDashboardUseCase 管理者ダッシュボード用のユースケース
     */
    public function __construct(AdminDashboardUseCase $adminDashboardUseCase)
    {
        $this->adminDashboardUseCase = $adminDashboardUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * 管理者ダッシュボードページを表示するメソッド
     * 
     * 管理者ダッシュボードページを表示します。
     * 
     * @return \Illuminate\Http\Response 管理者ダッシュボードのビュー
     */
    public function dashboard()
    {
        // ログを記録する
        $this->adminDashboardUseCase->dashboardLog();

        // Viewを返す
        return view('admin.dashboard');
    }
}
