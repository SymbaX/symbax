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
use Illuminate\Support\Facades\Mail;

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
     *
     * @param int $id イベントのID
     * @return \Illuminate\Database\Eloquent\Collection 最新のトピックのコレクションを返す
     */
    public function getEvent($id)
    {
        $event = Event::findOrFail($id);


        return $event;
    }

    /**
     * 指定したイベントに関連するトピックを取得する
     *
     * @param int $id イベントのID
     * @return \Illuminate\Database\Eloquent\Collection 最新のトピックのコレクションを返す
     */
    public function getTopics($id, $per_page = 10)
    {
        $topics = Topic::where("event_id", $id)->latest()->paginate($per_page);

        foreach ($topics as $topic) {
            $topic->content = $this->replaceMentions($topic->content, $topic->event_id);
        }

        return $topics;
    }

    /**
     * トピックを表示する際、文字列中のメンションを特定のフォーマットに変換します。
     *
     * メンションは @ユーザー名 の形式で、全てのメンションをHTMLリンクに変換します。
     * 特別なメンション @all は全員を指すものとしてスタイルを適用します。
     * 
     * @param string $content 元の文字列
     * @param int $eventId イベントID
     * @return string 変換後の文字列
     */
    public function replaceMentions($content, $eventId)
    {
        // 元の文字列をエスケープ
        $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

        // 文字列中からすべてのメンションを検出
        preg_match_all('/@(\w+)/', $content, $matches);
        $mentions = $matches[1] ?? [];

        foreach ($mentions as $mention) {
            if ($mention === 'all') { // メンションが'@all'の場合
                $replacement = "<span class='mention-all'>@{$mention}</span>";
                $content = str_replace("@{$mention}", $replacement, $content);
                continue;
            }

            // mentionのlogin_idを持つUserが存在するか
            $user = User::where('login_id', $mention)->first();

            // ユーザーが存在しない、またはイベントの参加者でない場合、そのメンションは無視
            if (!$user || !$this->isParticipant($eventId, $user->id)) {
                continue;
            }

            // ユーザー名が現在のユーザーと一致するかチェックし、一致する場合はクラスとして'mention-me'を、
            // 一致しない場合は'mention'を使用
            $class = $mention === Auth::user()->login_id ? 'mention-me' : 'mention';

            // ユーザーのプロフィールページへのURLを生成
            $url = url('/profile/' . $mention);

            // メンションをHTMLリンクに変換
            $replacement = "<a href='{$url}' class='{$class}' target='_blank' rel='noopener noreferrer'>@{$mention}</a>";
            $content = str_replace("@{$mention}", $replacement, $content);
        }

        // 改行を2つに変換
        $content = str_replace("\n", "\n\n", $content);

        return Markdown::parse($content); // Markdown形式のコンテンツをHTMLに変換して返す
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

        // イベントIDに紐づく、削除されていないイベントを取得。該当するイベントが存在しない場合は例外をスロー
        $event = Event::where('id', $eventId)->where('is_deleted', false)->firstOrFail();

        // 参加者のステータスが「承認済み」であるか、またはイベントの主催者であるかをチェック
        $isParticipantApproved = $this->checkParticipantStatus->execute($eventId);
        if ($isParticipantApproved !== "approved" && !$this->checkEventOrganizerService->check($eventId)) {
            return null;
        }

        // 新しいトピックを作成
        $topic = $this->createTopic($request);
        // トピックの作成を操作ログに記録
        $this->logTopicCreation($topic, $request);

        // リクエストの内容に含まれるメンションに紐づくユーザーを取得
        $mentionedUsers = $this->getMentionedUsers($request->content, $eventId);

        // メンションされたユーザーが存在する場合、メンションの通知を送信
        if (!empty($mentionedUsers)) {
            $this->sendMentionNotification($mentionedUsers, $topic, $event->name, Auth::user()->name);
        }

        return $topic;
    }

    /**
     * ユーザーがイベントの参加者、または主催者であるかを判定します。
     * 
     * @param int $eventId イベントID
     * @param int $userId ユーザーID
     * @return bool 参加者または主催者であればtrue、そうでなければfalse
     */
    public function isParticipant($eventId, $userId)
    {
        // ユーザーがイベントの主催者であるかどうかをチェック
        $isOrganizer = Event::where('id', $eventId)
            ->where('organizer_id', $userId)
            ->exists();

        // ユーザーが承認済みのイベント参加者であるかどうかをチェック
        $isApprovedParticipant = EventParticipant::where('event_id', $eventId)
            ->where('user_id', $userId)
            ->where('status', 'approved')
            ->exists();

        // ユーザーが主催者または承認済みの参加者であれば、関数はtrueを返します。
        // それ以外の場合はfalseを返します。
        return $isOrganizer || $isApprovedParticipant;
    }

    /**
     * 新規トピックを作成します。
     * 
     * @param \Illuminate\Http\Request $request HTTPリクエストインスタンス
     * @return \App\Models\Topic 作成したトピックのインスタンス
     */
    private function createTopic(Request $request)
    {
        $topic = new Topic();

        if ($request->content) {
            $topic->user_id = Auth::id();
            $topic->event_id = $request->event_id;
            $topic->content = $request->content;
            $topic->save();
        }

        return $topic;
    }

    /**
     * トピック作成の操作ログを保存します。
     * 
     * @param \App\Models\Topic $topic トピックモデル
     * @param \Illuminate\Http\Request $request HTTPリクエストインスタンス
     * @return void
     */
    private function logTopicCreation(Topic $topic, Request $request)
    {
        $this->operationLogUseCase->store([
            'detail' => "topic:\n{$request->content}\n",
            'user_id' => null,
            'target_event_id' => $request->event_id,
            'target_user_id' => null,
            'target_topic_id' => $topic->id,
            'action' => 'create-topic',
            'ip' => request()->ip(),
        ]);
    }

    /**
     * 文字列中のメンションから、メンションされたユーザーの一覧を取得します。
     * 
     * @param string $content 元の文字列
     * @param int $eventId イベントID
     * @return array<User> メンションされたユーザーモデルの配列
     */
    private function getMentionedUsers(string $content, int $eventId)
    {
        // 正規表現を用いてメンション（@以降の単語）を全て取り出す
        preg_match_all('/@(\w+)/', $content, $matches);

        // メンションされたユーザーのIDを取得
        $mentionedLoginIds = $matches[1] ?? [];

        // ユーザーIDの重複を排除
        $mentionedLoginIds = array_unique($mentionedLoginIds);

        // イベント情報を取得
        $event = Event::where('id', $eventId)->first();

        // イベントの主催者を取得
        $eventOrganizer = $event->organizer;

        // イベントの参加者をすべて取得
        $participants = EventParticipant::where('event_id', $eventId)
            ->where('status', 'approved')
            ->pluck('user_id')
            ->all();;

        // 主催者のIDも参加者として追加
        $participants[] = $eventOrganizer->id;

        // 重複する参加者を排除
        $participants = array_unique($participants);

        // 'all' がメンションされていた場合、全ての参加者を返す
        if (in_array('all', $mentionedLoginIds)) {
            return User::whereIn('id', $participants)->get()->all();
        }

        // メンションされたユーザーを格納する配列を初期化
        $mentionedUsers = [];
        foreach ($mentionedLoginIds as $loginId) {

            // ユーザー情報を取得
            $user = User::where('login_id', $loginId)->first();

            // ユーザーが存在し、かつそのユーザーが参加者である場合、メンションされたユーザーとして追加
            if ($user && in_array($user->id, $participants)) {
                $mentionedUsers[] = $user;
            }
        }

        // メンションされたユーザーのリストを返す
        return $mentionedUsers;
    }

    /**
     * メンションの通知メールを送る
     *
     * メンションが含まれるトピックが投稿されたときに、メンションされたユーザー全員に対して通知メールを送信します。
     *
     * @param array<User> $users メンションされたユーザーモデルの配列
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
            foreach ($emojis as $emojiCategory => $emojiList) {
                foreach ($emojiList as $emoji) {
                    $data[$topic->id][$emoji] = [
                        'count' => Reaction::getCountForTopic($topic->id, $emoji),
                        'hasReacted' => Reaction::hasReacted(Auth::id(), $topic->id, $emoji)
                    ];
                }
            }
        }

        return $data;
    }
}
