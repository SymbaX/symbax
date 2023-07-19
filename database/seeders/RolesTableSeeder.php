<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * RolesTableSeederクラス
 *
 * このクラスは、rolesテーブルの初期データを挿入するためのシーダーです。
 */
class RolesTableSeeder extends Seeder
{
    /**
     * rolesテーブルに初期データを挿入します。
     * 
     * @return void
     */
    public function run(): void
    {
        // 既存のデータがあるかチェック
        $existingRoles = DB::table('roles')->pluck('id')->toArray();

        $roles = [
            ['id' => 'default',    'name' => 'デフォルト',   'created_at' => new DateTime()],
            ['id' => 'admin',      'name' => '管理者',      'created_at' => new DateTime()],
            ['id' => 'disabled',   'name' => '無効',        'created_at' => new DateTime()],
        ];

        // 重複していないものだけ挿入
        $newRoles = array_filter($roles, function ($role) use ($existingRoles) {
            return !in_array($role['id'], $existingRoles);
        });

        DB::table('roles')->insert($newRoles);
    }
}
