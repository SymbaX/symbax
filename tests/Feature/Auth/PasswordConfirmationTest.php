<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * パスワード確認テストクラス
 *
 * パスワードの確認に関するテストを行います。
 */
class PasswordConfirmationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * パスワード確認画面が表示されることをテストします。
     *
     * @return void
     */
    public function test_パスワード確認画面が表示されることをテストします(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/confirm-password');

        $response->assertStatus(200);
    }

    /**
     * パスワードが確認されることをテストします。
     *
     * @return void
     */
    public function test_パスワードが確認されることをテストします(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    /**
     * 無効なパスワードの場合、パスワードが確認されないことをテストします。
     *
     * @return void
     */
    public function test_無効なパスワードの場合、パスワードが確認されないことをテストします(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
    }
}
