<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;

/**
 * ユーザーモデルクラス
 *
 * このクラスは、ユーザーモデルの操作を行います。
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * フィルアブル属性の定義
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'college_id',
        'department_id',
    ];

    /**
     * シリアライズ時に非表示とする属性の定義
     *
     * @var array<int,string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 型キャストする属性の定義
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
