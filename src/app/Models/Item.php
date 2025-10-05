<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'image',
        'description',
        'price',
        'brand_id',
        'status_id'
    ];

    //テキストボックスの検索
    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {

            // スペースで分割に対応
            $words = preg_split('/\s+/', $keyword);

            // 部分一致検索
            foreach ($words as $word)
            {
                $query->where('name', 'like', '%' . $keyword . '%');
            }
        }
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

    public function likes()
    {
        return $this->hasMany(Like::class);
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
