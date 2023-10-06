<?php

namespace App\UseCases\Event;

use App\Http\Requests\Event\UpdateRequest;
use App\Models\Event;
use App\Models\EventCategories;
use App\Services\CheckEventOrganizerService;
use Illuminate\Support\Facades\Storage;
use App\UseCases\OperationLog\OperationLogUseCase;
use Intervention\Image\Facades\Image;

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
        if ($request->hasFile('image_path')) {
            // 画像がアップロードされた場合
            Storage::delete($event->image_path);
            $validatedData['image_path'] = $request->file('image_path')->store('public/events');
        } else {
            // 画像がアップロードされなかった場合は、元の画像パスを使用
            $validatedData['image_path'] = $event->image_path;
        }

        $originalEvent = Event::findOrFail($id); // 原始的なイベントデータを取得

        $event->update($validatedData);


        // OGPを生成
        $file_name = 'base.png';
        $path = storage_path('app/public/event-titles/' . $file_name);
        $img = Image::make($path);

        // 画像にテキストを入れる。
        $img->text(preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $validatedData['name']), 60, 220, function ($font) { // 絵文字を除去する
            $font->file(public_path('fonts/NotoSansJP-SemiBold.ttf'));
            $font->size(54);
            $font->color("#FFF");
            $font->align("left");
            $font->valign("top");
        });

        // OGP画像を保存
        $save_path = storage_path('app/public/event-titles/ogp_' . $event->id . '.png');
        $img->save($save_path);

        // --- ログを記録 ここから ---
        $detail = "";
        $fields = [
            'name', 'category', 'tag', 'participation_condition',
            'external_link', 'place',
            'number_of_recruits', 'image_path', 'organizer_id'
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
