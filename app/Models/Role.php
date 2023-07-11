<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ロールモデルクラス
 *
 * このクラスは、ロールモデルの操作を行います。
 */
class Role extends Model
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
