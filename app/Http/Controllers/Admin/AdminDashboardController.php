<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\AdminDashboardUseCase;

/**
 * Class AdminDashboardController
 * 
 * 管理者用ダッシュボードのコントローラーです。
 */
class AdminDashboardController extends Controller
{
    /**
     * @var AdminDashboardUseCase
     * 
     * 管理者ダッシュボードのユースケース
     */
    private $adminDashboardUseCase;

    /**
     * AdminDashboardController constructor.
     *
     * @param AdminDashboardUseCase $adminDashboardUseCase
     * 
     * 管理者ダッシュボード用のユースケースを注入します。
     */
    public function __construct(AdminDashboardUseCase $adminDashboardUseCase)
    {
        $this->adminDashboardUseCase = $adminDashboardUseCase;
    }

    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     * 
     * 管理者ダッシュボードページを表示します。
     */
    public function __invoke()
    {
        $this->adminDashboardUseCase->execute();
        return view('admin.dashboard');
    }
}
