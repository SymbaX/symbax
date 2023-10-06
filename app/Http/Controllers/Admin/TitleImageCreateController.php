<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\UseCases\Admin\TitleImageCreateUseCase;

/**
 * タイトルイメージ生成のコントローラークラス
 */
class TitleImageCreateController extends Controller
{
    /**
     * タイトルイメージ生成のビジネスロジックを提供するユースケース
     *
     * @var TitleImageCreateUseCase 使用するUseCaseインスタンス
     */
    private $titleImageCreateUseCase;

    /**
     * TitleImageCreateControllerのコンストラクタ
     *
     * 使用するユースケースをインジェクション（注入）します。
     *
     * @param TitleImageCreateUseCase $titleImageCreateUseCase タイトルイメージ生成のユースケース
     */
    public function __construct(TitleImageCreateUseCase $titleImageCreateUseCase)
    {
        $this->titleImageCreateUseCase = $titleImageCreateUseCase;
    }

    /* =================== 以下メインの処理 =================== */

    /**
     * タイトルイメージを生成するメソッド
     *
     * タイトルイメージを生成する処理を呼び出します。
     *
     * @return View 管理者ダッシュボードのビュー
     */
    public function createImage()
    {
        $this->titleImageCreateUseCase->createImage();

        // Viewを返す
        return view('admin.dashboard');
    }
}
