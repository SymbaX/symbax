<?php

namespace Database\Seeders;

use DateInterval;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * SampleEventsTableSeederクラス
 *
 * このクラスは、eventsテーブルにダミーデータを挿入するためのシーダーです。
 */
class SampleEventsTableSeeder extends Seeder
{
    /**
     * eventsテーブルにダミーデータを挿入します。
     * 
     * @return void
     */
    public function run(): void
    {
        // 現在の日付
        $today = new DateTime();

        for ($i = 1; $i < 50; $i++) {
            // 数字を2桁の文字列にフォーマット（例：1 => 01, 10 => 010）
            $formattedNum = str_pad($i, 2, '0', STR_PAD_LEFT);

            // number_of_recruitsが1から5まで繰り返されるようにする
            $numberOfRecruits = ($i - 1) % 5 + 1;

            // organizer_idが1と2を交互に繰り返すようにする
            $organizerId = $i % 2 === 0 ? 2 : 1;

            // $iを元に日付を計算（現在の日付から前後25日）
            $eventDate = clone $today;
            $eventDate->sub(new DateInterval('P25D'))->add(new DateInterval('P' . $i . 'D'));

            // deadline_dateはeventDateの1日前に設定
            $deadlineDate = clone $eventDate;
            $deadlineDate->sub(new DateInterval('P1D'));


            DB::table('events')->insert([
                'name' => 'イベントタイトル' . $formattedNum,
                'detail' => "## イベント詳細" . $formattedNum,
                "category" => "カテゴリー" . $formattedNum,
                "tag" => "タグ" . $formattedNum,
                "participation_condition" => "参加条件" . $formattedNum,
                "external_link" => "https://example.com/" . $formattedNum,
                "date" => $eventDate->format('Y-m-d'),
                "deadline_date" => $deadlineDate->format('Y-m-d'),
                "place" => "開催場所" . $formattedNum,
                "number_of_recruits" => $numberOfRecruits,
                "image_path" => "public/events/dummy" . $formattedNum . ".jpg",
                "organizer_id" => $organizerId,
                "created_at" => new DateTime()
            ]);
        }
    }
}
