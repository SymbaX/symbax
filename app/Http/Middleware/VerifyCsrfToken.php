<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

/**
 * CSRFトークン検証ミドルウェアクラス
 *
 * このクラスは、CSRFトークンの検証に関するミドルウェア処理を行います。
 */
class VerifyCsrfToken extends Middleware
{
    /**
     * CSRF検証を除外するURIの配列
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
