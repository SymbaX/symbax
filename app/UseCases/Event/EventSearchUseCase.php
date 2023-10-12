<?php

namespace App\UseCases\Event;

use App\Models\Event;

/**
 * イベント検索ユースケース
 *
 * イベントの検索機能に関連するユースケースを提供するクラスです。
 */
class EventSearchUseCase
{
    /* =================== 以下メインの処理 =================== */

    /**
     * イベントの検索
     *
     * @param string $selectedCategoryId
     * @param string $keyword
     * @return void
     */
    public function search($selectedCategoryId, $keyword)
    {
        // イベントの検索のクエリを作成
        $query = Event::query();

        // 主催者が論理削除されていないイベントのみを取得
        $query->whereHas('organizer', function ($q) {
            $q->whereNull('deleted_at');
        });

        if ($selectedCategoryId == "All Categories") {      // カテゴリーが「すべて」の場合
            // キーワードのみで検索
            $query->where('detail', 'LIKE', "%{$keyword}%");
        } elseif (!empty($keyword)) {                       // キーワードが空の場合
            // カテゴリーのみで検索
            $query->where('detail', 'LIKE', "%{$keyword}%")
                ->where('category', '=', "{$selectedCategoryId}");
        } else {                                            // キーワードもカテゴリーも指定されていない場合
            // すべてのイベントを表示
            $query->where('category', '=', "{$selectedCategoryId}");
        }

        // クエリを実行し、結果を返す
        return $query->paginate(12);
    }
}
