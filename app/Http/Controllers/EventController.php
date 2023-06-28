<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'max:20'],
            'details' => ['required', 'max:1000'],
            'category' => ['required', 'max:30'],
            'tag' => ['required', 'max:30'],
            'conditions_of_participation' => ['required', 'max:100'],
            'extarnal_links' => ['required', 'max:255', 'url'],
            'datetime' => ['required', 'max:20', 'date'],
            'place' => ['required', 'max:50'],
            'number_of_people' => ['required', 'max:30', 'int'],
            'product_image'  => ['required', 'max:5000', 'mimes:jpg,jpeg,png,gif'],
        ]);

        $validatedData['product_image'] = $request->file('product_image')->store('public/events');

        $validatedData['creator_id'] = Auth::id();

        Event::create($validatedData);

        return redirect()->back()->with('status', 'event-create');
    }

    public function list()
    {
        $events = Event::paginate(12);
        return view('event.list', ['events' => $events]);
    }

    public function details($id)
    {
        $event = Event::findOrFail($id);
        $detail_markdown = Markdown::parse(e($event->details));

        $participants = EventParticipant::where('event_id', $id);
        $participantNames = User::whereIn('id', EventParticipant::where('event_id', $id)->pluck('user_id'))->pluck('name');

        return view('event.details', ['event' => $event, 'detail_markdown' => $detail_markdown, 'participants' => $participants, 'participantNames' => $participantNames]);
    }

    public function join(Request $request)
    {
        $user_id = Auth::id();
        $event_id = $request->input('event_id');
        $event = Event::findOrFail($event_id);
        $participantCount = EventParticipant::where('event_id', $event_id)->count();

        // 自分が作成したイベントであればエラー
        if ($event->creator_id == $user_id) {
            return redirect()->route('details', ['id' => $event_id])->with('status', 'your-event-owner');
        }

        // 参加可能な枠がなければエラー
        if ($event->number_of_people <= $participantCount) {
            return redirect()->route('details', ['id' => $event_id])->with('status', 'no-participation-slots');
        }

        // ユーザーが既に参加している場合はエラー
        $alreadyJoined = EventParticipant::where('event_id', $event_id)->where('user_id', $user_id)->exists();
        if ($alreadyJoined) {
            return redirect()->route('details', ['id' => $event_id])->with('status', 'already-joined');
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
}
