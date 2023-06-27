<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required','max:20'],
            'details' => ['required','max:1000'],
            'category' => ['required','max:30'],
            'tag' => ['required','max:30'],
            'conditions_of_participation' => ['required','max:100'],
            'extarnal_links' => ['required','max:255','url'],
            'datetime' => ['required','max:20','date'],
            'place' => ['required','max:50'],
            'number_of_people' => ['required','max:30','int'],
            'product_image'  => ['required', 'max:5000', 'mimes:jpg,jpeg,png,gif'],
        ]);

        $validatedData['product_image'] = $request->file('product_image')->store('public/events');

        $validatedData['creator_id'] = Auth::id();
      
        Event::create($validatedData);

        return redirect()->back()->with('status', 'event-create');
    }

    public function list()
    {
        $events = Event::paginate(12);
        return view('event.list', ['events' => $events]);
    }

    public function details($id)
    {
        $event = Event::findOrFail($id);
        $detail_markdown = Markdown::parse(e($event->details));


        return view('event.details', ['event' => $event, 'detail_markdown' => $detail_markdown]);
    }
}
