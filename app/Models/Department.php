<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 学科モデルクラス
 *
 * このクラスは、学科モデルの操作を行います。
 */
class Department extends Model
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
