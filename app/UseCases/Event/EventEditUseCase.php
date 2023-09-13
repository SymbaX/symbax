<?php

namespace App\UseCases\Event;

use App\Http\Requests\Event\UpdateRequest;
use App\Models\Event;
use App\Models\EventCategories;
use App\Services\CheckEventOrganizerService;
use Illuminate\Support\Facades\Storage;
use App\UseCases\OperationLog\OperationLogUseCase;

/**
 * イベント編集ユースケース
 *
 * イベントの編集に関するユースケースを提供するクラスです。
 */
class EventEditUseCase
{
    /**
     * 操作ログを保存するためのビジネスロジックを提供するユースケース
     *
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * イベントオーガナイザーを確認するためのサービス
     *
     * @var CheckEventOrganizerService
     */
    private $checkEventOrganizerService;

    /**
     * EventEditUseCaseのコンストラクタ
     *
     * 使用するユースケースとサービスをインジェクション（注入）します。
     *
     * @param OperationLogUseCase $operationLogUseCase 操作ログに関するユースケース
     * @param CheckEventOrganizerService $checkEventOrganizerService イベントオーガナイザーを確認するためのサービス
     */
    public function __construct(OperationLogUseCase $operationLogUseCase, CheckEventOrganizerService $checkEventOrganizerService)
    {
        $this->operationLogUseCase = $operationLogUseCase;
        $this->checkEventOrganizerService = $checkEventOrganizerService;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * 指定されたイベントIDに基づいて、イベント情報を取得し、表示します。
     *
     * @param int $id イベントID
     * @return Event|null 編集可能なイベントを返します。イベントが存在しない場合はnullを返します。
     */
    public function edit($id)
    {
        $categories = EventCategories::all();
        $event = Event::where('id', $id)->where('is_deleted', false)->firstOrFail();

        if (!$this->checkEventOrganizerService->check($id)) {    // イベント作成者ではない場合
            return null;
        }

        // セッションにイベントIDとトークンを保存
        session()->put('edit_event_id', $id);
        session()->put('edit_token', str()->uuid()->toString());

        return view('event.edit', [
            'event' => $event,
            'categories' => $categories,
        ]);
    }


    /**
     * イベントの更新を行います。
     *
     * 指定されたイベントIDに基づいて、イベントのデータをリクエストから受け取ったデータで更新します。
     * 現在のユーザーがイベントの作成者でない場合、または更新に失敗した場合はfalseを返します。
     * 画像がアップロードされた場合は、既存の画像を削除して新しい画像を保存します。
     *
     * @param int $id イベントID
     * @param UpdateRequest $request 更新リクエスト
     * @return bool 更新が成功した場合はtrue、それ以外の場合はfalse
     */
    public function updateEvent($id, UpdateRequest $request): bool
    {
        if (!$this->checkEventOrganizerService->check($id)) {    // イベント作成者ではない場合
            return false;
        }

        // セッションに保存されたイベントIDとトークンを取得し、リクエストの値と比較
        $editToken = $request->input('edit_token');
        if ($editToken !== session('edit_token') || $id !== session('edit_event_id')) {
            abort(419);
        }

        $event = Event::where('id', $id)->where('is_deleted', false)->firstOrFail();

        $validatedData = $request->validated();

        // 画像がアップロードされた場合は、既存の画像を削除して新しい画像を保存
        if ($request->hasFile('image_path_a')) {
            // 画像がアップロードされた場合
            Storage::delete($event->image_path_a);
            $validatedData['image_path_a'] = $request->file('image_path_a')->store('public/events');
        } else {
            // 画像がアップロードされなかった場合は、元の画像パスを使用
            $validatedData['image_path_a'] = $event->image_path_a;
        }

        // NULLで、画像がアップロードされた場合は、そのまま画像を保存
        if (is_null($event->image_path_b) && $request->hasFile('image_path_b')){
            $validatedData['image_path_b'] = $request->file('image_path_b')->store('public/events');
        }
        // NULLではなくて、画像がアップロードされた場合は、既存の画像を削除して新しい画像を保存
        elseif ($request->hasFile('image_path_b')) {
            // 画像がアップロードされた場合
            Storage::delete($event->image_path_b);
            $validatedData['image_path_b'] = $request->file('image_path_b')->store('public/events');
        } else {
            // 画像がアップロードされなかった場合は、元の画像パスを使用
            $validatedData['image_path_b'] = $event->image_path_b;
        }

        // NULLで、画像がアップロードされた場合は、そのまま画像を保存
        if (is_null($event->image_path_c) && $request->hasFile('image_path_c')){
            $validatedData['image_path_c'] = $request->file('image_path_c')->store('public/events');
        }
        // NULLではなくて、画像がアップロードされた場合は、既存の画像を削除して新しい画像を保存
        elseif ($request->hasFile('image_path_c')) {
            // 画像がアップロードされた場合
            Storage::delete($event->image_path_c);
            $validatedData['image_path_c'] = $request->file('image_path_c')->store('public/events');
        } else {
            // 画像がアップロードされなかった場合は、元の画像パスを使用
            $validatedData['image_path_c'] = $event->image_path_c;
        }

        // NULLで、画像がアップロードされた場合は、そのまま画像を保存
        if (is_null($event->image_path_d) && $request->hasFile('image_path_d')){
            $validatedData['image_path_d'] = $request->file('image_path_d')->store('public/events');
        }
        // NULLではなくて、画像がアップロードされた場合は、既存の画像を削除して新しい画像を保存
        elseif ($request->hasFile('image_path_d')) {
            // 画像がアップロードされた場合
            Storage::delete($event->image_path_d);
            $validatedData['image_path_d'] = $request->file('image_path_d')->store('public/events');
        } else {
            // 画像がアップロードされなかった場合は、元の画像パスを使用
            $validatedData['image_path_d'] = $event->image_path_d;
        }

        // NULLで、画像がアップロードされた場合は、そのまま画像を保存
        if (is_null($event->image_path_e) && $request->hasFile('image_path_e')){
            $validatedData['image_path_e'] = $request->file('image_path_e')->store('public/events');
        }
        // NULLではなくて、画像がアップロードされた場合は、既存の画像を削除して新しい画像を保存
        elseif ($request->hasFile('image_path_e')) {
            // 画像がアップロードされた場合
            Storage::delete($event->image_path_e);
            $validatedData['image_path_e'] = $request->file('image_path_e')->store('public/events');
        } else {
            // 画像がアップロードされなかった場合は、元の画像パスを使用
            $validatedData['image_path_e'] = $event->image_path_e;
        }

        // 画像削除チェックボックスがtrueなら画像を削除
        if ($event->img_delete_b == true){
            Storage::delete($event->image_path_b);
        }
        if ($event->img_delete_c == true){
            Storage::delete($event->image_path_c);
        }
        if ($event->img_delete_d == true){
            Storage::delete($event->image_path_d);
        }
        if ($event->img_delete_e == true){
            Storage::delete($event->image_path_e);
        }

        $originalEvent = Event::findOrFail($id); // 原始的なイベントデータを取得

        // $event->update($validatedData);

        // --- ログを記録 ここから ---
        $detail = "";
        $fields = [
            'name', 'category', 'tag', 'participation_condition',
            'external_link', 'place',
            'number_of_recruits',
            'image_path_a', 'image_path_b', 'image_path_c', 'image_path_d', 'image_path_e',
            'organizer_id'
        ];

        foreach ($fields as $field) {
            $originalValue = $originalEvent->$field;
            $updatedValue = $event->$field;
            if ($originalValue != $updatedValue) {
                $detail .= "▼ {$field}: {$originalValue} ▶ {$updatedValue}\n";
            }
        }

        if ($originalEvent->detail != $event->detail) {
            $detail .= "----- detail -----\n" .
                "▼ original:\n{$originalEvent->detail}\n\n---- ▼ ----\n" .
                "▼ new:\n{$event->detail}\n\n" .
                "------------------------\n";
        }

        $this->operationLogUseCase->store([
            'detail' => $detail,
            'user_id' => null,
            'target_event_id' => $event->id,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'event-update',
            'ip' => request()->ip(),
        ]);
        // --- ログを記録 ここまで ---


        return true; // 更新成功
    }
}
