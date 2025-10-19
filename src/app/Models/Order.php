<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    //支払方法
    public const PAYMENT_METHODS = [
        'convenience' => 'コンビニ払い',
        'credit_card' => 'カード支払い',
    ];

    protected $fillable = [
        'user_id',
        'item_id',
        'payment_method',
        'postal_code',
        'address',
        'building',
    ];

    //PAYMENT_METHODSのアクセサ
    public function getPayAttribute()
    {
        return self::PAYMENT_METHODS[$this->payment_method];
    }
}
