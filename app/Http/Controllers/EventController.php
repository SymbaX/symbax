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
            'product_image'  => 'required',
        ]);

        $productImage = $request->product_image;
        if ($productImage) {

            //一意のファイル名を自動生成しつつ保存し、かつファイルパス（$productImagePath）を生成
            //ここでstore()メソッドを使っているが、これは画像データをstorageに保存している
            $productImagePath = $productImage->store('public/uploads');
        } else {
            $productImagePath = "";
        }

        $validatedData['product_image'] = $productImagePath;
        

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
        return view('event.details', ['event' => $event]);
    }
}
