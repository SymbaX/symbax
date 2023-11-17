<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\College;
use App\Models\Department;

/**
 * ユーザーファクトリクラス
 *
 * ユーザーモデルのファクトリクラスです。`Factory`クラスを拡張し、`User`モデルのデフォルトの状態やメソッドを定義します。
 */
class UserFactory extends Factory
{
    /**
     * モデルのデフォルトの状態を定義します。
     *
     * @return array<string,mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail('@g.neec.ac.jp'),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'college_id' => 'it',
            'department_id' => 'specialist',
            'login_id' => 'test',
        ];
    }

    /**
     * モデルのメールアドレスが未確認であることを示します。
     *
     * @return $this
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
