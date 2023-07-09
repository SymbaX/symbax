<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * 認証済みリダイレクトミドルウェアクラス
 *
 * このクラスは、認証済みの場合にリダイレクトするミドルウェア処理を行います。
 */
class RedirectIfAuthenticated
{
    /**
     * リクエストの処理を行う
     *
     * @param Request $request リクエスト
     * @param Closure $next 次の処理を行うクロージャ
     * @param string[] $guards ガードの配列
     * @return Response レスポンス
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
