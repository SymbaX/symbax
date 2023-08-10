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
     * メール送信のビジネスロジックを提供するユースケース
     * 
     * @var MailSendUseCase メール送信に使用するUseCaseインスタンス
     */
    private $mailSendUseCase;

    /**
     * MailSendControllerのコンストラクタ
     *
     * 使用するユースケースをインジェクション（注入）します。
     * 
     * @param MailSendUseCase $mailSendUseCase メール送信のユースケース
     */
    public function __construct(MailSendUseCase $mailSendUseCase)
    {
        $this->mailSendUseCase = $mailSendUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * メール作成画面を表示するメソッド
     *
     * @return View メール作成画面のビュー
     */
    public function showMailForm()
    {
        // メール作成画面を表示する
        return view('admin.mail');
    }

    /**
     * メールの送信をするメソッド
     * 
     * @param SendMailRequest $request 送信するメールの情報が含まれるリクエスト
     * 
     * @return RedirectResponse メール作成画面にリダイレクトする
     */
    public function sendMail(SendMailRequest $request)
    {
        // メールを送信する
        $this->mailSendUseCase->performMailSending($request);

        // メール作成画面にリダイレクトする
        return redirect()->back()->with('status', 'mail-send');
    }
}
