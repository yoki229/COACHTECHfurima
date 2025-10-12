<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{

    public function run()
    {
        //商品情報のダミーデータ10件
        $items = [
            [
            'user_id' => 1,
            'name' => '腕時計',
            'price' => 15000,
            'image' => 'storage/test_images/Clock.jpg',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'brand_id' => 1,
            'status_id' => 1,
            'category' => [1, 5, 12],
            ],
            [
            'user_id' => 2,
            'name' => 'HDD',
            'price' => 5000,
            'image' => 'storage/test_images/Disk.jpg',
            'description' => '高速で信頼性の高いハードディスク',
            'brand_id' => 2,
            'status_id' => 2,
            'category' => [2],
            ],
            [
            'user_id' => 3,
            'name' => '玉ねぎ3束',
            'price' => 300,
            'image' => 'storage/test_images/iLoveIMG+d.jpg',
            'description' => '新鮮な玉ねぎ3束のセット',
            'brand_id' => 4,
            'status_id' => 3,
            'category' => [10],
            ],
            [
            'user_id' => 4,
            'name' => '革靴',
            'buyer_id'=> 3,
            'price' => 4000,
            'image' => 'storage/test_images/Shoes.jpg',
            'description' => 'クラシックなデザインの革靴',
            'brand_id' => null,
            'status_id' => 4,
            'category' => [1, 5],
            ],
            [
            'user_id' => 5,
            'name' => 'ノートPC',
            'price' => 45000,
            'image' => 'storage/test_images/Laptop.jpg',
            'description' => '高性能なノートパソコン',
            'brand_id' => null,
            'status_id' => 1,
            'category' => [2],
            ],
            [
            'user_id' => 6,
            'name' => 'マイク',
            'price' => 8000,
            'image' => 'storage/test_images/Mic.jpg',
            'description' => '高音質のレコーディング用マイク',
            'brand_id' => 3,
            'status_id' => 2,
            'category' => [2],
            ],
            [
            'user_id' => 7,
            'name' => 'ショルダーバッグ',
            'buyer_id' => 5,
            'price' => 3500,
            'image' => 'storage/test_images/bag.jpg',
            'description' => 'おしゃれなショルダーバッグ',
            'brand_id' => null,
            'status_id' => 3,
            'category' => [1, 4],
            ],
            [
            'user_id' => 8,
            'name' => 'タンブラー',
            'price' => 500,
            'image' => 'storage/test_images/Tumbler.jpg',
            'description' => '使いやすいタンブラー',
            'brand_id' => 3,
            'status_id' => 4,
            'category' => [10],
            ],
            [
            'user_id' => 9,
            'name' => 'コーヒーミル',
            'price' => 4000,
            'image' => 'storage/test_images/Coffee+Grinder.jpg',
            'description' => '手動のコーヒーミル',
            'brand_id' => 4,
            'status_id' => 1,
            'category' => [10],
            ],
            [
            'user_id' => 10,
            'name' => 'メイクセット',
            'price' => 2000,
            'image' => 'storage/test_images/makeup.jpg',
            'description' => '便利なメイクアップセット',
            'brand_id' => null,
            'status_id' => 2,
            'category' => [1, 4, 6],
            ],
        ];

        // データを挿入
        foreach ($items as $item) {
            $categories = $item['category'];
            unset($item['category']);
            $newItem = Item::create($item);
            $newItem->categories()->sync($categories);
        }
    }
}
