<?php

namespace App\UseCases\Event;

use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventCommunityUseCase
{
    protected $checkParticipantStatus;
    protected $checkEventOrganizer;

    public function __construct(
        CheckEventParticipantStatusUseCase $checkParticipantStatus,
        CheckEventOrganizerUseCase $checkEventOrganizer
    ) {
        $this->checkParticipantStatus = $checkParticipantStatus;
        $this->checkEventOrganizer = $checkEventOrganizer;
    }

    public function checkAccess($id): bool
    {
        $isParticipantApproved = $this->checkParticipantStatus->execute($id);
        $isEventOrganizer = $this->checkEventOrganizer->execute($id);

        if ($isParticipantApproved === "approved" || $isEventOrganizer) {
            return true;
        }

        return false;
    }

    public function getTopics($id)
    {
        return Topic::where("event_id", $id)->latest()->get();
    }

    public function saveTopic(Request $request)
    {
        $topic = new Topic();

        if ($request->content) {
            $topic->user_id = Auth::id();
            $topic->event_id = $request->event_id;
            $topic->content = $request->content;
            $topic->save();
        }

        return $topic;
    }
}
