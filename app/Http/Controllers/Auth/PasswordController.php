<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UseCases\Auth\PasswordUseCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    private $passwordUseCase;

    public function __construct(PasswordUseCase $passwordUseCase)
    {
        $this->passwordUseCase = $passwordUseCase;
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $this->passwordUseCase->updatePassword($request->user(), $validated);

        return back()->with('status', 'password-updated');
    }
}
