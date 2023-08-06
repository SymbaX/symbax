<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendMailRequest;
use App\UseCases\Admin\MailSendUseCase;

/**
 * Class MailSendController
 * 
 * 管理者向けメール送信のコントローラーです。
 */
class MailSendController extends Controller
{
    /**
     * @var MailSendUseCase
     * 
     * メール送信のためのユースケース
     */
    private $mailSendUseCase;

    /**
     * MailSendController constructor.
     *
     * @param MailSendUseCase $mailSendUseCase
     * 
     * メール送信のユースケースを注入します。
     */
    public function __construct(MailSendUseCase $mailSendUseCase)
    {
        $this->mailSendUseCase = $mailSendUseCase;
    }

    /**
     * メール作成画面を表示します。
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('admin.mail');
    }

    /**
     * メールを送信します。
     * 
     * @param SendMailRequest $request
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(SendMailRequest $request)
    {
        $this->mailSendUseCase->send($request);
        return redirect()->back()->with('status', 'mail-send');
    }
}
