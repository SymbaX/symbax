<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

/**
 * 認証ミドルウェアクラス
 *
 * このクラスは、認証に関連するミドルウェア処理を行います。
 */
class Authenticate extends Middleware
{
    /**
     * 認証されていないユーザーがアクセスした場合のリダイレクト先パスを取得する
     *
     * @param Request $request リクエスト
     * @return string|null リダイレクト先のパス
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}
