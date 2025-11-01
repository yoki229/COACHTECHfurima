<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_image',
        'postal_code',
        'address',
        'building',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //メール認証のオーバーライド
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

    //プロフィール画像のアクセサ
    public function getProfileImageAttribute()
    {
        //画像が登録されていればそのパス、なければデフォルト画像
        $profile_image = $this->attributes['profile_image']
        ? 'storage/profile_images/' . $this->attributes['profile_image']
        : 'storage/test_images/user_default.png';

        return asset($profile_image);
    }

    //リレーション
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function likes()
    {
        return $this->belongsToMany(Item::class, 'likes')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
