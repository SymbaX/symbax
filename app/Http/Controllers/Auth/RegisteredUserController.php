<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserRegistrationRequest;
use App\Models\College;
use App\Models\Department;
use App\Providers\RouteServiceProvider;
use App\UseCases\Auth\RegistrationUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\View\View;

/**
 * 登録済みユーザーコントローラークラス
 *
 * このクラスは、ユーザーの新規登録に関連する処理を提供します。
 */
class RegisteredUserController extends Controller
{
    /**
     * ユーザーの新規登録に関連するビジネスロジックを提供するユースケース
     * 
     * @var RegistrationUseCase ユーザーの新規登録に使用するUseCaseインスタンス
     */
    private $registrationUseCase;

    /**
     * RegisteredUserControllerのコンストラクタ
     * 
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param RegistrationUseCase $registrationUseCase ユーザーの新規登録に関連するユースケース
     */
    public function __construct(RegistrationUseCase $registrationUseCase)
    {
        $this->registrationUseCase = $registrationUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * 登録フォームを表示するメソッド
     *
     * @return View 登録フォームのViewインスタンス
     */
    public function create(): View
    {
        // カレッジと学科の一覧を取得
        $colleges = College::all();
        $departments = Department::all();

        // 登録フォームの初期値として選択されているカレッジと学科の ID を取得
        $selectedCollegeId = old('college', null);
        $selectedDepartmentId = old('department', null);

        // 値をViewに渡して、登録フォームを表示する
        return view('auth.register', [
            'colleges' => $colleges,
            'departments' => $departments,
            'selectedCollegeId' => $selectedCollegeId,
            'selectedDepartmentId' => $selectedDepartmentId,
        ]);
    }

    /**
     * ユーザーの新規登録を行うメソッド
     *
     * @param Request $request リクエスト
     * @return RedirectResponse リダイレクトレスポンス
     */
    public function store(UserRegistrationRequest $request): RedirectResponse
    {
        // ユーザーの新規登録処理を行う
        $user = $this->registrationUseCase->register($request->all());

        // ユーザーをログインさせる
        $this->registrationUseCase->login($user);

        // ユーザーの新規登録処理の結果に応じてリダイレクトする
        return redirect(RouteServiceProvider::HOME);
    }
}
