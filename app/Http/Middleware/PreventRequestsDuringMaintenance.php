<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

/**
 * メンテナンスモード中のリクエスト防止ミドルウェアクラス
 *
 * このクラスは、メンテナンスモード中のリクエスト防止に関するミドルウェア処理を行います。
 */
class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * メンテナンスモード時にアクセスが許可されるURIの配列
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
