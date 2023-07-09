<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * ゲストレイアウトコンポーネントクラス
 *
 * このクラスは、ゲスト用のレイアウトコンポーネントを表します。
 * `layouts.guest`ビューファイルを表示するためのメソッドを提供します。
 */
class GuestLayout extends Component
{
    /**
     * コンポーネントを表示します。
     *
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('layouts.guest');
    }
}
