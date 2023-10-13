<?php

namespace App\Models;

use Carbon\Carbon;
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
        'image_path',
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

    /**
     * イベントの状態を取得します。
     *
     * @return void
     */
    public function getStatus()
    {
        if ($this->is_deleted) {
            return 'Deleted';
        }


        // 現在日時が開催日の終わり（23:59:59）を過ぎていれば締切
        $date = Carbon::parse($this->date)->endOfDay();
        if (Carbon::now() > $date) {
            return 'After the event';
        }

        // 現在日時が締切日の終わり（23:59:59）を過ぎていれば締切
        $deadline = Carbon::parse($this->deadline_date)->endOfDay();
        if (Carbon::now() > $deadline) {
            return 'Deadline';
        }

        return 'Currently recruiting';
    }
}
