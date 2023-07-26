<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = [
        "user_id", "content", "event_id"
    ];

    /**
     * トピックを作成したユーザーを取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
