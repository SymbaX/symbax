<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

/**
 * Cookieの暗号化ミドルウェアクラス
 *
 * このクラスは、Cookieの暗号化に関するミドルウェア処理を行います。
 */
class EncryptCookies extends Middleware
{
    /**
     * 暗号化しないCookieの名前の配列
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
