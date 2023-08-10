<?php

namespace App\UseCases\Event;

use App\Models\Reaction;
use App\Services\CheckEventOrganizerService;

/**
 * トピックのリアクションを扱うユースケース
 */
class ReactionUseCase
{
    /**
     * イベント参加者のステータスをチェックするユースケース
     * 
     * @var CheckEventParticipantStatusUseCase
     */
    protected $checkParticipantStatus;

    /**
     * イベントオーガナイザーを確認するサービス
     * 
     * @var CheckEventOrganizerService
     */
    protected $checkEventOrganizerService;

    /**
     * EventCommunityUseCaseのコンストラクタ
     * 
     * 使用するユースケースとサービスをインジェクション（注入）します。
     * 
     * @param CheckEventParticipantStatusUseCase $checkParticipantStatus イベント参加者のステータスをチェックするユースケース
     * @param CheckEventOrganizerService $checkEventOrganizerService イベントオーガナイザーを確認するサービス
     */
    public function __construct(
        CheckEventParticipantStatusUseCase $checkParticipantStatus,
        CheckEventOrganizerService $checkEventOrganizerService
    ) {
        $this->checkParticipantStatus = $checkParticipantStatus;
        $this->checkEventOrganizerService = $checkEventOrganizerService;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * 使用可能な絵文字の一覧を取得する
     *
     * @return array 絵文字の配列
     */
    public function getEmojis()
    {
        return [
            'face_and_persons' =>     [
                '🙂', '😀', '😃', '😄', '😁', '😅', '😆', '🤣', '😂', '🙃', '😉', '😊', '😇', '😎', '🧐',
                '🥳', '🥰', '😍', '🤩', '😙', '🥲', '😜', '🤪', '😝', '🤑', '🤗',
                '😒', '🙄', '😬', '😮‍💨', '🤥', '😪', '😴', '😌', '😔', '🤤', '😷', '🤒', '🤕', '🤢', '🤮', '🤧', '🥵', '🥶', '🥴', '😵',
                '🤯', '😕', '😟', '🙁', '☹', '😮', '😯', '😲', '😳', '🥺', '😦', '😧', '😨', '😰', '😥', '😢', '😭', '😱', '😖', '😣',
                '😞', '😓', '😩', '😫', '🥱', '😤', '😡', '😠', '🤬',  '💩',

            ],
            'emotions' =>     ['💖', '💗', '💕', '❣', '💔', '💯', '💢', '💥', '💫', '💦', '💬', '💤'],
            'tasks' => [
                '✅', '❌', '🎉', '👀', '🙏', '👍', '🙎', '🙎‍♂‍', '🙎‍♀‍', '🙅', '🙅‍♂‍', '🙅‍♀‍', '🙆', '🙆‍♂‍', '🙆‍♀‍', '💁', '💁‍♂‍',
                '💁‍♀‍', '🙋', '🙋‍♂‍', '🙋‍♀‍', '🙇', '🙇‍♂‍', '🙇‍♀‍', '🤷', '🤷‍♂‍', '🤷‍♀‍'
            ]
        ];
    }

    /**
     * ユーザーの反応（リアクション）を保存または削除します。
     *
     * @param int $userId
     * @param int $topicId
     * @param string $emoji
     * 
     * @return void
     */
    public function storeReaction(int $userId, int $topicId, string $emoji)
    {
        // topicIdからeventIdを取得
        $topic = \App\Models\Topic::find($topicId);
        if (!$topic) {
            // トピックが見つからない場合は、処理を中断します。
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('The topic is not found.');
        }
        $eventId = $topic->event_id;

        // 参加者のステータスが「承認済み」であるか、またはイベントの主催者であるかをチェック
        $isParticipantApproved = $this->checkParticipantStatus->execute($eventId);
        if ($isParticipantApproved !== "approved" && !$this->checkEventOrganizerService->check($eventId)) {
            return null;
        }

        if (Reaction::hasReacted($userId, $topicId, $emoji)) {
            Reaction::where('user_id', $userId)
                ->where('topic_id', $topicId)
                ->where('emoji', $emoji)
                ->delete();
        } else {
            $reaction = new Reaction;
            $reaction->user_id = $userId;
            $reaction->topic_id = $topicId;
            $reaction->emoji = $emoji;
            $reaction->save();
        }
    }
}
