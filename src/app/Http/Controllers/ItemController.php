<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Comment;
use App\Models\Category;
use App\Models\Status;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
    // 商品一覧画面の表示
    public function index(Request $request){
        $user = auth()->user();
        $keyword = $request->input('keyword', '');
        $tab = $request->input('tab', 'recommend');

        //マイリスト表示
        if($tab === 'mylist') {
            if(! $user || ! $user->hasVerifiedEmail()){
                $items = collect();
            } else {
                $items = $user->likedItems()
                ->search($keyword)
                ->get();
            }
        } else {
            //おすすめ表示
            $items = Item::select('id', 'item_image', 'name', 'user_id', 'buyer_id')

            // ユーザーが出品した商品は除外
            ->when($user, function ($query) use ($user){
            $query->where('user_id', '!=', $user->id);
            })
            ->search($keyword)
            ->get();
        }

        $activeTab = $tab;

        return view('index', compact('items', 'activeTab', 'keyword'));
    }

    // 商品一覧画面の検索機能
    public function search(Request $request){
        $user = auth()->user();
        $keyword = $request->input('keyword', '');
        $activeTab = $request->input('tab', 'recommend');

        if($activeTab === 'mylist'){
            // キーワード検索(部分一致処理をモデル側で定義)
            $items = $user->likes()->search($keyword)->get();
        } else {
            $items = Item::select('id', 'item_image', 'name', 'user_id', 'buyer_id', )

            // ユーザーが出品した商品は除外
                ->when($user, function ($query) use ($user){
                $query->where('user_id', '!=', $user->id);
                })
                ->search($keyword)
                ->get();
        }

        return view('index', compact('items', 'keyword', 'activeTab'));
    }

    // 商品詳細画面の表示
    public function getItem($item_id){
        $user = auth()->user();
        $item = Item::findOrFail($item_id);

        // いいね
        $item->liked = $user ? $user->likes->contains($item->id) : false;
        $likeCount = $item->likedUsers()->count();

        // コメント
        $commentCount = $item->comments()->count();

        // カテゴリー
        $categories = $item->categories;

        return view('item', compact('item', 'likeCount', 'commentCount', 'categories'));
    }

    // いいね機能
    public function like($item_id){
        $user = auth()->user();
        $item = Item::findOrFail($item_id);

        $user->likes()->toggle($item->id);

        return redirect("/item/{$item->id}");
    }

    // コメント機能
    public function commentsStore(CommentRequest $request, $item_id){
        if(!auth()->check()){
            // ログインしていなければメッセージを返す
            return redirect()->back()->with('error', 'ログインしてください');
        }

        $form = $request->validated();
        $form['user_id'] = auth()->id();
        $form['item_id'] = $item_id;

        Comment::create($form);

        return redirect()->back();
    }

    // 出品画面の表示
    public function sell(){
        $categories = Category::all();
        $statuses = Status::all();

        return view('sell', compact('categories', 'statuses'));
    }

    // 出品処理
    public function sellStore(ExhibitionRequest $request){
        $form = $request->except('category');
        $form['user_id'] = auth()->id();

        // 商品の状態を保存
        $form['status_id'] = $request->input('status');

        // 画像は名前をつけて保存
        if ($request->hasFile('item_image')){
            $originalName = $request->file('item_image')->getClientOriginalName();
            $request->file('item_image')->storeAs('item_images', $originalName, 'public');
            $form['item_image'] = $originalName;
        }

        $item = Item::create($form);

        // 中間テーブルに保存するカテゴリの紐づけ
        $item->categories()->sync($request->input('category'));

        return redirect('mypage')->with('success', '商品を登録しました！');
    }

}
