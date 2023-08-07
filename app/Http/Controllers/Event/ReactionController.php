<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Reaction;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * リアクションコントローラー
 * 
 * ユーザーの反応を管理するコントローラーです。
 */
class ReactionController extends Controller
{
    /**
     * ユーザーの反応（リアクション）を保存または削除します。
     *
     * @param Request $request
     * @param Topic $topic
     * 
     * @return \Illuminate\Http\RedirectResponse
     * 
     * ユーザーがすでに反応している場合は反応を削除し、反応していない場合は新たに反応を保存します。
     */
    public function store(Request $request, Topic $topic)
    {
        $request->validate([
            'emoji' => 'required'
        ]);

        $emoji = $request->input('emoji');

        if (Reaction::hasReacted(Auth::id(), $topic->id, $emoji)) {
            Reaction::where('user_id', Auth::id())
                ->where('topic_id', $topic->id)
                ->where('emoji', $emoji)
                ->delete();
        } else {
            $reaction = new Reaction;
            $reaction->user_id = Auth::id();
            $reaction->topic_id = $topic->id;
            $reaction->emoji = $emoji;
            $reaction->save();
        }

        return back();
    }
}
