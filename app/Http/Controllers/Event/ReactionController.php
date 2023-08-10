<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use App\UseCases\Event\ReactionUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

/**
 * リアクションコントローラー
 * 
 * トピックに対するユーザーのリアクションを管理するコントローラーです。
 */
class ReactionController extends Controller
{
    /**
     * リアクションのビジネスロジックを提供するユースケース
     * 
     * @var ReactionUseCase リアクションに使用するUseCaseインスタンス
     */
    private $reactionUseCase;

    /**
     * ReactionControllerのコンストラクタ
     * 
     * 使用するユースケースをインジェクション（注入）します。
     * 
     * @param ReactionUseCase $reactionUseCase リアクションに関する処理を提供するユースケース
     */
    public function __construct(ReactionUseCase $reactionUseCase)
    {
        $this->reactionUseCase = $reactionUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * リアクションを保存するメソッド。
     * 
     * ユーザーがトピックに対して選択した絵文字のリアクションを保存します。
     * 
     * @param Request $request リアクション情報を持つリクエストオブジェクト
     * @param Topic $topic リアクションの対象となるトピック
     * @return RedirectResponse リダイレクトレスポンス
     */
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
