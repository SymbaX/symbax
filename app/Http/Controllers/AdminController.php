<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\College;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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
        $roles = Role::all();

        return view('admin.users-list', [
            'users' => $users,
            'colleges' => $colleges,
            'departments' => $departments,
            'roles' => $roles,

        ]);
    }

    public function userUpdate(Request $request, User $user): RedirectResponse
    {
        // バリデーション
        $validatedData = $request->validate([
            'college' => 'required',
            'department' => 'required',
            'role' => 'required',
        ]);

        // College IDとDepartment IDを更新
        $user->college_id = $validatedData['college'];
        $user->department_id = $validatedData['department'];

        // ロールを更新
        $user->role = $validatedData['role'];

        // ユーザーの変更を保存
        $user->save();

        return Redirect::route('admin.users')->with('status', 'user-updated');
    }
}
