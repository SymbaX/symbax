<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\UseCases\Auth\EmailVerificationNotificationUseCase;

class EmailVerificationNotificationController extends Controller
{
    private $emailVerificationNotificationUseCase;

    public function __construct(EmailVerificationNotificationUseCase $emailVerificationNotificationUseCase)
    {
        $this->emailVerificationNotificationUseCase = $emailVerificationNotificationUseCase;
    }

    public function store(Request $request): RedirectResponse
    {
        $this->emailVerificationNotificationUseCase->resendEmailVerificationNotification($request->user());

        return back()->with('status', 'verification-link-sent');
    }
}
