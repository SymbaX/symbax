<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\UseCases\Auth\EmailVerificationUseCase;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    private $emailVerificationUseCase;

    public function __construct(EmailVerificationUseCase $emailVerificationUseCase)
    {
        $this->emailVerificationUseCase = $emailVerificationUseCase;
    }

    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        return $this->emailVerificationUseCase->verify($request);
    }
}
