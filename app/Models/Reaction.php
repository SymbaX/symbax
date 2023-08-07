<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * リアクションモデルクラス
 *
 * ユーザーのトピックへの反応を表すモデルクラスです。
 */
class Reaction extends Model
{
    use HasFactory;

    /**
     * リアクションが属するユーザーを取得します。
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * リアクションが属するトピックを取得します。
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * 指定のトピックと絵文字に対する反応の総数を取得します。
     *
     * @param int $topicId
     * @param string $emoji
     * @return int
     */
    public static function getCountForTopic($topicId, $emoji)
    {
        return self::where('topic_id', $topicId)
            ->where('emoji', $emoji)
            ->count();
    }

    /**
     * ユーザーが指定のトピックと絵文字に対して既に反応しているかどうかを判定します。
     *
     * @param int $userId
     * @param int $topicId
     * @param string $emoji
     * @return bool
     */
    public static function hasReacted($userId, $topicId, $emoji)
    {
        return self::where('user_id', $userId)
            ->where('topic_id', $topicId)
            ->where('emoji', $emoji)
            ->exists();
    }
}
