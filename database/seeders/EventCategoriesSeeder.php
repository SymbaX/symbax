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
            ['id' => '1', 'category' => 'うんち'],
            ['id' => '2', 'category' => 'うんこ'],
        ]);
    }
}
