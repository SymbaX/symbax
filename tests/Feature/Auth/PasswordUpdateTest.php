<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * パスワード更新テストクラス
 *
 * パスワードの更新に関するテストを行います。
 */
class PasswordUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * パスワードが更新されることをテストします。
     *
     * @return void
     */
    public function test_パスワードが更新されることをテストします(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertTrue(Hash::check('new-password', $user->refresh()->password));
    }

    /**
     * 正しいパスワードを提供してパスワードを更新するためのテストです。
     *
     * @return void
     */
    public function test_正しいパスワードを提供してパスワードを更新するためのテストです(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->put('/password', [
                'current_password' => 'wrong-password',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('updatePassword', 'current_password')
            ->assertRedirect('/profile');
    }
}
