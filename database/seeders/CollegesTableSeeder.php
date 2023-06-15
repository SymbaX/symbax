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
            ['id' => 'college_1', 'name' => 'カレッジ 1', 'created_at' => new DateTime()],
            ['id' => 'college_2', 'name' => 'カレッジ 2', 'created_at' => new DateTime()],
        ]);
    }
}
