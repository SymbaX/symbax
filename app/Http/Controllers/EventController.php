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
            'name' => 'required',
            'details' => 'required',
            'category' => 'required',
            'tag' => 'required',
            'conditions_of_participation' => 'required',
            'extarnal_links' => 'required',
            'datetime' => 'required',
            'place' => 'required',
            'number_of_people' => 'required',
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
