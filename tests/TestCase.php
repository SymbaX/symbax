<?php

namespace Tests;

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
}
