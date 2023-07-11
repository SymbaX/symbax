<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Adminミドルウェアクラス
 *
 * このクラスは、管理者向けのミドルウェア処理を行います。
 */
class AdminMiddleware
{
    /**
     * リクエストの処理を行う
     *
     * @param Closure $next 次の処理を行うクロージャ
     * @return Response レスポンス
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role_id === 'admin') {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
