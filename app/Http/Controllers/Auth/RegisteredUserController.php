<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\College;
use App\Models\Department;
use App\Providers\RouteServiceProvider;
use App\UseCases\Auth\RegistrationUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    private $registrationUseCase;

    public function __construct(RegistrationUseCase $registrationUseCase)
    {
        $this->registrationUseCase = $registrationUseCase;
    }

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

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users'), 'regex:/^[^@]+@g\.neec\.ac\.jp$/'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'college' => ['required', 'exists:colleges,id'],
            'department' => [
                'required', 'exists:departments,id', Rule::exists('departments', 'id')->where(function ($query) use ($request) {
                    $query->where('college_id', $request->input('college'));
                }),
            ],
        ]);

        $user = $this->registrationUseCase->register($request->all());

        $this->registrationUseCase->login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
