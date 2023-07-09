<?php

namespace Tests;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * テストケースクラス
 *
 * テストクラスのベースとなる抽象クラスです。
 * Laravelのベーステストケースクラス（`TestCase`）を拡張し、`CreatesApplication`トレイトを使用してアプリケーションのインスタンスを作成します。
 * このクラスは、テストクラス内で共通のセットアップやヘルパーメソッドを提供するために使用されます。
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }
}
