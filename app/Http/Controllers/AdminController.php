<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\College;
use App\Models\Department;

/**
 * 管理者コントローラークラス
 * 
 * このクラスは管理者に関する処理を行うコントローラーです。
 */
class AdminController extends Controller
{
    /**
     * 管理者ダッシュボード
     *
     * 管理者のダッシュボードを表示します。
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $users = User::where('role', 'admin')->get();

        return view('admin.dashboard', compact('users'));
    }

    public function listUsers()
    {
        $users = User::paginate(10);

        $colleges = College::all();
        $departments = Department::all();

        return view('admin.users-list', [
            'users' => $users,
            'colleges' => $colleges,
            'departments' => $departments,
        ]);
    }
}
