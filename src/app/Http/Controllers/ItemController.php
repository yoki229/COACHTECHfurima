<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(){

      $products = Product::only('image','name');

      return view('index', compact('products'));
    }

    public function getItem($id){

      $item = item::find($id);

      return view('item', compact('item'));
    }

    public function sell(){
      return view('sell');
    }
}
