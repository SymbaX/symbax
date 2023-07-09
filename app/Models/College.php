<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * カレッジモデルクラス
 *
 * このクラスは、カレッジモデルの操作を行います。
 */
class College extends Model
{
    /**
     * プライマリキーのカラム名を取得します。
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * プライマリキーのデータ型を取得します。
     *
     * @var string
     */
    protected $keyType = 'string';
}
