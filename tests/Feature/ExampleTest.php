<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Exampleテストクラス
 *
 * アプリケーションの基本的なテストを行います。
 */
class ExampleTest extends TestCase
{
    /**
     * アプリケーションが成功したレスポンスを返すことをテストします。
     *
     * @return void
     */
    public function test_アプリケーションが成功したレスポンスを返すことをテストします(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
