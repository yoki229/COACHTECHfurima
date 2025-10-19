<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Order;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PurchaseRequest;

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

        //いいね
        $item->liked = $user ? $user->likes->contains($item->id) : false;
        $likeCount = $item->likedUsers()->count();

        //コメント
        $commentCount = $item->comments()->count();

        //カテゴリー
        $categories = $item->categories;

        return view('item', compact('item', 'likeCount', 'commentCount', 'categories'));
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

    //コメント機能
    public function commentsStore(CommentRequest $request, $item_id){
        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item_id,
            'comment' => $request->comment,
        ]);

        if(!auth()->check()){
            // ログインしていなければメッセージを返す
            return redirect()->back()->with('error', 'ログインしてください');
        }

        return redirect()->back();
    }

    //商品購入画面の表示
    public function purchase($item_id){
        $user = auth()->user();
        $item = Item::findOrFail($item_id);
        $payments = Order::PAYMENT_METHODS;

        return view('purchase', compact('user', 'item', 'payments'));
    }

    //決済処理
    public function store(PurchaseRequest $request, $item_id){
        $user = Auth::user();
        $item = Item::findOrFail($item_id);
        $orderData = $request->only([
            'payment_method',
            'postal_code',
            'building'
        ]);
        $orderData['postal_code'] = str_replace('〒', '', $orderData['postal_code']);

        $orderData['user_id'] = $user->id;
        $orderData['item_id'] = $item->id;

        Order::create($orderData);

        $item->update(['buyer_id' => $user->id,]);

        return redirect('/')->with('success', '購入が完了しました！');
    }
}
