<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\ListEventsUseCase;

/**
 * 管理者向けイベントリストのコントローラクラス
 */
class ListEventsController extends Controller
{
    /**
     * イベントリスト表示のビジネスロジックを提供するユースケース
     *
     * @var ListEventsUseCase イベントリスト表示に使用するUseCaseインスタンス
     */
    private $listEventsUseCase;

    /**
     * ListEventsControllerのコンストラクタ
     *
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param ListEventsUseCase $listUsersUseCase イベントリスト表示のためのユースケース
     */
    public function __construct(ListEventsUseCase $listEventsUseCase)
    {
        $this->listEventsUseCase = $listEventsUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * イベントリストのページを表示するメソッド
     *
     * @return Response イベントリストのビューページ。ユースケースから取得したイベントリストをビューに渡す。
     */
    public function listEvents()
    {
        // イベントリストを取得する
        $events = $this->listEventsUseCase->fetchEventsData();

        // イベントリストをViewに渡して返す
        return view('admin.events-list', $events);
    }
}
