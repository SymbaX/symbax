<?php

namespace Tests\Feature\Event;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventCreateTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * イベント作成フォームが正しく表示されることをテストします。
     *
     * @return void
     */
    public function test_イベント作成フォームが表示されることをテストします(): void
    {
        $response = $this->actingAs($this->user)->get('/event/create');


        $response->assertStatus(200);
    }

    /**
     * ログインしていないときにイベント作成フォームにアクセスすると、ログイン画面にリダイレクトされることをテストします。
     *
     * @return void
     */
    public function test_ログインしていないときにイベント作成フォームにアクセスすると、ログイン画面にリダイレクトされることをテストします(): void
    {
        $response = $this->get('/event/create');

        $response->assertRedirect('/login');
    }

    /**
     * イベントが正しく作成されることをテストします。
     *
     * @return void
     */
    public function test_イベントが正しく作成されることをテストします(): void
    {
        // ランダムな画像ファイルを生成
        $imageFile = UploadedFile::fake()->image('test_image.jpg');

        $requestData = [
            'name' => 'Test Event',
            'detail' => 'Test Event Details',
            'category' => '99_others',
            'date' => '2040-09-01',
            'deadline_date' => '2040-08-25',
            'place' => 'Test Place',
            'number_of_recruits' => 10,
            'image_path' => $imageFile,
            'organizer_id' => $this->user->id,
        ];

        $response = $this->actingAs($this->user)->patch('/event/store', $requestData);

        // ファイルが正しく保存されたことを確認
        Storage::disk()->assertExists('public/events/' . $imageFile->hashName());

        $this->assertDatabaseHas('events', [
            'name' => 'Test Event',
            'detail' => 'Test Event Details',
            'category' => '99_others',
            'date' => '2040-09-01',
            'deadline_date' => '2040-08-25',
            'place' => 'Test Place',
            'number_of_recruits' => 10,
            'image_path' => 'public/events/' . $imageFile->hashName(), // 修正
            'organizer_id' => $this->user->id,
        ]);

        // テスト後にファイルを削除
        Storage::disk()->delete('public/events/' . $imageFile->hashName());
    }

    /**
     * 過去の日付でイベントを作成できないことをテストします。
     *
     * @return void
     */
    public function test_過去の日付でイベントを作成できないことをテストします(): void
    {
        $requestData = [
            'name' => 'Test Event',
            'detail' => 'Test Event Details',
            'category' => '99_others',
            'date' => '2000-01-10', // 過去の日付
            'deadline_date' => '2000-01-01',
            'place' => 'Test Place',
            'number_of_recruits' => 10,
            'organizer_id' => $this->user->id,
        ];

        $response = $this->actingAs($this->user)->patch('/event/store', $requestData);

        $response->assertSessionHasErrors(['date']);
        $this->assertDatabaseMissing('events', [
            'name' => 'Test Event',
            'detail' => 'Test Event Details',
            'category' => '99_others',
            'date' => '2000-01-10',
            'deadline_date' => '2000-01-01',
            'place' => 'Test Place',
            'number_of_recruits' => 10,
            'organizer_id' => $this->user->id,
        ]);
    }
}
