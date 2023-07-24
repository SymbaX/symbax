<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


/**
 * UsersTableSeederクラス
 *
 * UsersTableSeederクラスは、ユーザーデータのダミーデータをデータベースに挿入するためのシーダーです。
 */
class UsersTableSeeder extends Seeder
{
    /**
     * usersテーブルにダミーデータを挿入します。
     * 
     * @return void
     */
    public function run(): void
    {
        //
        for ($i = 1; $i < 100; $i++) {
            // 数字を2桁の文字列にフォーマット（例：1 => 01, 10 => 010）
            $formattedNum = str_pad($i, 2, '0', STR_PAD_LEFT);

            DB::table('users')->insert([
                'login_id' => 'test_' . $formattedNum,
                'name' => '[test]' . $formattedNum,
                'email' => 'test' . $formattedNum . '@g.neec.ac.jp',
                'email_verified_at' => now(),
                'password' => Hash::make('test' . $formattedNum . '@g.neec.ac.jp'),
                'college_id' => "it",
                'department_id' => "specialist",
            ]);
        }
    }
}
