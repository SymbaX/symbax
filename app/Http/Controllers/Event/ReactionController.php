<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Reaction;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReactionController extends Controller
{
    public function store(Request $request, Topic $topic)
    {
        $emoji = $request->input('emoji');

        if (Reaction::userHasReacted(Auth::id(), $topic->id, $emoji)) {
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
