<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\CreateRequest;
use App\UseCases\Event\EventCreateUseCase;

class EventCreateController extends Controller
{
    /**
     * イベント作成フォームの表示
     *
     * イベント作成フォームを表示します。
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $createEventUseCase = new EventCreateUseCase();
        return $createEventUseCase->create();
    }

    /**
     * イベントの作成
     *
     * リクエストから受け取ったデータを検証し、新しいイベントを作成します。
     *
     * @param  CreateRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateRequest $request)
    {
        $createEventUseCase = new EventCreateUseCase();
        return $createEventUseCase->create($request);
    }
}
