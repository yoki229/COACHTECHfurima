<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;

class ProfileController extends Controller
{
    // マイページの表示
    public function mypage(Request $request){
        $user = auth()->user();

        // クエリパラメータがbuyかsellを取得
        $page = $request->query('page', 'sell');

        if ($page === 'sell'){
            // 出品した商品
            $items = Item::where('user_id', $user->id)->get();
            $activeTab = 'sell';

        } else {
            // 購入した商品
            $items = Item::where('buyer_id', $user->id)->get();
            $activeTab = 'buy';
        }

        return view('mypage', compact('user', 'items', 'activeTab'));
    }

    // プロフィール更新ページの表示
    public function mypageProfile(){
        $user = auth()->user();
        return view('mypage_profile', compact('user'));
    }

    // プロフィール更新機能
    public function updateProfile(ProfileRequest $request){
        $user = auth()->user();

        // 画像を保存
        if (!$request->hasFile('profile_image')){
            return redirect('/mypage');
        }

        $image = $request->file('profile_image');
        $path = $image->store('profile_images', 'public');
        $name = basename($path);

        //本番環境では古い画像はstorageから削除する
        if(app()->isProduction() && $user->profile_image){
            Storage::disk('public')->delete('profile_images/' . $user->profile_image);
        }

        //新しい画像を保存
        $image->storeAs('profile_images', $name, 'public');

        //ＤＢを更新
        $user->update(['profile_image' => $name]);

        // リダイレクト先をフォームから取得。なければデフォルト '/'
        $redirect = $request->input('redirect_to') ? : '/mypage';
        return redirect($redirect);
    }

    // 住所変更画面
    public function editAddress($item_id){
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        return view('address', compact('user', 'item'));
    }

    // 住所変更機能
    public function updateAddress(AddressRequest $request, $item_id){
        $user = Auth::user();

        $user->update([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect("/purchase/{$item_id}");
    }

}
