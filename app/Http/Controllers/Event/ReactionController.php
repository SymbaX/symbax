<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Reaction;
use App\Models\Topic;
use App\UseCases\Event\ReactionUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * リアクションコントローラー
 * 
 * ユーザーの反応を管理するコントローラーです。
 */
class ReactionController extends Controller
{
    private $reactionUseCase;

    public function __construct(ReactionUseCase $reactionUseCase)
    {
        $this->reactionUseCase = $reactionUseCase;
    }

    public function store(Request $request, Topic $topic)
    {
        $emojis = $this->reactionUseCase->getEmojis();
        $emojisFlat = array_merge([], ...array_values($emojis));

        $request->validate([
            'emoji' => [
                'required',
                Rule::in($emojisFlat)
            ]
        ]);

        $emoji = $request->input('emoji');
        $this->reactionUseCase->storeReaction(Auth::id(), $topic->id, $emoji);

        return back();
    }
}
