<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\UseCases\Auth\AuthSessionUseCase;

class AuthenticatedSessionController extends Controller
{
    private $authSessionUseCase;

    public function __construct(AuthSessionUseCase $authSessionUseCase)
    {
        $this->authSessionUseCase = $authSessionUseCase;
    }

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        if ($this->authSessionUseCase->processLogin($credentials)) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return back()->withErrors([
            'email' => trans('auth.failed'),
        ]);
    }

    public function destroy(): RedirectResponse
    {
        $this->authSessionUseCase->logout();

        return redirect('/');
    }
}
