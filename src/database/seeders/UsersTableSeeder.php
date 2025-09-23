<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run()
    {   
        //テストログイン用の一人分のテストダミーデータ
        $testUser = [
            'id' => 1,
            'name' => '山田太郎',
            'email' => 'yamada@example.com',
            'password' => bcrypt('password'),
            'profile_image' => 'storage/test_image/user_sample.png',
            'postal_code' => '123-4567',
            'address' => '群馬県富岡市南後箇1000',
            'building' => 'ユースパーク101',
        ];
        DB::table('users')->insert($testUser);

        User::factory(10)->create();
    }
}
