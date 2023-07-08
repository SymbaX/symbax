<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
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

    public function list()
    {
        $events = Event::whereDate('date', '>=', Carbon::today())
            ->orderBy('date', 'desc')
            ->paginate(12);
        return view('event.list', ['events' => $events]);
    }

    public function listAll()
    {
        $events = Event::orderBy('date', 'desc')
            ->paginate(12);
        return view('event.all', ['events' => $events]);
    }

    public function details($id)
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

        return view('event.details', [
            'event' => $event,
            'detail_markdown' => $detail_markdown,
            'participants' => $participants,
            'participant_names' => $participant_names,
            'is_organizer' => $is_organizer,
            'is_join' => $is_join,
            'organizer_name'  => $organizer_name
        ]);
    }


    public function join(Request $request)
    {
        $user_id = Auth::id();
        $event_id = $request->input('event_id');
        $event = Event::findOrFail($event_id);
        $participantCount = EventParticipant::where('event_id', $event_id)->count();

        // 自分が作成したイベントであればエラー
        if ($event->organizer_id == $user_id) {
            return redirect()->route('details', ['id' => $event_id])->with('status', 'your-event-owner');
        }

        // ユーザーが既に参加している場合はエラー
        $alreadyJoined = EventParticipant::where('event_id', $event_id)->where('user_id', $user_id)->exists();
        if ($alreadyJoined) {
            return redirect()->route('details', ['id' => $event_id])->with('status', 'already-joined');
        }

        // 参加可能な枠がなければエラー
        if ($event->number_of_recruits <= $participantCount) {
            return redirect()->route('details', ['id' => $event_id])->with('status', 'no-participation-slots');
        }

        $eventParticipant = EventParticipant::create([
            'user_id' => $user_id,
            'event_id' => $event_id,
        ]);

        return redirect()->route('details', ['id' => $event_id])->with('status', 'joined-event');
    }

    public function cancelJoin(Request $request)
    {
        $user_id = Auth::id();
        $event_id = $request->input('event_id');
        $event = Event::findOrFail($event_id);

        // ユーザーが参加しているか確認
        $participant = EventParticipant::where('event_id', $event_id)->where('user_id', $user_id)->first();

        // 参加していない場合はエラー
        if (!$participant) {
            return redirect()->route('details', ['id' => $event_id])->with('status', 'not-joined');
        }

        // 参加をキャンセル
        $participant->delete();

        return redirect()->route('details', ['id' => $event_id])->with('status', 'canceled-join');
    }

    public function delete($id)
    {
        $event = Event::findOrFail($id);

        // イベントに参加者が登録されているかどうかを確認します
        $participantCount = EventParticipant::where('event_id', $id)->count();
        if ($participantCount > 0) {
            return redirect()->route('details', ['id' => $id])->with('status', 'cannot-delete-with-participants');
        }

        // イベントを削除し、関連する商品画像ファイルを削除します
        Storage::delete($event->image_path);
        $event->delete();

        return redirect()->route('list')->with('status', 'event-deleted');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);

        // 現在のユーザーがイベントの作成者であるかをチェック
        if ($event->organizer_id !== Auth::id()) {
            return redirect()->route('details', ['id' => $event->id])->with('status', 'unauthorized');
        }

        return view('event.edit', ['event' => $event]);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        // 現在のユーザーがイベントの作成者であるかをチェック
        if ($event->organizer_id !== Auth::id()) {
            return redirect()->route('details', ['id' => $event->id])->with('status', 'unauthorized');
        }

        $validatedData = $request->validate([
            'name' => ['required', 'max:20'],
            'detail' => ['required', 'max:1000'],
            'category' => ['required', 'max:30'],
            'tag' => ['required', 'max:30'],
            'participation_condition' => ['required', 'max:100'],
            'external_link' => ['required', 'max:255', 'url'],
            'date' => ['required', 'date'],
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

        return redirect()->route('details', ['id' => $event->id])->with('status', 'event-updated');
    }
}
