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
        $mail->sendEmail($request->subject, $request->body);
        Mail::to($request->email)->send($mail);

        $this->operationLogUseCase->store([
            'detail' => "▼ subject: {$request->subject}\n" .
                "▼ email: {$request->email}\n\n" .
                "▼ body: \n{$request->body}\n",
            'user_id' => auth()->user()->id,
            'target_event_id' => null,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'admin-mail-send',
            'ip' => request()->ip(),
        ]);

        return true;
    }
}
