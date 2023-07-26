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
            ['id' => 'live_concert', 'name' => 'ライブ・コンサート'],
            ['id' => 'sports', 'name' => 'スポーツ'],
            ['id' => 'game', 'name' => 'ゲーム'],
            ['id' => 'anime', 'name' => 'アニメ'],
            ['id' => 'stage_play_comedy', 'name' => '舞台・演劇・お笑い'],
            ['id' => 'others', 'name' => 'その他']
        ];

        // 重複していないものだけ挿入
        $newRoles = array_filter($categories, function ($role) use ($existingCategories) {
            return !in_array($role['id'], $existingCategories);
        });

        DB::table('event_categories')->insert($newRoles);
    }
}
