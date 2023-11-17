<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * 登録テストクラス
 *
 * 新規ユーザーの登録に関するテストを行います。
 */
class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 登録画面が表示されることをテストします。
     *
     * @return void
     */
    public function test_登録画面が表示されることをテストします(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    /**
     * 新しいユーザーが登録できることをテストします。
     *
     * @return void
     */
    public function test_新しいユーザーが登録できることをテストします(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@g.neec.ac.jp',
            'password' => 'password',
            'password_confirmation' => 'password',
            'college' => 'it',
            'department' => 'specialist',
            'login_id' => 'test',
            'terms' => true,
            'privacy_policy' => true,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    /**
     * 学校ドメイン以外で登録できないことをテストします。
     *
     * @return void
     */
    public function test_学校ドメイン以外で登録できないことをテストします(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'college' => 'it',
            'department' => 'specialist',
            'login_id' => 'test',
            'terms' => true,
            'privacy_policy' => true,
        ]);

        $this->assertGuest(); // ユーザーが認証されていないことを確認
        $response->assertSessionHasErrors(['email']); // メールアドレスに関するエラーがあることを確認
    }
}
