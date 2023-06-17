<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class CollegeController extends Controller
{
    public function index()
    {
        $colleges = DB::select('select * from colleges');
        return view('develop-test', ['colleges' => $colleges]);
    }
}
