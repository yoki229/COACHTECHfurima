<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{

    public function run()
    {
        //シーディング前に一度データをクリア
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('likes')->truncate();
        DB::table('comments')->truncate();
        DB::table('orders')->truncate();
        DB::table('users')->truncate();
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        //商品情報のダミーデータ10件

        $products = [
            [
            'user_id' => 1,
            'name' => '腕時計',
            'price' => 15000,
            'image' => 'storage/test_image/Clock.png',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'brand_id' => 1,
            'status_id' => 1,
            'category' => [1],
            ],
        ];

        // データを挿入
        foreach ($products as $product) {
            $categories = $product['categories'];
            unset($product['categories']);
            $newProduct = Product::create($product);
            $newProduct->categories()->sync($categories);
        }
    }
}
