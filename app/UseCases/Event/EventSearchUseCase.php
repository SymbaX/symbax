<?php

namespace App\UseCases\Event;

use App\Models\Event;

class EventSearchUseCase
{
    /* =================== 以下メインの処理 =================== */
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
