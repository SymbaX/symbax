<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
   {
        DB::table('events_category')->insert([
            ['id' => '1', 'category' => 'うんち'],
            ['id' => '2', 'category' => 'うんこ'],
        ]);
    }
}