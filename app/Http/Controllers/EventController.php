<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

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
        ]);

        Event::create($validatedData);

        return redirect()->back()->with('status', 'event-create');
    }
}
