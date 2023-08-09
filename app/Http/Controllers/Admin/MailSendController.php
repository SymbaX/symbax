<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendMailRequest;
use App\UseCases\Admin\MailSendUseCase;

/**
 * 管理者向けメール送信のコントローラクラス
 */
class MailSendController extends Controller
{
    /**
     * メール送信のためのユースケース
     * 
     * @var MailSendUseCase
     */
    private $mailSendUseCase;

    /**
     * MailSendControllerのコンストラクタ
     *
     * @param MailSendUseCase $mailSendUseCase メール送信のユースケース
     */
    public function __construct(MailSendUseCase $mailSendUseCase)
    {
        $this->mailSendUseCase = $mailSendUseCase;
    }

    /**
     * メール作成画面を表示
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showMailForm()
    {
        return view('admin.mail');
    }

    /**
     * メールの送信処理
     * 
     * @param SendMailRequest $request 送信するメールの情報が含まれるリクエスト
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendMail(SendMailRequest $request)
    {
        $this->mailSendUseCase->performMailSending($request);
        return redirect()->back()->with('status', 'mail-send');
    }
}
