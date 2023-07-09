<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ValidateSignature as Middleware;

/**
 * シグネチャ検証ミドルウェアクラス
 *
 * このクラスは、シグネチャの検証に関するミドルウェア処理を行います。
 */
class ValidateSignature extends Middleware
{
    /**
     * 無視するクエリ文字列パラメータの名前の配列
     *
     * @var array<int, string>
     */
    protected $except = [
        // 'fbclid',
        // 'utm_campaign',
        // 'utm_content',
        // 'utm_medium',
        // 'utm_source',
        // 'utm_term',
    ];
}
