<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

/**
 * 文字列トリムミドルウェアクラス
 *
 * このクラスは、指定された属性をトリムしない文字列トリムのミドルウェア処理を行います。
 */
class TrimStrings extends Middleware
{
    /**
     * トリムを除外する属性の名前の配列
     *
     * @var array<int, string>
     */
    protected $except = [
        'current_password',
        'password',
        'password_confirmation',
    ];
}
