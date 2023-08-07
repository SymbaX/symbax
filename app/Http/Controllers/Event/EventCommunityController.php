<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\TopicRequest;
use App\UseCases\Event\EventCommunityUseCase;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * イベントコミュニティに関連するアクションを制御するクラス
 */
class EventCommunityController extends Controller
{
    protected $useCase;

    /**
     * コンストラクタ
     *
     * @param EventCommunityUseCase $useCase イベントコミュニティに関連するビジネスロジックを扱うUseCase
     */
    public function __construct(EventCommunityUseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    /**
     * トピック作成画面を表示する
     *
     * @param \Illuminate\Http\Request $request HTTPリクエストインスタンス
     * @param int $id イベントID
     * @return \Illuminate\View\View トピック作成画面のビューを返す
     */
    public function create(Request $request, $id): View
    {
        if (!$this->useCase->checkAccess($id)) {
            abort(403);
        }

        $topics = $this->useCase->getTopics($id);

        $emojis = $this->useCase->getEmojis();

        $reactionData = $this->useCase->getTopicReactionData($topics, $emojis);

        return view('event.community', ['event' => $id, 'topics' => $topics, 'emojis' => $emojis, 'reactionData' => $reactionData]);
    }

    /**
     * トピックを保存する
     *
     * @param \App\Http\Requests\Event\TopicRequest $request フォームリクエスト
     * @return \Illuminate\Http\RedirectResponse リダイレクトレスポンスを返す
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
     * トピックを削除する
     *
     * @param Request $request
     * @param int $eventId
     * @param int $topicId
     * @return \Illuminate\Http\RedirectResponse
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
