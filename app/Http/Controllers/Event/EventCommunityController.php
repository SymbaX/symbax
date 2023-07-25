<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\TopicRequest;
use App\UseCases\Event\EventCommunityUseCase;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventCommunityController extends Controller
{
    protected $useCase;

    public function __construct(EventCommunityUseCase $useCase)
    {
        $this->useCase = $useCase;
    }

    public function create(Request $request, $id): View
    {
        if (!$this->useCase->checkAccess($id)) {
            abort(403);
        }

        $topics = $this->useCase->getTopics($id);
        return view('event.community', ['event' => $id, 'topics' => $topics]);
    }

    public function save(TopicRequest $request)
    {
        $topic = $this->useCase->saveTopic($request);
        return redirect()->route('event.community', ['event_id' => $topic->event_id]);
    }
}
