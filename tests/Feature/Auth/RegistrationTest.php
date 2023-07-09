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
    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    /**
     * 新しいユーザーが登録できることをテストします。
     *
     * @return void
     */
    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@g.neec.ac.jp',
            'password' => 'password',
            'password_confirmation' => 'password',
            'college' => 'it',
            'department' => 'specialist',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
