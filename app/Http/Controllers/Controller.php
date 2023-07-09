<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * コントローラークラス
 *
 * このクラスは、コントローラーのベースとなる機能を提供します。
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
