<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\ListUsersUseCase;

/**
 * Class ListUsersController
 * 
 * 管理者向けユーザーリストのコントローラーです。
 */
class ListUsersController extends Controller
{
    /**
     * @var ListUsersUseCase
     * 
     * ユーザーリスト表示のためのユースケース
     */
    private $listUsersUseCase;

    /**
     * ListUsersController constructor.
     *
     * @param ListUsersUseCase $listUsersUseCase
     * 
     * ユーザーリストのユースケースを注入します。
     */
    public function __construct(ListUsersUseCase $listUsersUseCase)
    {
        $this->listUsersUseCase = $listUsersUseCase;
    }

    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\Response
     * 
     * ユーザーリストのページを表示します。ユースケースから取得したデータをビューに渡します。
     */
    public function __invoke()
    {
        $data = $this->listUsersUseCase->execute();
        return view('admin.users-list', $data);
    }
}
