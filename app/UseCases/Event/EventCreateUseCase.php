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
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * OperationLogUseCaseの新しいインスタンスを作成します。
     *
     * @param  OperationLogUseCase  $operationLogUseCase
     * @return void
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /**
     * イベント作成フォームの表示
     *
     * イベント作成フォームを表示します。
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
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

        $this->operationLogUseCase->store([
            'detail' => 'イベントを作成しました',
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
    private function storeEventImage(CreateRequest $request): string
    {
        return $request->file('image_path')->store('public/events');
    }
}
