<?php

namespace App\UseCases\Event;

use App\Mail\MailSendCommunity;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Reaction;
use App\Models\Topic;
use App\Models\User;
use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CheckEventOrganizerService;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Uid\NilUlid;

/**
 * イベントとコミュニティに関連するビジネスロジックを扱うクラス
 */
class EventCommunityUseCase
{
    protected $checkParticipantStatus;
    protected $checkEventOrganizerService;

    /**
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * コンストラクタ
     *
     * @param CheckEventParticipantStatusUseCase $checkParticipantStatus
     * @param CheckEventOrganizerService $checkEventOrganizerService
     * @param  OperationLogUseCase  $operationLogUseCase
     */
    public function __construct(
        CheckEventParticipantStatusUseCase $checkParticipantStatus,
        CheckEventOrganizerService $checkEventOrganizerService,
        OperationLogUseCase $operationLogUseCase
    ) {
        $this->checkParticipantStatus = $checkParticipantStatus;
        $this->checkEventOrganizerService = $checkEventOrganizerService;
        $this->operationLogUseCase = $operationLogUseCase;
    }

    public function getEmojis()
    {
        return ['👍', '😀', '😃', '😄', '😁', '😆', '😅', '😂', '🤣', '😊', '😇', '🙂', '🙃', '😉', '😌', '😍', '😘', '😗', '😙', '😚', '😋', '😛', '😜', '😝', '🤑', '🤗', '🤭', '🤫', '🤔', '🤐', '🤨', '😐', '😑', '😶', '😏', '😒', '🙄', '😬', '🤥', '😌', '😔', '😪', '🤤', '😴', '😷', '🤒', '🤕', '🤢', '🤮', '🤧', '🥵', '🥶', '🥴', '😵', '🤯', '🤠', '🥳', '😎', '🤓', '🧐', '😕', '😟', '🙁', '😮', '😯', '😲', '😳', '🥺', '😦', '😧', '😨', '😰', '😥', '😢', '😭', '😱', '😖', '😣', '😞', '😓', '😩', '😫', '😤', '😡', '😠', '🤬', '😈', '👿', '💀', '☠️', '💩', '🤡', '👹', '👺', '👻', '👽', '👾', '🤖', '😺', '😸', '😹', '😻', '😼', '😽', '🙀', '😿', '😾'];
    }




    /**
     * イベントへのアクセス権限をチェックする
     *
     * @param int $id イベントのID
     * @return bool 参加者が承認されているか、またはイベント主催者である場合はtrueを返す。それ以外の場合はfalseを返す。
     */
    public function checkAccess($id): bool
    {
        $isParticipantApproved = $this->checkParticipantStatus->execute($id);
        Event::where('id', $id)->where('is_deleted', false)->firstOrFail();

        if ($isParticipantApproved === "approved" || $this->checkEventOrganizerService->check($id)) {
            return true;
        }



        return false;
    }

    /**
     * 指定したイベントに関連するトピックを取得する
     *
     * @param int $id イベントのID
     * @return \Illuminate\Database\Eloquent\Collection 最新のトピックのコレクションを返す
     */
    public function getTopics($id, $perPage = 10)
    {

        $topics = Topic::where("event_id", $id)->latest()->paginate($perPage);

        foreach ($topics as $topic) {
            $topic->content = $this->replaceMentions($topic->content, $topic->event_id);
        }


        return $topics;
    }

    public function replaceMentions($content, $eventId)
    {
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

        preg_match_all('/@(\w+)/', $content, $matches);
        $loginIds = $matches[1] ?? [];

        foreach ($loginIds as $loginId) {
            if ($loginId === 'all') {
                $replacement = "<span class='mention-all'>@{$loginId}</span>";
                $content = str_replace("@{$loginId}", $replacement, $content);
                continue;
            }


            $user = User::where('login_id', $loginId)->first();
            if (!$user || !$this->isParticipant($eventId, $user->id)) {
                continue;
            }

            $class = $loginId === Auth::user()->login_id ? 'mention-me' : 'mention';

            $url = url('/profile/' . $loginId);
            $replacement = "<a href='{$url}' class='{$class}' target='_blank' rel='noopener noreferrer'>@{$loginId}</a>";
            $content = str_replace("@{$loginId}", $replacement, $content);
        }

        return Markdown::parse($content);
    }

    public function isParticipant($eventId, $userId)
    {
        $isOrganizer = Event::where('id', $eventId)
            ->where('organizer_id', $userId)
            ->exists();

        $isApprovedParticipant = EventParticipant::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->exists();

        return $isOrganizer || $isApprovedParticipant;
    }

    /**
     * トピックを保存する
     *
     * @param \Illuminate\Http\Request $request HTTPリクエストインスタンス
     * @return \App\Models\Topic 保存されたトピックのインスタンスを返す
     */
    public function saveTopic(Request $request)
    {

        $eventId = $request->event_id;

        Event::where('id', $eventId)->where('is_deleted', false)->firstOrFail();


        $isParticipantApproved = $this->checkParticipantStatus->execute($eventId);
        if ($isParticipantApproved !== "approved" && !$this->checkEventOrganizerService->check($eventId)) {
            return null;
        }

        $topic = new Topic();

        if ($request->content) {
            $topic->user_id = Auth::id();
            $topic->event_id = $request->event_id;
            $topic->content = $request->content;
            $topic->save();
        }


        $this->operationLogUseCase->store([
            'detail' => "topic:\n{$request->content}\n",
            'user_id' => null,
            'target_event_id' => $request->event_id,
            'target_user_id' => null,
            'target_topic_id' => $topic->id,
            'action' => 'create-topic',
            'ip' => request()->ip(),
        ]);

        // 検出したメンションの処理
        preg_match_all('/@(\w+)/', $request->content, $matches);
        $mentionedLoginIds = $matches[1] ?? [];
        $mentionedLoginIds = array_unique($mentionedLoginIds);

        $event = Event::where('id', $request->event_id)->first();
        $eventOrganizer = $event->organizer;

        $participants = $this->getEventParticipants($request->event_id);
        $participants[] = $eventOrganizer->id;
        $participants = array_unique($participants);

        if (in_array('all', $mentionedLoginIds)) {
            // @allが含まれていたら全参加者を$mentionedUsersに含める
            $mentionedUsers = User::whereIn('id', $participants)->get()->all();
        } else {
            // それ以外の場合はメンションされた参加者だけを$mentionedUsersに含める
            foreach ($mentionedLoginIds as $loginId) {
                $user = $this->getUserByLoginId($loginId);
                if ($user && in_array($user->id, $participants)) {
                    $mentionedUsers[] = $user;
                }
            }
        }

        // メンションされた全員に対して一度でメール送信
        if (!empty($mentionedUsers)) {
            $this->sendMentionNotification($mentionedUsers, $topic, $event->name, Auth::user()->name);
        }

        return $topic;
    }

    /**
     * メンションの通知メールを送る
     *
     * @param array<User> $users ユーザーモデルの配列
     * @param \App\Models\Topic $topic トピックモデル
     * @param string $eventName イベント名
     * @param string $senderName 送信者の名前
     * @return void
     */
    protected function sendMentionNotification(array $users, Topic $topic, string $eventName, string $senderName)
    {
        // メール送信処理
        $mail = new MailSendCommunity();
        $mail->eventMention($eventName, $topic->event_id, $senderName);

        $recipientEmails = array_map(function (User $user) {
            return $user->email;
        }, $users);

        Mail::bcc($recipientEmails)->send($mail);
    }

    /**
     * トピックを削除する
     *
     * @param int $topicId
     * @param int $eventId
     * @param int $userId
     * @return bool
     */
    public function deleteTopic(int $topicId, int $eventId, int $userId)
    {
        $topic = Topic::where('id', $topicId)
            ->where('event_id', $eventId)
            ->where('user_id', $userId)
            ->first();

        if (!$topic) {
            return false;
        }

        $topic->is_deleted = true;
        $topic->save();

        $this->operationLogUseCase->store([
            'detail' => null,
            'user_id' => null,
            'target_event_id' => $eventId,
            'target_user_id' => null,
            'target_topic_id' => $topic->id,
            'action' => 'delete-topic',
            'ip' => request()->ip(),
        ]);


        return true;
    }



    public function getEventParticipants(int $eventId)
    {
        return EventParticipant::where('event_id', $eventId)
            ->where('status', 'approved')
            ->pluck('user_id')
            ->all();
    }

    public function getUserByLoginId(string $loginId)
    {
        // login_idに基づいてユーザーを取得する
        return User::where('login_id', $loginId)->first();
    }

    /**
     * トピックと絵文字の組み合わせに対する反応のデータを取得します。
     *
     * @param Collection $topics
     * @param array $emojis
     * @return array
     */
    public function getTopicReactionData($topics, $emojis)
    {
        $data = [];

        foreach ($topics as $topic) {
            foreach ($emojis as $emoji) {
                $data[$topic->id][$emoji] = [
                    'count' => Reaction::getCountForTopic($topic->id, $emoji),
                    'hasReacted' => Reaction::hasReacted(Auth::id(), $topic->id, $emoji)
                ];
            }
        }

        return $data;
    }
}
