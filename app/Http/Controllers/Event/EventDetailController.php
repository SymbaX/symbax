<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\UseCases\Event\EventDetailUseCase;
use Illuminate\Http\RedirectResponse;

class EventDetailController extends Controller
{
    /**
     * @var EventDetailUseCase
     */
    private $eventDetailUseCase;

    /**
     * EventDetailUseCaseの新しいインスタンスを作成します。
     *
     * @param  EventDetailUseCase  $eventDetailUseCase
     * @return void
     */
    public function __construct(EventDetailUseCase $eventDetailUseCase)
    {
        $this->eventDetailUseCase = $eventDetailUseCase;
    }

    /**
     * イベントの詳細表示
     *
     * 指定されたイベントの詳細情報を表示します。
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function detail($id)
    {
        $eventDetail = $this->eventDetailUseCase->getEventDetail($id);

        return view('event.detail', $eventDetail);
    }
}
