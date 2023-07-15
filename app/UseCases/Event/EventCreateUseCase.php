<?php

namespace App\UseCases\Event;

use App\Http\Requests\Event\CreateRequest;
use App\Models\Event;
use App\Models\OperationLog;
use Illuminate\Support\Facades\Auth;

class EventCreateUseCase
{
    /**
     * イベント作成フォームの表示
     *
     * イベント作成フォームを表示します。
     *
     * @return \Illuminate\View\View
     */
    public function createView()
    {
        return view('event.create');
    }


    /**
     * イベントの作成
     *
     * リクエストから受け取ったデータを検証し、新しいイベントを作成します。
     *
     * @param  CreateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(CreateRequest $request)
    {
        $validatedData = $request->validated();

        $imagePath = $this->storeEventImage($request);
        $organizerId = Auth::id();

        $event = Event::create([
            'name' => $validatedData['name'],
            'detail' => $validatedData['detail'],
            'category' => $validatedData['category'],
            'tag' => $validatedData['tag'],
            'participation_condition' => $validatedData['participation_condition'],
            'external_link' => $validatedData['external_link'],
            'date' => $validatedData['date'],
            'deadline_date' => $validatedData['deadline_date'],
            'place' => $validatedData['place'],
            'number_of_recruits' => $validatedData['number_of_recruits'],
            'image_path' => $imagePath,
            'organizer_id' => $organizerId,
        ]);

        $this->createEventCreationLog($event->id, $organizerId);

        return redirect()->back()->with('status', 'event-create');
    }

    /**
     * イベントの画像を保存
     *
     * リクエストから受け取った画像を保存します。
     *
     * @param  CreateRequest  $request
     * @return string
     */
    private function storeEventImage(CreateRequest $request)
    {
        return $request->file('image_path')->store('public/events');
    }

    /**
     * イベント作成の操作ログを作成
     *
     * イベント作成の操作ログを作成します。
     *
     * @param  int  $eventId
     * @param  int  $organizerId
     * @return void
     */
    private function createEventCreationLog(int $eventId, int $organizerId)
    {
        OperationLog::create([
            'user_id' => $organizerId,
            'action' => 'イベントを作成しました ID:' . $eventId,
        ]);
    }
}
