<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * DatabaseSeederクラス
 *
 * このクラスは、アプリケーションのデータベースシーディングを行います。
 * データベースに初期データを挿入するためのシーダーを呼び出します。
 */
class DatabaseSeeder extends Seeder
{
    /**
     * アプリケーションのデータベースシーディングを実行します。
     * 
     * @return void
     */
    public function run(): void
    {
        $this->call(CollegesTableSeeder::class);
        $this->call(DepartmentsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
    }
}
