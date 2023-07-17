<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ConfirmPasswordRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\UseCases\Auth\ConfirmablePasswordUseCase;

class ConfirmablePasswordController extends Controller
{
    private $confirmablePasswordUseCase;

    public function __construct(ConfirmablePasswordUseCase $confirmablePasswordUseCase)
    {
        $this->confirmablePasswordUseCase = $confirmablePasswordUseCase;
    }

    public function show(): View
    {
        return view('auth.confirm-password');
    }

    public function store(ConfirmPasswordRequest $request): RedirectResponse
    {
        $this->confirmablePasswordUseCase->confirmPassword(
            $request->user()->email,
            $request->password
        );

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
