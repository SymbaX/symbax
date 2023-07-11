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


/**
 * イベントコントローラークラス
 * 
 * このクラスはイベントに関する処理を行うコントローラーです。
 */
class EventController extends Controller
{
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

        Event::create($validatedData);

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

        $participants = EventParticipant::where('event_id', $id);
        $participant_names = User::whereIn('id', $participants->pluck('user_id'))->pluck('name');

        $organizer_name = User::where('id', $event->organizer_id)->value('name');

        // 現在のユーザーがイベントの作成者であるかをチェック
        $is_organizer = $event->organizer_id === Auth::id();

        // 現在のユーザーがイベントに参加しているかをチェック
        $is_join = $event->organizer_id !== Auth::id() && !$participants->pluck('user_id')->contains(Auth::user()->id);

        return view('event.detail', [
            'event' => $event,
            'detail_markdown' => $detail_markdown,
            'participants' => $participants,
            'participant_names' => $participant_names,
            'is_organizer' => $is_organizer,
            'is_join' => $is_join,
            'organizer_name'  => $organizer_name
        ]);
    }



    /**
     * イベントへの参加
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントに参加します。
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function join(Request $request)
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
        ]);

        return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'joined-event');
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

        return redirect()->route('event.detail', ['id' => $event_id])->with('status', 'canceled-join');
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

        return redirect()->route('event.detail', ['id' => $event->id])->with('status', 'event-updated');
    }
}
