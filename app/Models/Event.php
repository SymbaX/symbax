<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * イベントモデルクラス
 *
 * このクラスは、イベントモデルの操作を行います。
 */
class Event extends Model
{
    /**
     * フィルアブル属性の定義
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name',
        'detail',
        'category',
        'tag',
        'participation_condition',
        'external_link',
        'date',
        'deadline_date',
        'place',
        'number_of_recruits',
        'image_path',
        'organizer_id',
    ];
}
