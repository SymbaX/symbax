<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

/**
 * パスワードリセットテストクラス
 *
 * パスワードのリセットに関するテストを行います。
 */
class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    /**
     * パスワードリセットリンク画面が表示されることをテストします。
     *
     * @return void
     */
    public function test_パスワードリセットリンク画面が表示されることをテストします(): void
    {
        $response = $this->get('/forgot-password');

        $response->assertStatus(200);
    }

    /**
     * パスワードリセットリンクをリクエストできることをテストします。
     *
     * @return void
     */
    public function test_パスワードリセットリンクをリクエストできることをテストします(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    /**
     * パスワードリセット画面が表示されることをテストします。
     *
     * @return void
     */
    public function test_パスワードリセット画面が表示されることをテストします(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $response = $this->get('/reset-password/' . $notification->token);

            $response->assertStatus(200);

            return true;
        });
    }

    /**
     * 有効なトークンを使用してパスワードがリセットできることをテストします。
     *
     * @return void
     */
    public function test_有効なトークンを使用してパスワードがリセットできることをテストします(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors();

            return true;
        });
    }
}
