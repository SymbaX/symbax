<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * DepartmentsTableSeederクラス
 *
 * このクラスは、departmentsテーブルの初期データを挿入するためのシーダーです。
 */
class DepartmentsTableSeeder extends Seeder
{
    /**
     * departmentsテーブルに初期データを挿入します。
     * 
     * @return void
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['id' => 'screen',              'college_id' => 'creators',    'name' => '放送芸術科',                     'created_at' => new DateTime()],
            ['id' => 'actor',               'college_id' => 'creators',    'name' => '声優・演劇科',                   'created_at' => new DateTime()],
            ['id' => 'show',                'college_id' => 'creators',    'name' => '演劇スタッフ科',                 'created_at' => new DateTime()],
            ['id' => 'manga_anime_4years',  'college_id' => 'creators',    'name' => 'マンガ・アニメーション科四年制', 'created_at' => new DateTime()],
            ['id' => 'manga_anime_2years',  'college_id' => 'creators',    'name' => 'マンガ・アニメーション科',       'created_at' => new DateTime()],
            ['id' => 'gamecreator_4years',  'college_id' => 'design',      'name' => 'ゲームクリエイター科四年制',     'created_at' => new DateTime()],
            ['id' => 'gamecreator_2years',  'college_id' => 'design',      'name' => 'ゲームクリエイター科',           'created_at' => new DateTime()],
            ['id' => 'cgmovie',             'college_id' => 'design',      'name' => 'CG映像科',                       'created_at' => new DateTime()],
            ['id' => 'design',              'college_id' => 'design',      'name' => 'デザイン科',                     'created_at' => new DateTime()],
            ['id' => 'artist',              'college_id' => 'music',       'name' => 'ミュージックアーティスト科',     'created_at' => new DateTime()],
            ['id' => 'concertevent',        'college_id' => 'music',       'name' => 'コンサート・イベント科',         'created_at' => new DateTime()],
            ['id' => 'recording',           'college_id' => 'music',       'name' => '音響芸術科',                     'created_at' => new DateTime()],
            ['id' => 'dance',               'college_id' => 'music',       'name' => 'ダンスパフォーマンス科',         'created_at' => new DateTime()],
            ['id' => 'specialist',          'college_id' => 'it',          'name' => 'ITスペシャリスト科',             'created_at' => new DateTime()],
            ['id' => 'aisystem',            'college_id' => 'it',          'name' => 'AIシステム科',                   'created_at' => new DateTime()],
            ['id' => 'ip',                  'college_id' => 'it',          'name' => '情報処理科',                     'created_at' => new DateTime()],
            ['id' => 'network',             'college_id' => 'it',          'name' => 'ネットワークセキュリティ科',     'created_at' => new DateTime()],
            ['id' => 'business',            'college_id' => 'it',          'name' => '情報ビジネス科',                 'created_at' => new DateTime()],
            ['id' => 'electronic',          'college_id' => 'technology',  'name' => '電子・電気科',                   'created_at' => new DateTime()],
            ['id' => 'architecture_4year',  'college_id' => 'technology',  'name' => '建築学科四年制',                 'created_at' => new DateTime()],
            ['id' => 'architecture_2year',  'college_id' => 'technology',  'name' => '建築学科',                       'created_at' => new DateTime()],
            ['id' => 'mashine',             'college_id' => 'technology',  'name' => '機械設計科',                     'created_at' => new DateTime()],
        ]);
    }
}
