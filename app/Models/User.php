<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'login_id',
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

    public function role()
    {
        return $this->belongsTo(Role::class,);
    }

    /**
     * Get the events organized by the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function organizedEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    /**
     * Get the events that the user has joined.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function joinedEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_participants', 'user_id', 'event_id');
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }
}
