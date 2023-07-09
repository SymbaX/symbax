<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        //
        DB::table('roles')->insert([
            ['id' => 'default',    'name' => 'デフォルト',     'created_at' => new DateTime()],
            ['id' => 'admin',      'name' => '管理者',           'created_at' => new DateTime()],
        ]);
    }
}
