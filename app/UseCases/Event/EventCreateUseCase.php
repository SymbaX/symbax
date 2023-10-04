<?php

namespace App\UseCases\Event;

use App\Http\Requests\Event\CreateRequest;
use App\Models\Event;
use App\Models\EventCategories;
use App\Models\OperationLog;
use App\UseCases\OperationLog\OperationLogUseCase;
use Illuminate\Support\Facades\Auth;

/**
 * イベント作成ユースケース
 *
 * イベントの作成に関するビジネスロジックを提供するユースケースクラスです。
 */
class EventCreateUseCase
{
    /**
     * 操作ログを保存するためのビジネスロジックを提供するユースケース
     * このユースケースを利用して、システムの操作に関するログの記録処理を行います。
     *
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * EventCreateUseCaseのコンストラクタ
     *
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param OperationLogUseCase $operationLogUseCase 操作ログに関するユースケース
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * イベント作成フォームの表示
     *
     * イベント作成フォームを表示します。
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // イベントカテゴリーの一覧を取得
        $categories = EventCategories::all();
        $selectedCategoryId = old('category', null);

        return view('event.create', [
            'categories' => $categories,
            'selectedCategoryId' => $selectedCategoryId
        ]);
    }

    /**
     * イベントの作成
     *
     * リクエストから受け取ったデータを検証し、新しいイベントを作成します。
     *
     * @param  CreateRequest  $request
     * @return int イベントのID
     */
    public function store(CreateRequest $request): int
    {
        $validatedData = $request->validated();

        // 画像を保存
        $imagePath_a = $this->storeEventImageA($request);
        $imagePath_b = $this->storeEventImageB($request);
        $imagePath_c = $this->storeEventImageC($request);
        $imagePath_d = $this->storeEventImageD($request);
        $imagePath_e = $this->storeEventImageE($request);

        // イベント作成者のIDを取得(現在ログイン中のユーザーID)
        $organizerId = Auth::id();

        // イベントを作成
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
            'image_path_a' => $imagePath_a,
            'image_path_b' => $imagePath_b,
            'image_path_c' => $imagePath_c,
            'image_path_d' => $imagePath_d,
            'image_path_e' => $imagePath_e,
            'organizer_id' => $organizerId,
        ]);

        // 操作ログを保存
        $this->operationLogUseCase->store([
            'detail' => "name: {$event->name}\n" .
                "detail: \n\n{$event->detail}\n\n" .
                "category: {$event->category}\n" .
                "tag: {$event->tag}\n" .
                "participation_condition: {$event->participation_condition}\n" .
                "external_link: {$event->external_link}\n" .
                "date: {$event->date}\n" .
                "deadline_date: {$event->deadline_date}\n" .
                "place: {$event->place}\n" .
                "number_of_recruits: {$event->number_of_recruits}\n" .
                "image_path_a: {$event->image_path_a}\n" .
                "image_path_b: {$event->image_path_b}\n" .
                "image_path_c: {$event->image_path_c}\n" .
                "image_path_d: {$event->image_path_d}\n" .
                "image_path_e: {$event->image_path_e}\n" .
                "organizer_id: {$event->organizer_id}",
            'user_id' => null,
            'target_event_id' => $event->id,
            'target_user_id' => null,
            'target_topic_id' => null,
            'action' => 'event-create',
            'ip' => request()->ip(),
        ]);

        return $event->id;
    }

    /**
     * イベントの画像を保存
     *
     * リクエストから受け取った画像を保存します。
     *
     * @param  CreateRequest  $request
     * @return string
     */
    private function storeEventImageA(CreateRequest $request): string
    {
        return $request->file('image_path_a')->store('public/events');
    }

    private function storeEventImageB(CreateRequest|null $request=null): ?string
    {
        if ($request->hasFile('image_path_b')) {
            // 'image_path_b'がファイルとして送信された場合の処理
            return $request->file('image_path_b')->store('public/events');
        }
        else{
            return 'public/events/noimage.png';
        }
    }

    private function storeEventImageC(CreateRequest|null $request=null): ?string
    {
        if ($request->hasFile('image_path_c')) {
            // 'image_path_c'がファイルとして送信された場合の処理
            return $request->file('image_path_c')->store('public/events');
        }
        else{
            return 'public/events/noimage.png';
        }
    }

    private function storeEventImageD(CreateRequest|null $request=null): ?string
    {
        if ($request->hasFile('image_path_d')) {
            // 'image_path_d'がファイルとして送信された場合の処理
            return $request->file('image_path_d')->store('public/events');
        }
        else{
            return 'public/events/noimage.png';
        }
    }

    private function storeEventImageE(CreateRequest|null $request=null): ?string
    {
        if ($request->hasFile('image_path_e')) {
            // 'image_path_e'がファイルとして送信された場合の処理
            return $request->file('image_path_e')->store('public/events');
        }
        else{
            return 'public/events/noimage.png';
        }
    }
}