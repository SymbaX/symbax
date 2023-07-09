<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * プロファイルテストクラス
 *
 * ユーザープロファイルに関するテストを行います。
 */
class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * プロファイルページが表示されることをテストします。
     *
     * @return void
     */
    public function test_プロファイルページが表示されることをテストします(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    /**
     * プロファイル情報が更新されることをテストします。
     *
     * @return void
     */
    public function test_プロファイル情報が更新されることをテストします(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    /**
     * メールアドレスが変更されない場合、メールアドレスの検証ステータスが変更されないことをテストします。
     *
     * @return void
     */
    public function test_メールアドレスが変更されない場合、メールアドレスの検証ステータスが変更されないことをテストします(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    /**
     * ユーザーがアカウントを削除できることをテストします。
     *
     * @return void
     */
    public function test_ユーザーがアカウントを削除できることをテストします(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    /**
     * アカウントを削除するために正しいパスワードを提供する必要があることをテストします。
     *
     * @return void
     */
    public function test_アカウントを削除するために正しいパスワードを提供する必要があることをテストします(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
