<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;

class ProfileController extends Controller
{
    public function mypage(){
        return view('mypage');
    }

    public function mypageProfile(){
        return view('mypage_profile');
    }

    public function updateProfile(ProfileRequest $request){

        $user = auth()->user();

        //画像をセット
        if ($request->hasFile('image')) {
        $image = $request->file('image')->store('public/profile_images');
        $user['profile_image'] = $image;
        }

        //画像以外をセット
        $data = $request->only(['name', 'postal_code', 'address', 'building']);

        // ユーザー情報を更新
        $user->update($data);

        // リダイレクト先をフォームから取得。なければデフォルト '/'
        $redirect = $request->input('redirect_to', '/');
        return redirect($redirect);
    }

}
