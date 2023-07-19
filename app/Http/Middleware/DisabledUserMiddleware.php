<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * DisabledUserMiddlewareクラス
 *
 * このクラスは、無効なユーザー向けのミドルウェア処理を行います。
 */
class DisabledUserMiddleware
{
    /**
     * リクエストの処理を行う
     *
     * @param Closure $next 次の処理を行うクロージャ
     * @return Response レスポンス
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->role_id === 'disabled') {
            return response()->view('errors.user_disabled_page', [], 403);
        }

        // BANされていない場合は処理を続行
        return $next($request);
    }
}
