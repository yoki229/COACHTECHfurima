<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(){
        $items = Item::select('id','image', 'name')->get();

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
