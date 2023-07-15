<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\CreateRequest;
use App\Http\Requests\Event\EventRequest;
use App\Models\Event;
use App\Models\OperationLog;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class CreateController extends Controller
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
     * @param  EventRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(CreateRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['image_path'] = $request->file('image_path')->store('public/events');
        $validatedData['organizer_id'] = Auth::id();

        $event = Event::create($validatedData);

        OperationLog::create([
            'user_id' => $validatedData['organizer_id'],
            'action' => 'イベントを作成しました ID:' . $event->id,
        ]);

        return redirect()->back()->with('status', 'event-create');
    }
}
