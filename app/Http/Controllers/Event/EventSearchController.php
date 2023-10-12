<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\EventCategories;
use App\UseCases\Event\EventSearchUseCase;
use Illuminate\Http\Request;

/**
 * イベント検索コントローラー
 *
 * イベントの検索機能に関連するアクションを提供するコントローラーです。
 */
class EventSearchController extends Controller
{
    /**
     * イベント検索のビジネスロジックを提供するユースケース
     *
     * @var EventSearchUseCase イベント検索に使用するUseCaseインスタンス
     */
    private $eventSearchUseCase;

    /**
     * EventSearchControllerのコンストラクタ
     *
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param EventSearchUseCase $eventSearchUseCase イベント検索に使用するUseCaseのインスタンス
     */
    public function __construct(EventSearchUseCase $eventSearchUseCase)
    {
        $this->eventSearchUseCase = $eventSearchUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * イベント検索画面を表示するメソッド
     *
     * @return View イベント検索画面のビュー。
     */
    public function indexSearch(Request $request)
    {
        // カテゴリー一覧を取得
        $categories = EventCategories::all();

        // 検索条件を取得
        $selectedCategoryId = $request->input('category');
        $keyword = $request->input('keyword');

        // イベントを検索
        $events = $this->eventSearchUseCase->search($selectedCategoryId, $keyword);

        // イベント検索画面を表示
        return view('event.list-search', compact('events', 'keyword', 'categories', 'selectedCategoryId'));
    }
}
