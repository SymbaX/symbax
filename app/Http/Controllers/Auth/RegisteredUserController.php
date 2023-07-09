<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\College;
use App\Models\Department;
use Illuminate\Validation\Rule;

/**
 * ユーザー登録コントローラー
 *
 * ユーザー登録に関連するコントローラー
 */
class RegisteredUserController extends Controller
{
    /**
     * 登録ビューを表示する
     *
     * @return View 登録ビューの表示
     */
    public function create(): View
    {
        $colleges = College::all();
        $departments = Department::all();

        // 登録フォームの初期値として選択されているカレッジとデパートメントの ID を取得
        $selectedCollegeId = old('college', null);
        $selectedDepartmentId = old('department', null);

        return view('auth.register', [
            'colleges' => $colleges,
            'departments' => $departments,
            'selectedCollegeId' => $selectedCollegeId,
            'selectedDepartmentId' => $selectedDepartmentId,
        ]);
    }

    /**
     * 登録リクエストの処理を行う
     *
     * @param Request $request リクエスト
     * @return RedirectResponse リダイレクトレスポンス
     *
     * @throws \Illuminate\Validation\ValidationException バリデーション例外
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users'), 'regex:/^[^@]+@g\.neec\.ac\.jp$/',],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'college' => ['required', 'exists:colleges,id'],
            'department' => [
                'required', 'exists:departments,id', Rule::exists('departments', 'id')->where(function ($query) use ($request) {
                    $query->where('college_id', $request->input('college'));
                })
            ],
        ],);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'college_id' => $request->college,
            'department_id' => $request->department,
        ]);


        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
