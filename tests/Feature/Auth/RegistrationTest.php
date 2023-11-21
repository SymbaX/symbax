<?php

namespace Tests\Feature\Auth;

use App\Models\College;
use App\Models\Department;
use App\Models\User;
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

    /**
     * すでに存在するログインIDで登録できないことをテストします
     *
     * @return void
     */
    public function test_すでに存在するログインIDで登録できないことをテストします(): void
    {
        // 既存のユーザーをファクトリを使用してデータベースに作成
        User::factory()->create(['login_id' => 'existing_user']);

        // 既存のログインIDを使用して新しいユーザーを登録しようとする
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@g.neec.ac.jp',
            'password' => 'password',
            'password_confirmation' => 'password',
            'college' => 'it',
            'department' => 'specialist',
            'login_id' => 'existing_user', // 既存のログインIDを使用
            'terms' => true,
            'privacy_policy' => true,
        ]);

        // 登録が失敗し、エラーがログインIDに関するものであることを確認
        $this->assertGuest();
        $response->assertSessionHasErrors(['login_id']);
    }

    /**
     * ユーザー登録時のパスワード確認不一致をテストします。
     *
     * @return void
     */
    public function test_パスワード確認が一致しない場合に登録できないことをテストします(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@g.neec.ac.jp',
            'password' => 'password',
            'password_confirmation' => 'wrong_password', // 不一致なパスワード
            'college' => 'it',
            'department' => 'specialist',
            'login_id' => 'test',
            'terms' => true,
            'privacy_policy' => true,
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['password']);
    }

    /**
     * 利用規約とプライバシーポリシーに同意しない場合に登録できないことをテストします。
     *
     * @return void
     */
    public function test_利用規約とプライバシーポリシーに同意しない場合に登録できないことをテストします(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@g.neec.ac.jp',
            'password' => 'password',
            'password_confirmation' => 'password',
            'college' => 'it',
            'department' => 'specialist',
            'login_id' => 'test',
            'terms' => false, // 利用規約に同意しない
            'privacy_policy' => false, // プライバシーポリシーに同意しない
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['terms', 'privacy_policy']);
    }

    /**
     * 利用規約またはプライバシーポリシーに同意しない場合に登録できないことをテストします。
     *
     * @return void
     */
    public function test_利用規約またはプライバシーポリシーに同意しない場合に登録できないことをテストします(): void
    {
        // 利用規約に同意し、プライバシーポリシーには同意しない
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@g.neec.ac.jp',
            'password' => 'password',
            'password_confirmation' => 'password',
            'college' => 'it',
            'department' => 'specialist',
            'login_id' => 'test',
            'terms' => true, // 利用規約に同意
            'privacy_policy' => false, // プライバシーポリシーに同意しない
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['privacy_policy']);

        // 利用規約には同意せず、プライバシーポリシーに同意
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@g.neec.ac.jp',
            'password' => 'password',
            'password_confirmation' => 'password',
            'college' => 'it',
            'department' => 'specialist',
            'login_id' => 'test',
            'terms' => false, // 利用規約に同意しない
            'privacy_policy' => true, // プライバシーポリシーに同意
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['terms']);
    }


    /**
     * 特定の college に紐づく department を使用して新しいユーザーが登録できることをテストします。
     *
     * @return void
     */
    public function test_特定のcollegeに紐づくdepartmentを使用して新しいユーザーが登録できることをテストします(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@g.neec.ac.jp',
            'password' => 'password',
            'password_confirmation' => 'password',
            'college' => 'music',
            'department' => 'dance', // 特定の college に紐づく department を指定
            'login_id' => 'test',
            'terms' => true,
            'privacy_policy' => true,
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    /**
     * 特定の college に含まれていない department を使用して新しいユーザーが登録できないことをテストします。
     *
     * @return void
     */
    public function test_特定のcollegeに含まれていないdepartmentを使用して新しいユーザーが登録できないことをテストします(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@g.neec.ac.jp',
            'password' => 'password',
            'password_confirmation' => 'password',
            'college' => 'creators',
            'department' => 'design', // 特定の college に含まれていない department を指定
            'login_id' => 'test',
            'terms' => true,
            'privacy_policy' => true,
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors(['department']);
    }
}
