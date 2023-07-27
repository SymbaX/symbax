<?php

namespace App\UseCases\Admin;

use App\Mail\MailSend;
use App\Mail\MailSendAdmin;
use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Support\Facades\Mail;

class MailSendUseCase
{
    private $operationLogUseCase;

    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    public function send($request)
    {
        // メール送信処理
        $mail = new MailSendAdmin();
        $mail->sendEmail($request->title, $request->body);
        Mail::to($request->email)->send($mail);

        return true;
    }
}
