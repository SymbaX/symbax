<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategories;
use App\UseCases\Event\EventSearchUseCase;
use Illuminate\Http\Request;


class EventSearchController extends Controller
{
    private $eventSearchUseCase;

    public function __construct(EventSearchUseCase $eventSearchUseCase)
    {
        $this->eventSearchUseCase = $eventSearchUseCase;
    }

    public function indexSearch(Request $request)
    {
        $categories = EventCategories::all();
        $selectedCategoryId = $request->input('category');
        $keyword = $request->input('keyword');

        $events = $this->eventSearchUseCase->search($selectedCategoryId, $keyword);

        return view('event.list-search', compact('events', 'keyword', 'categories', 'selectedCategoryId'));
    }
}
