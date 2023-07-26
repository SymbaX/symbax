<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('')->insert([]);


        // 既存のデータがあるかチェック
        $existingCategories = DB::table('event_categories')->pluck('id')->toArray();

        $categories = [
            ['id' => '01_live_concert', 'name' => 'ライブ・コンサート'],
            ['id' => '02_sports', 'name' => 'スポーツ'],
            ['id' => '03_game', 'name' => 'ゲーム'],
            ['id' => '04_anime', 'name' => 'アニメ'],
            ['id' => '05_stage_play_comedy', 'name' => '舞台・演劇・お笑い'],
            ['id' => '99_others', 'name' => 'その他']
        ];

        // 重複していないものだけ挿入
        $newRoles = array_filter($categories, function ($role) use ($existingCategories) {
            return !in_array($role['id'], $existingCategories);
        });

        DB::table('event_categories')->insert($newRoles);
    }
}
