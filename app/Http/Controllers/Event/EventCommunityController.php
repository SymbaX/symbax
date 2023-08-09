<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\TopicRequest;
use App\UseCases\Event\EventCommunityUseCase;
use App\UseCases\Event\ReactionUseCase;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * イベントコミュニティのアクションを制御するコントローラー
 */
class EventCommunityController extends Controller
{
    /**
     * イベントコミュニティのビジネスロジックを提供するユースケース
     *
     * @var EventCommunityUseCase イベントコミュニティに使用するUseCaseインスタンス
     */
    protected $useCase;

    /**
     * リアクションのビジネスロジックを提供するユースケース
     *
     * @var ReactionUseCase
     */
    protected $reactionUseCase;

    /**
     * EventCommunityControllerのコンストラクタ
     * 
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param EventCommunityUseCase $useCase イベントコミュニティに関するユースケース
     * @param ReactionUseCase $reactionUseCase リアクションに関するユースケース
     */
    public function __construct(EventCommunityUseCase $useCase, ReactionUseCase $reactionUseCase)
    {
        $this->useCase = $useCase;
        $this->reactionUseCase = $reactionUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * トピック作成画面を表示するメソッド
     * 
     * 指定されたイベントIDに関連するトピック作成画面を表示するためのメソッド。
     *
     * @param int $id イベントID
     * @return \Illuminate\View\View トピック作成画面
     */
    public function create($id): View
    {
        if (!$this->useCase->checkAccess($id)) {
            abort(403);
        }

        $event = $this->useCase->getEvent($id);
        $topics = $this->useCase->getTopics($id);

        $emojis = $this->reactionUseCase->getEmojis();

        $reactionData = $this->useCase->getTopicReactionData($topics, $emojis);

        return view('event.community', ['event' => $event, 'topics' => $topics, 'emojis' => $emojis, 'reactionData' => $reactionData]);
    }

    /**
     * トピック情報を保存するメソッド
     * 
     * リクエストから送信されたトピック情報をデータベースに保存するメソッド。
     *
     * @param \App\Http\Requests\Event\TopicRequest $request トピックの情報を持つリクエスト
     * @return \Illuminate\Http\RedirectResponse 保存後のリダイレクトレスポンス
     */
    public function save(TopicRequest $request)
    {
        $topic = $this->useCase->saveTopic($request);
        if ($topic === null) {
            abort(403);
        }

        return redirect()->route('event.community', ['event_id' => $topic->event_id]);
    }

    /**
     * 指定されたトピックを削除するメソッド
     * 
     * イベントIDとトピックIDを基に、対応するトピックを削除するメソッド。
     *
     * @param Request $request リクエスト情報
     * @param int $eventId イベントID
     * @param int $topicId トピックID
     * @return \Illuminate\Http\RedirectResponse 削除後のリダイレクトレスポンス
     */
    public function deleteTopic(Request $request, $eventId, $topicId)
    {
        $userId = $request->user()->id;

        if (!$this->useCase->deleteTopic($topicId, $eventId, $userId)) {
            abort(403);
        }

        return redirect()->route('event.community', ['event_id' => $eventId]);
    }
}
