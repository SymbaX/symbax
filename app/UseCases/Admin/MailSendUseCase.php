<?php

namespace App\UseCases\Admin;

use App\Mail\MailSendAdmin;
use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Support\Facades\Mail;

/**
 * 管理者向けメール送信ユースケースクラス
 */
class MailSendUseCase
{
    /**
     * 操作ログを保存するためのユースケースインスタンス。
     * このユースケースを利用して、システムの操作に関するログの記録処理を行います。
     * 
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * コンストラクタ
     * 
     * 操作ログを管理するユースケースをインジェクションします。
     *
     * @param OperationLogUseCase $operationLogUseCase 操作ログに関するユースケース
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * メールの送信処理
     *
     * @param $request メールの詳細が含まれるリクエスト
     * 
     * @return bool 処理が成功した場合はtrueを返す
     */
    public function performMailSending($request): bool
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
