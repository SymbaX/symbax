<?php

namespace App\UseCases\Event;

use App\Http\Requests\Event\UpdateRequest;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventEditUseCase
{
    /**
     * イベントの編集
     *
     * 指定されたイベントを編集するためのビューを表示します。
     * 現在のユーザーがイベントの作成者でない場合は編集できません。
     *
     * @param  int  $id
     * @return Event|null
     */
    public function getEditableEvent($id): ?Event
    {
        $event = Event::findOrFail($id);

        if ($event->organizer_id !== Auth::id()) {
            return null;
        }

        return $event;
    }

    /**
     * イベントの更新
     *
     * リクエストから受け取ったデータを検証し、指定されたイベントを更新します。
     * 現在のユーザーがイベントの作成者でない場合は更新できません。
     * 画像がアップロードされた場合は既存の画像を削除して新しい画像を保存します。
     *
     * @param  int  $id
     * @param  UpdateRequest  $request
     * @return bool true if the event was successfully updated; otherwise, false.
     */
    public function updateEvent($id, UpdateRequest $request): bool
    {
        $event = $this->getEditableEvent($id);

        if (!$event) {
            return false;
        }

        $validatedData = $request->validated();

        if ($request->hasFile('image_path')) {
            Storage::delete($event->image_path);
            $validatedData['image_path'] = $request->file('image_path')->store('public/events');
        } else {
            $validatedData['image_path'] = $event->image_path; // 元の画像パスを使用
        }

        $event->update($validatedData);

        return true;
    }
}
