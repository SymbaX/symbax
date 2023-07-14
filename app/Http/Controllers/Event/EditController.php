<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Controllers\OperationLogController;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EditController extends Controller
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
