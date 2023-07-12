<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\College;
use App\Models\Department;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use App\Http\Controllers\OperationLogController;
use App\Models\OperationLog;

/**
 * 管理者コントローラークラス
 * 
 * このクラスは管理者に関する処理を行うコントローラーです。
 */
class AdminController extends Controller
{
    /**
     * @var OperationLogController
     */
    private $operationLogController;

    /**
     * OperationLogControllerの新しいインスタンスを作成します。
     *
     * @param  OperationLogController  $operationLogController
     * @return void
     */
    public function __construct(OperationLogController $operationLogController)
    {
        $this->operationLogController = $operationLogController;
    }

    /**
     * 管理者ダッシュボード
     *
     * 管理者のダッシュボードを表示します。
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        $users = User::where('role_id', 'admin')->get();

        $this->operationLogController->store('● 管理者ダッシュボードを表示しました');

        return view('admin.dashboard', compact('users'));
    }

    /**
     * ユーザー一覧を表示します。
     *
     * @return \Illuminate\View\View
     */
    public function listUsers()
    {
        $users = User::paginate(10);

        $colleges = College::all();
        $departments = Department::all();
        $roles = Role::all();

        $this->operationLogController->store('● ユーザー一覧を表示しました');


        return view('admin.users-list', [
            'users' => $users,
            'colleges' => $colleges,
            'departments' => $departments,
            'roles' => $roles,

        ]);
    }

    /**
     * ユーザー情報を更新します。
     *
     * @param  Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function userUpdate(Request $request, User $user): RedirectResponse
    {
        // バリデーション
        $validatedData = $request->validate([
            'college' => ['required', 'exists:colleges,id'],
            'department' => [
                'required', 'exists:departments,id', Rule::exists('departments', 'id')->where(function ($query) use ($request) {
                    $query->where('college_id', $request->input('college'));
                })
            ],
            'role' => 'required',
        ]);

        // College IDとDepartment IDを更新
        $user->college_id = $validatedData['college'];
        $user->department_id = $validatedData['department'];

        // ロールを更新
        $user->role_id = $validatedData['role'];

        // ユーザーの変更を保存
        $user->save();

        $this->operationLogController->store('● ID:' . $user->id . 'のユーザー情報を更新しました', $user->id);

        return Redirect::route('admin.users')->with('status', 'user-updated');
    }

    /**
     * 操作ログ一覧を表示します。
     *
     * @return \Illuminate\View\View
     */
    public function listOperationLogs()
    {
        $operation_logs = OperationLog::latest('created_at')->paginate(100);
        $users = User::pluck('name', 'id');

        // $operation_logsの各操作ログのユーザーIDを利用して、名前に変換
        foreach ($operation_logs as $operation_log) {
            $operation_log->user_name = $users[$operation_log->user_id] ?? 'Unknown';
        }

        $this->operationLogController->store('● 操作ログを表示しました');

        return view('admin.operation-logs', compact('operation_logs'));
    }
}
