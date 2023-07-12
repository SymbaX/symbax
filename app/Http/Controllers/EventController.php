<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\OperationLogController;


/**
 * イベントコントローラークラス
 * 
 * このクラスはイベントに関する処理を行うコントローラーです。
 */
class EventController extends Controller
{
    /**
     * @var OperationLogController
     */
    private $operationLogController;

    /**
     * OperationLogControllerの新しいインスタンスを作成します。
     *
     * @param  OperationLogController  $operationLogController
     * @return void
     */
    public function __construct(OperationLogController $operationLogController)
    {
        $this->operationLogController = $operationLogController;
    }

    /**
     * イベント作成フォームの表示
     *
     * イベント作成フォームを表示します。
     *
     * @return \Illuminate\View\View
     */
    public function createView(): View
    {
        return view('event.create');
    }


    /**
     * イベントの作成
     *
     * リクエストから受け取ったデータを検証し、新しいイベントを作成します。
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'max:20'],
            'detail' => ['required', 'max:1000'],
            'category' => ['required', 'max:30'],
            'tag' => ['required', 'max:30'],
            'participation_condition' => ['required', 'max:100'],
            'external_link' => ['required', 'max:255', 'url'],
            'date' => ['required', 'date'],
            'deadline_date' => ['required', 'date'],
            'place' => ['required', 'max:50'],
            'number_of_recruits' => ['required', 'integer', 'min:1'],
            'image_path' => ['required', 'max:5000', 'mimes:jpg,jpeg,png,gif'],
        ]);

        $validatedData['image_path'] = $request->file('image_path')->store('public/events');

        $validatedData['organizer_id'] = Auth::id();

        $event = Event::create($validatedData);

        $this->operationLogController->store('イベントを作成しました ID:' . $event->id);

        return redirect()->back()->with('status', 'event-create');
    }

    /**
     * イベント一覧の表示
     *
     * 今日以降の日付で開催されるイベントを日付の降順でページネーションして表示します。
     *
     * @return \Illuminate\View\View
     */
    public function listUpcoming()
    {
        $events = Event::whereDate('date', '>=', Carbon::today())
            ->orderBy('date', 'desc')
            ->paginate(12);
        return view('event.list-upcoming', ['events' => $events]);
    }

    /**
     * 全てのイベント一覧の表示
     *
     * すべてのイベントを日付の降順でページネーションして表示します。
     *
     * @return \Illuminate\View\View
     */
    public function listAll()
    {
        $events = Event::orderBy('date', 'desc')
            ->paginate(12);
        return view('event.list-all', ['events' => $events]);
    }

    /**
     * イベントの詳細表示
     *
     * 指定されたイベントの詳細情報を表示します。
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function detail($id)
    {
        $event = Event::findOrFail($id);
        $detail_markdown = Markdown::parse(e($event->detail));

        $participants = EventParticipant::where('event_id', $id)
            ->join('users', 'users.id', '=', 'event_participants.user_id')
            ->select('users.id as user_id', 'users.name', 'event_participants.status')
            ->get();


        $organizer_name = User::where('id', $event->organizer_id)->value('name');

        // 現在のユーザーがイベントの作成者であるかをチェック
        $is_organizer = $event->organizer_id === Auth::id();

        // 現在のユーザーがイベントに参加しているかをチェック
        $is_join = $event->organizer_id !== Auth::id() && !$participants->pluck('user_id')->contains(Auth::id());


        $your_status = $participants->where('user_id', Auth::id())->first()->status ?? 'not-join';

        return view('event.detail', [
            'event' => $event,
            'detail_markdown' => $detail_markdown,
            'participants' => $participants,
            'is_organizer' => $is_organizer,
            'is_join' => $is_join,
            'your_status' => $your_status,
            'organizer_name'  => $organizer_name
        ]);
    }



    /**
     * イベントへの参加リクエスト
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントに参加リクエストを送信します。
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function joinRequest(Request $request)
    {
        $user_id = Auth::id();
        $event_id = $request->input('event_id');
        $event = Event::findOrFail($event_id);
        $participantCount = EventParticipant::where('event_id', $event_id)->count();

        // 自分が作成したイベントであればエラー
        if ($event->organizer_id == $user_id) {
            return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'your-event-owner');
        }

        // ユーザーが既に参加している場合はエラー
        $alreadyJoined = EventParticipant::where('event_id', $event_id)->where('user_id', $user_id)->exists();
        if ($alreadyJoined) {
            return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'already-joined');
        }

        // 参加可能な枠がなければエラー
        if ($event->number_of_recruits <= $participantCount) {
            return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'no-participation-slots');
        }

        EventParticipant::create([
            'user_id' => $user_id,
            'event_id' => $event_id,
            'status' => 'pending',
        ]);

        $this->operationLogController->store('ID:' . $event_id . 'のイベントに参加リクエストを送信しました');

        return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'join-request-event');
    }

    /**
     * イベントへの参加のキャンセル
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントへの参加をキャンセルします。
     *
     * @param  Request * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancelJoin(Request $request)
    {
        $user_id = Auth::id();
        $event_id = $request->input('event_id');

        // ユーザーが参加しているか確認
        $participant = EventParticipant::where('event_id', $event_id)->where('user_id', $user_id)->first();

        // 参加していない場合はエラー
        if (!$participant) {
            return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'not-joined');
        }

        // 参加をキャンセル
        $participant->delete();

        $this->operationLogController->store('ID:' . $event_id . 'のイベントへの参加をキャンセルしました');


        return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'canceled-join');
    }


    /**
     * イベントへの参加の承認
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントへの参加を承認します。
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function joinApproval(Request $request)
    {
        $event_id = $request->input('event_id');
        $user_Id = $request->input('user_id');
        $status = $request->input('status');

        $event = Event::find($event_id);

        // イベント作成者の場合の処理
        if ($event->organizer_id === Auth::id()) {
            $participant = EventParticipant::where('event_id', $event_id)
                ->where('user_id', $user_Id)
                ->first();

            if ($participant) {
                $participant->status = $status;
                $participant->save();
            }
        }

        // その他の処理...

        return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'approval-join');
    }


    /**
     * イベントの削除
     *
     * 指定されたイベントを削除します。参加者がいる場合は削除できません。
     * 関連する画像ファイルも削除されます。
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($id)
    {
        $event = Event::findOrFail($id);

        // イベントに参加者が登録されているかどうかを確認します
        $participantCount = EventParticipant::where('event_id', $id)->count();
        if ($participantCount > 0) {
            return redirect()->route('event.detail', ['id' => $id])->with('status', 'cannot-delete-with-participants');
        }

        // イベントを削除し、関連する画像ファイルを削除します
        Storage::delete($event->image_path);
        $event->delete();

        $this->operationLogController->store('ID:' . $event->id . 'のイベントを削除しました');

        return redirect()->route('list.upcoming')->with('status', 'event-deleted');
    }

    /**
     * イベントの編集
     *
     * 指定されたイベントを編集するためのビューを表示します。
     * 現在のユーザーがイベントの作成者でない場合は編集できません。
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);

        // 現在のユーザーがイベントの作成者であるかをチェック
        if ($event->organizer_id !== Auth::id()) {
            return redirect()->route('event.detail', ['id' => $event->id])->with('status', 'unauthorized');
        }

        return view('event.edit', ['event' => $event]);
    }

    /**
     * イベントの更新
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントを更新します。
     * 現在のユーザーがイベントの作成者でない場合は更新できません。
     * 画像がアップロードされた場合は既存の画像を削除して新しい画像を保存します。
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // 現在のユーザーがイベントの作成者であるかをチェック
        if ($event->organizer_id !== Auth::id()) {
            return redirect()->route('event.detail', ['id' => $event->id])->with('status', 'unauthorized');
        }

        $validatedData = $request->validate([
            'name' => ['required', 'max:20'],
            'detail' => ['required', 'max:1000'],
            'category' => ['required', 'max:30'],
            'tag' => ['required', 'max:30'],
            'participation_condition' => ['required', 'max:100'],
            'external_link' => ['required', 'max:255', 'url'],
            'date' => ['required', 'date'],
            'deadline_date' => ['required', 'date'],
            'place' => ['required', 'max:50'],
            'number_of_recruits' => ['required', 'integer', 'min:1'],
            'image_path'  => ['nullable', 'max:5000', 'mimes:jpg,jpeg,png,gif'],
        ]);

        if ($request->hasFile('image_path')) {
            // 新しい画像がアップロードされた場合、既存の画像を削除して新しい画像を保存
            Storage::delete($event->image_path);
            $validatedData['image_path'] = $request->file('image_path')->store('public/events');
        }

        $event->update($validatedData);

        $this->operationLogController->store('ID:' . $event->id . 'のイベントを更新しました');


        return redirect()->route('event.detail', ['id' => $event->id])->with('status', 'event-updated');
    }
}
