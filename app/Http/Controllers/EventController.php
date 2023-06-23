<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required','max:20',
            'details' => 'required','max:1000',
            'category' => 'required','max:30',
            'tag' => 'required','max:30',
            'conditions_of_participation' => 'required','max:100',
            'extarnal_links' => 'required','max:40','url',
            'datetime' => 'required','max:20','date',
            'place' => 'required','max:50',
            'number_of_people' => 'required','max:30','int',
        ]);

        Event::create($validatedData);

        return redirect()->back()->with('status', 'event-create');
    }
}
