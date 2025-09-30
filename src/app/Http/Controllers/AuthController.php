<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    
    public function index(){
      return view('index');
    }

    public function mypageProfile(){
      return view('mypage_profile');
    }

}
