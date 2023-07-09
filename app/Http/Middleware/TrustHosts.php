<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

/**
 * 信頼できるホストミドルウェアクラス
 *
 * このクラスは、信頼できるホストに関するミドルウェア処理を行います。
 */
class TrustHosts extends Middleware
{
    /**
     * 信頼できるホストのパターンを取得する
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
    {
        return [
            $this->allSubdomainsOfApplicationUrl(),
        ];
    }
}
