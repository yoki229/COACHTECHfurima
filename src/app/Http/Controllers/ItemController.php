<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(){

        $items = Item::select('image', 'name')->get();

        return view('index', compact('items'));
    }

    public function getItem($id){

        $item = item::find($id);

        return view('item', compact('item'));
    }

    public function sell(){
        return view('sell');
    }
}
