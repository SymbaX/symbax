<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CollegesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('colleges')->insert([
            [' id' => 'creators', ' name' => 'クリエイターズカレッジ', 'created_at' => new DateTime()],
            [' id' => 'design', ' name' => 'デザインカレッジ', 'created_at' => new DateTime()],
            [' id' => 'music', ' name' => 'ミュージックカレッジ', 'created_at' => new DateTime()],
            [' id' => 'it', ' name' => 'ITカレッジ', 'created_at' => new DateTime()],
            [' id' => 'technology', ' name' => 'テクノロジーカレッジ', 'created_at' => new DateTime()],
        ]);
    }
}
