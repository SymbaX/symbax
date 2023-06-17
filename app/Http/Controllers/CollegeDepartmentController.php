<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\Department;

class CollegeDepartmentController extends Controller
{
    public function index()
    {
        $colleges = College::all();
        $departments = Department::all();
        return view('develop-test', ['colleges' => $colleges, 'departments' => $departments]);
    }
}
