<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public static function getCountForTopic($topicId, $emoji)
    {
        return Reaction::where('topic_id', $topicId)
            ->where('emoji', $emoji)
            ->count();
    }

    public static function userHasReacted($userId, $topicId, $emoji)
    {
        return Reaction::where('user_id', $userId)
            ->where('topic_id', $topicId)
            ->where('emoji', $emoji)
            ->exists();
    }

    public static function hasReacted($userId, $topicId, $emoji)
    {
        return self::where('user_id', $userId)
            ->where('topic_id', $topicId)
            ->where('emoji', $emoji)
            ->exists();
    }
}
