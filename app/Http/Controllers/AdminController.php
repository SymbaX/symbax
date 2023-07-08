<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $users = User::where('role', 'admin')->get();

        return view('auth.admin.dashboard', compact('users'));
    }
}
