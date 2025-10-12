<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(){
        $user = auth()->user();

        $items = Item::select('id', 'image',  'name', 'user_id', 'status_id', 'buyer_id')

        // ユーザーが出品した商品は除外
        ->when($user, function ($query) use ($user){
            $query->where('user_id', '!=', $user->id);
        })
        ->get()

        // 購入済み商品か判定
        ->map(function ($item){
            $item->sold_class = $item->buyer_id ? 'sold-item' : '';
            return $item;
        });

        return view('index', compact('items'));
    }

    public function mylist(){
        $user = auth()->user();
        $items = $user->likes()->with('item')->get()->pluck('item');

        return view('index', compact('items'));
    }

    public function getItem($item_id){
        $item = Item::find($item_id);

        return view('item', compact('item'));
    }

    public function sell(){
        return view('sell');
    }
}
