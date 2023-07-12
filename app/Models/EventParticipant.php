<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * イベント参加者モデルクラス
 *
 * このクラスは、イベント参加者モデルの操作を行います。
 */
class EventParticipant extends Model
{
    /**
     * テーブル名
     *
     * @var string
     */
    protected $table = 'event_participants';

    /**
     * フィルアブル属性の定義
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'user_id',
        'event_id',
        'status',
    ];
}
