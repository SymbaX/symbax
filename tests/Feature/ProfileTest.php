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
            ->get('/profile/edit');

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
            ->patch('/profile/update', [
                'name' => 'Test User',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile/edit');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
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
            ->delete('/profile/delete', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        // ユーザーアカウントがSoft Deleteされたことを確認
        $this->assertSoftDeleted('users', ['id' => $user->id]);

        // ログインしているか確認
        $this->assertGuest();

        // ユーザーアカウントが削除されていることを確認
        $this->assertNull(User::find($user->id));
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
            ->delete('/profile/delete', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertRedirect('/profile');

        // ユーザーアカウントが削除されていないことを確認
        $this->assertDatabaseHas('users', ['id' => $user->id, 'deleted_at' => null]);
    }
}
