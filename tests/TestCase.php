<?php

namespace Tests;

use App\Models\User;
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

    /**
     * テストケースのセットアップ処理を行います。
     *
     * 各テストメソッドの実行前に実行されます。
     * ベースクラスの`setUp`メソッドを呼び出し、データベースをリフレッシュします。
     * さらに、`DatabaseSeeder`を使用してデータベースをシーディングします。
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
    }
}
