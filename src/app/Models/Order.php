<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    //支払方法
    // あとで消す　Order::PAYMENT_METHODS[$order->payment_method]で日本語表示
    public const PAYMENT_METHODS = [
        'convenience' => 'コンビニ払い',
        'credit_card' => 'カード支払い',
    ];

    protected $fillable = [
        'payment_method',
        'postal_code',
        'address',
        'building',
    ];
}
