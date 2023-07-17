<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\UseCases\Auth\NewPasswordUseCase;

class NewPasswordController extends Controller
{
    private $newPasswordUseCase;

    public function __construct(NewPasswordUseCase $newPasswordUseCase)
    {
        $this->newPasswordUseCase = $newPasswordUseCase;
    }

    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    public function store(Request $request): RedirectResponse
    {
        $status = $this->newPasswordUseCase->resetPassword($request);

        return $status == \Illuminate\Support\Facades\Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }
}
