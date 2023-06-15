<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class collegesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param  = [
            'ID' => 'creators',
            'collegename'=> 'クリエイターズカレッジ',
        ];
        DB::table('colleges')->insert($param);

        $param  = [
            'ID' => 'design',
            'collegename'=> 'デザインカレッジ',
        ];
        DB::table('colleges')->insert($param);

        $param  = [
            'ID' => 'music',
            'collegename'=> 'ミュージックカレッジ',
        ];
        DB::table('colleges')->insert($param);

        $param  = [
            'ID' => 'it',
            'collegename'=> 'ITカレッジ',
        ];
        DB::table('colleges')->insert($param);

        $param  = [
            'ID' => 'technology',
            'collegename'=> 'テクノロジーカレッジ',
        ];
        DB::table('colleges')->insert($param);
    }
}
