<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OperationLogController;
use App\Models\Event;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreateController extends Controller
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
}
