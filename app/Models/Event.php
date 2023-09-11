<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'image_path_a',
        'image_path_b',
        'image_path_c',
        'image_path_d',
        'image_path_e',
        'organizer_id',
    ];

    /**
     * イベントの作成者を取得します。
     *
     * @return BelongsTo
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }
}
