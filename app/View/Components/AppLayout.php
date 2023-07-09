<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * アプリケーションレイアウトコンポーネントクラス
 *
 * このクラスは、アプリケーションのレイアウトコンポーネントを表します。
 * `layouts.app`ビューファイルを表示するためのメソッドを提供します。
 */
class AppLayout extends Component
{
    /**
     * コンポーネントを表示します。
     *
     * @return \Illuminate\View\View
     */
    public function render(): View
    {
        return view('layouts.app');
    }
}
