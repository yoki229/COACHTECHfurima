<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Models\User;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    //マイページの表示
    public function mypage(){
        return view('mypage');
    }

    //プロフィール更新ページの表示
    public function mypageProfile(){
        $user = auth()->user();
        return view('mypage_profile', compact('user'));
    }

    //プロフィール更新機能
    public function updateProfile(ProfileRequest $request){
        $user = auth()->user();

        //画像を保存
        if ($request->hasFile('profile_image')) {

            //古い画像を消去する(テスト中邪魔なので一時的にコメントアウト)
            // if($user->profile_image && Storage::disk('public')->exists('profile_images/' .$user->profile_image)){
            //     Storage::disk('public')->delete('profile_images/' .$user->profile_image);
            // }

            //新しい画像を元のファイル名のまま保存
            $originalName = $request->file('profile_image')->getClientOriginalName();
            $request->file('profile_image')->storeAs('profile_images', $originalName, 'public');
            $user->profile_image = $originalName; //ファイル名だけ保存
            $user->save();
        }

        //画像以外を保存
        $data = $request->only(['name', 'postal_code', 'address', 'building']);
        $user->update($data ?? []);

        // リダイレクト先をフォームから取得。なければデフォルト '/'
        $redirect = $request->input('redirect_to') ? : '/mypage';
        return redirect($redirect);
    }

}
