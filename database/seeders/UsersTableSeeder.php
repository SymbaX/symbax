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
            DB::table('users')->insert([
                'name' => '[test]' . $i,
                'email' => 'test' . $i . '@g.neec.ac.jp',
                'email_verified_at' => now(),
                'password' => Hash::make('test' . $i . '@g.neec.ac.jp'),
                'college_id' => "it",
                'department_id' => "specialist",
            ]);
        }
    }
}
