<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendMailRequest;
use App\UseCases\Admin\MailSendUseCase;

class MailSendController extends Controller
{
    private $mailSendUseCase;

    public function __construct(MailSendUseCase $mailSendUseCase)
    {
        $this->mailSendUseCase = $mailSendUseCase;
    }

    public function create()
    {
        return view('admin.mail');
    }

    public function send(SendMailRequest $request)
    {
        $this->mailSendUseCase->send($request);
        return redirect()->back()->with('message', 'Mail sent successfully!');
    }
}
