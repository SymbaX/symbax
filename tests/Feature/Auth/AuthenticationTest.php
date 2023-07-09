<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * 認証テストクラス
 *
 * ユーザーの認証に関するテストを行います。
 */
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログイン画面が表示されることをテストします。
     *
     * @return void
     */
    public function test_ログイン画面が表示されることをテストします(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * ユーザーがログインできることをテストします。
     *
     * @return void
     */
    public function test_ユーザーがログインできることをテストします(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    /**
     * パスワードが無効な場合、ユーザーが認証できないことをテストします。
     *
     * @return void
     */
    public function test_パスワードが無効な場合、ユーザーが認証できないことをテストします(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }
}
