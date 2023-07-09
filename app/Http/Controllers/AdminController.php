<?php

/**
 * 管理者コントローラークラス
 *
 * このファイルでは管理者ユーザーに関連するコントローラーを記載。
 * 
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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

        return view('auth.admin.dashboard', compact('users'));
    }
}
