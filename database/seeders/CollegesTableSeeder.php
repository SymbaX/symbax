<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * CollegesTableSeederクラス
 *
 * このクラスは、collegesテーブルのシーダーを定義します。
 * カレッジの初期データをデータベースに挿入するためのメソッドを提供します。
 */
class CollegesTableSeeder extends Seeder
{
    /**
     * collegesテーブルに初期データを挿入します。
     * 
     * @return void
     */
    public function run(): void
    {
        // 既存のデータがあるかチェック
        $existingColleges = DB::table('colleges')->pluck('id')->toArray();

        $colleges = [
            ['id' => 'creators',    'name' => 'クリエイターズカレッジ',     'created_at' => new DateTime()],
            ['id' => 'design',      'name' => 'デザインカレッジ',           'created_at' => new DateTime()],
            ['id' => 'music',       'name' => 'ミュージックカレッジ',       'created_at' => new DateTime()],
            ['id' => 'it',          'name' => 'ITカレッジ',                 'created_at' => new DateTime()],
            ['id' => 'technology',  'name' => 'テクノロジーカレッジ',       'created_at' => new DateTime()],
        ];

        // 重複していないものだけ挿入
        $newColleges = array_filter($colleges, function ($college) use ($existingColleges) {
            return !in_array($college['id'], $existingColleges);
        });

        DB::table('colleges')->insert($newColleges);
    }
}
