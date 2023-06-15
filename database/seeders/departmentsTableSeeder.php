<?php

namespace Database\Seeders;

use DateTime;
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
        DB::table('departments')->insert([
            ['id' => 'department_1',  'colleges_id' => 'college_1', 'name' => '学科 1', 'created_at' => new DateTime()],
            ['id' => 'department_2',  'colleges_id' => 'college_2', 'name' => '学科 2', 'created_at' => new DateTime()],
        ]);
    }
}
