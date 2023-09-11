<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;


class EventSearchController extends Controller
{
    public function indexSearch(Request $request)
    {

        $keyword = $request->input('keyword');

        $query = Event::query();

        if (!empty($keyword)) {
            $query->where('detail', 'LIKE', "%{$keyword}%");
        }

        $events = $query->paginate(12);

        return view('event.list-search', compact('events', 'keyword'));
    }
}
