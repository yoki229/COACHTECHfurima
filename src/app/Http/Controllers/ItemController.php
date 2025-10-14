<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    //商品一覧画面の表示（おすすめ）
    public function index(Request $request){
        $user = auth()->user();
        $keyword = $request->input('keyword', '');

        $items = Item::select('id', 'image', 'name', 'user_id', 'buyer_id')

            // ユーザーが出品した商品は除外
            ->when($user, function ($query) use ($user){
            $query->where('user_id', '!=', $user->id);
            })
            ->search($keyword)
            ->get();

        $activeTab = 'recommend';

        return view('index', compact('items', 'activeTab', 'keyword'));
    }

    //商品一覧画面の表示（マイリスト）
    public function mylist(Request $request){
        $user = auth()->user();
        $keyword = $request->input('keyword', '');

        $items = $user->likes()
            ->search($keyword)
            ->get();

        $activeTab = 'mylist';

        return view('index', compact('items', 'activeTab', 'keyword'));
    }

    //商品一覧画面の検索機能
    public function search(Request $request){
        $user = auth()->user();
        $keyword = $request->input('keyword', '');
        $activeTab = $request->input('tab', 'recommend');

        if($activeTab === 'mylist'){
            $items = Item::whereIn('id', $user->likes->pluck('item_id'))
                //キーワード検索(部分一致処理をモデル側で定義)
                ->search($keyword)
                ->get();

        } else {
            $items = Item::select('id', 'image', 'name', 'user_id', 'buyer_id', )

            // ユーザーが出品した商品は除外
                ->when($user, function ($query) use ($user){
                $query->where('user_id', '!=', $user->id);
                })
                ->search($keyword)
                ->get();
        }

        return view('index', compact('items', 'keyword', 'activeTab'));
    }

    //商品詳細画面の表示
    public function getItem($item_id){
        $user = auth()->user();
        $item = Item::findOrFail($item_id);

        $item->liked = $user ? $user->likes->contains($item->id) : false;

        return view('item', compact('item'));
    }

    //いいね機能
    public function like($item_id){
        $user = auth()->user();
        $item = Item::findOrFail($item_id);

        if($user->likes()->where('item_id', $item->id)->exists())
        {
            $user->likes()->detach($item->id);
        } else {
            $user->likes()->attach($item->id);
        }

        return redirect("/item/{$item->id}");
    }

    //商品決済画面の表示
    public function sell(){
        return view('sell');
    }
}
