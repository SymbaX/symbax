<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

/**
 * アプリケーションの作成トレイト
 *
 * Laravelアプリケーションのインスタンスを作成します。
 * テストクラスで使用され、`createApplication`メソッドがアプリケーションのインスタンスを生成します。
 * Laravelフレームワークのコア機能が正しく動作するために必要な初期化を行います。
 */
trait CreatesApplication
{
    /**
     * アプリケーションを作成します。
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication(): Application
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
