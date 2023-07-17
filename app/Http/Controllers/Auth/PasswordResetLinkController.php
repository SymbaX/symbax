<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UseCases\Auth\PasswordResetLinkUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    private $passwordResetLinkUseCase;

    public function __construct(PasswordResetLinkUseCase $passwordResetLinkUseCase)
    {
        $this->passwordResetLinkUseCase = $passwordResetLinkUseCase;
    }

    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $status = $this->passwordResetLinkUseCase->sendResetLink($request->only('email'));

        return $status == \Illuminate\Support\Facades\Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }
}
