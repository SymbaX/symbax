<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['id' => 'screen',              'colleges_id' => 'creators',    'name' => '放送芸術科',                     'created_at' => new DateTime()],
            ['id' => 'actor',               'colleges_id' => 'creators',    'name' => '声優・演劇科',                   'created_at' => new DateTime()],
            ['id' => 'show',                'colleges_id' => 'creators',    'name' => '演劇スタッフ科',                 'created_at' => new DateTime()],
            ['id' => 'manga_anime_4years',  'colleges_id' => 'creators',    'name' => 'マンガ・アニメーション科四年制', 'created_at' => new DateTime()],
            ['id' => 'manga_anime_2years',  'colleges_id' => 'creators',    'name' => 'マンガ・アニメーション科',       'created_at' => new DateTime()],
            ['id' => 'gamecreator_4years',  'colleges_id' => 'design',      'name' => 'ゲームクリエイター科四年制',     'created_at' => new DateTime()],
            ['id' => 'gamecreator_2years',  'colleges_id' => 'design',      'name' => 'ゲームクリエイター科',           'created_at' => new DateTime()],
            ['id' => 'cgmovie',             'colleges_id' => 'design',      'name' => 'CG映像科',                       'created_at' => new DateTime()],
            ['id' => 'design',              'colleges_id' => 'design',      'name' => 'デザイン科',                     'created_at' => new DateTime()],
            ['id' => 'artist',              'colleges_id' => 'music',       'name' => 'ミュージックアーティスト科',     'created_at' => new DateTime()],
            ['id' => 'concertevent',        'colleges_id' => 'music',       'name' => 'コンサート・イベント科',         'created_at' => new DateTime()],
            ['id' => 'recording',           'colleges_id' => 'music',       'name' => '音響芸術科',                     'created_at' => new DateTime()],
            ['id' => 'dance',               'colleges_id' => 'music',       'name' => 'ダンスパフォーマンス科',         'created_at' => new DateTime()],
            ['id' => 'specialist',          'colleges_id' => 'it',          'name' => 'ITスペシャリスト科',             'created_at' => new DateTime()],
            ['id' => 'aisystem',            'colleges_id' => 'it',          'name' => 'AIシステム科',                   'created_at' => new DateTime()],
            ['id' => 'ip',                  'colleges_id' => 'it',          'name' => '情報処理科',                     'created_at' => new DateTime()],
            ['id' => 'network',             'colleges_id' => 'it',          'name' => 'ネットワークセキュリティ科',     'created_at' => new DateTime()],
            ['id' => 'business',            'colleges_id' => 'technology',  'name' => '情報ビジネス科',                 'created_at' => new DateTime()],
            ['id' => 'electronic',          'colleges_id' => 'technology',  'name' => '電子・電気科',                   'created_at' => new DateTime()],
            ['id' => 'architecture_4year',  'colleges_id' => 'technology',  'name' => '建築学科四年制',                 'created_at' => new DateTime()],
            ['id' => 'architecture_2year',  'colleges_id' => 'technology',  'name' => '建築学科',                       'created_at' => new DateTime()],
            ['id' => 'mashine',             'colleges_id' => 'technology',  'name' => '機械設計科',                     'created_at' => new DateTime()],
        ]);
    }
}
