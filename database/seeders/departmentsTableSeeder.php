<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class departmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $param  = [
            'ID' => 'screen',
            'colleges'=> 'creators',
            'deparmentname'=>'放送芸術科'
        ];
        DB::table('departments')->insert($param);

        $param  = [
            'ID' => 'actor',
            'colleges'=> 'creators',
            'deparmentname'=>'声優・演劇科'
        ];
        DB::table('departments')->insert($param);

        $param  = [
            'ID' => 'show',
            'colleges'=> 'creators',
            'deparmentname'=>'演劇スタッフ科'
        ];
        DB::table('departments')->insert($param);

        $param  = [
            'ID' => 'manga_anime_4yeaars',
            'colleges'=> 'creators',
            'deparmentname'=>'マンガ・アニメーション科四年制'
        ];
        DB::table('departments')->insert($param);

        $param  = [
            'ID' => 'manga_anime_2yeaars',
            'colleges'=> 'creators',
            'deparmentname'=>'マンガ・アニメーション科'
        ];
        DB::table('departments')->insert($param);

        $param  = [
            'ID' => 'gamecreator_4yeaars',
            'colleges'=> 'design',
            'deparmentname'=>'ゲームクリエイター科四年制'
        ];
        DB::table('departments')->insert($param);

        
    }
}
