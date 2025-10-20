<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Brand;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'buyer_id',
        'name',
        'image',
        'description',
        'price',
        'brand_id',
        'status_id'
    ];

    //テキストボックスの検索
    public function scopeSearch($query, $keyword)
    {
        if (!empty($keyword)) {

            // スペースで分割に対応
            $words = preg_split('/[\s ]+/u', trim($keyword));

            // 部分一致検索
            foreach ($words as $word)
            {
                $query->where('name', 'like', "%{$word}%");
            }
        }
    }

    //購入済みの判定アクセサ
    public function getsoldClassAttribute()
    {
        return $this->buyer_id ? 'sold-item' : '';
    }

    //ブランド名表示アクセサ
    public function getBrandNameAttribute()
    {
        return $this->brand_id ? $this->brand->name : '';
    }

    //いいねをしているか判定アクセサ
    public function getLikedAttribute(){
        $user = auth()->user();
        return $user ? $user->likes->contains($this->id) : false;
    }

    //リレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likedUsers()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
    }
}
