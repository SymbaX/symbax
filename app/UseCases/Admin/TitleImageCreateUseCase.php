<?php

namespace App\UseCases\Admin;

use App\Models\Event;
use App\UseCases\OperationLog\OperationLogUseCase;
use Intervention\Image\Facades\Image;

/**
 * タイトルイメージ生成のユースケースクラス
 *
 * タイトルイメージ生成の動作やログ記録に関連する処理を担当します。
 */
class TitleImageCreateUseCase
{
    /**
     * 操作ログを保存するためのビジネスロジックを提供するユースケース
     * このユースケースを利用して、システムの操作に関するログの記録処理を行います。
     *
     * @var OperationLogUseCase
     */
    private $operationLogUseCase;

    /**
     * TitleImageCreateUseCaseのコンストラクタ
     *
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param OperationLogUseCase $operationLogUseCase 操作ログに関するユースケース
     */
    public function __construct(OperationLogUseCase $operationLogUseCase)
    {
        $this->operationLogUseCase = $operationLogUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * タイトルイメージ生成の処理を行います。
     *
     * タイトルイメージを一括で生成する処理を行います。
     */
    public function createImage()
    {
        // イベントを取得する
        $events =  Event::all();

        foreach ($events as $event) {
            $this->createEventOGP($event);
        }

        return;
    }

    /**
     * イベントのOGPを作成する
     *
     * @param Event $event
     * @return void
     */
    private function createEventOGP(Event $event)
    {
        // OGPを生成
        $path = public_path('img/base.png');
        $img = Image::make($path);

        // 画像にテキストを入れる。
        $img->text(preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $event->name), 60, 220, function ($font) {
            $font->file(public_path('fonts/NotoSansJP-SemiBold.ttf'));
            $font->size(54);
            $font->color("#000");
            $font->align("left");
            $font->valign("top");
        });

        // OGP画像を保存
        $save_path = storage_path('app/public/event-titles/ogp_' . $event->id . '.png');
        $img->save($save_path);
    }
}
