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
        $query = Event::query();

        if ($selectedCategoryId == "All Categories") {
            $query->where('detail', 'LIKE', "%{$keyword}%");
        } elseif (!empty($keyword)) {
            $query->where('detail', 'LIKE', "%{$keyword}%")
                ->where('category', '=', "{$selectedCategoryId}");
        } else {
            $query->where('category', '=', "{$selectedCategoryId}");
        }

        return $query->paginate(12);
    }
}
