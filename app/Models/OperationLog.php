<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 操作履歴モデルクラス
 *
 * このクラスは、操作履歴モデルの操作を行います。
 */
class OperationLog extends Model
{
    /**
     * フィルアブル属性の定義
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'user_id',
        'action',
    ];
}
