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
        DB::table('event_categories')->insert([
            ['id' => '1', 'category' => 'ライブ・コンサート'],
            ['id' => '2', 'category' => 'スポーツ'],
            ['id' => '3', 'category' => 'ゲーム'],
            ['id' => '4', 'category' => 'アニメ'],
            ['id' => '5', 'category' => '舞台・演劇・お笑い'],
            ['id' => '6', 'category' => 'その他']
        ]);
    }
}
