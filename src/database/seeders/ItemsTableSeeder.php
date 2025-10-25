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
            'item_image' => 'storage/item_image/Clock.jpg',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'brand' => 'Rolax',
            'status_id' => 1,
            'category' => [1, 5, 12],
            ],
            [
            'user_id' => 2,
            'name' => 'HDD',
            'price' => 5000,
            'item_image' => 'storage/item_images/Disk.jpg',
            'description' => '高速で信頼性の高いハードディスク',
            'brand' => '西芝',
            'status_id' => 2,
            'category' => [2],
            ],
            [
            'user_id' => 3,
            'name' => '玉ねぎ3束',
            'price' => 300,
            'item_image' => 'storage/item_image/iLoveIMG+d.jpg',
            'description' => '新鮮な玉ねぎ3束のセット',
            'brand' => 'なし',
            'status_id' => 3,
            'category' => [10],
            ],
            [
            'user_id' => 4,
            'buyer_id' => 1,
            'name' => '革靴',
            'buyer_id'=> 3,
            'price' => 4000,
            'item_image' => 'storage/item_image/Shoes.jpg',
            'description' => 'クラシックなデザインの革靴',
            'brand' => null,
            'status_id' => 4,
            'category' => [1, 5],
            ],
            [
            'user_id' => 5,
            'name' => 'ノートPC',
            'price' => 45000,
            'item_image' => 'storage/item_image/Laptop.jpg',
            'description' => '高性能なノートパソコン',
            'brand' => null,
            'status_id' => 1,
            'category' => [2],
            ],
            [
            'user_id' => 1,
            'name' => 'マイク',
            'price' => 8000,
            'item_image' => 'storage/item_image/Mic.jpg',
            'description' => '高音質のレコーディング用マイク',
            'brand' => 'なし',
            'status_id' => 2,
            'category' => [2],
            ],
            [
            'user_id' => 1,
            'name' => 'ショルダーバッグ',
            'buyer_id' => 5,
            'price' => 3500,
            'item_image' => 'storage/item_image/bag.jpg',
            'description' => 'おしゃれなショルダーバッグ',
            'brand' => null,
            'status_id' => 3,
            'category' => [1, 4],
            ],
            [
            'user_id' => 8,
            'name' => 'タンブラー',
            'price' => 500,
            'item_image' => 'storage/item_image/Tumbler.jpg',
            'description' => '使いやすいタンブラー',
            'brand' => 'なし',
            'status_id' => 4,
            'category' => [10],
            ],
            [
            'user_id' => 9,
            'name' => 'コーヒーミル',
            'price' => 4000,
            'item_image' => 'storage/item_image/Coffee+Grinder.jpg',
            'description' => '手動のコーヒーミル',
            'brand' => 'Starbacks',
            'status_id' => 1,
            'category' => [10],
            ],
            [
            'user_id' => 1,
            'name' => 'メイクセット',
            'price' => 2000,
            'item_image' => 'storage/item_image/makeup.jpg',
            'description' => '便利なメイクアップセット',
            'brand' => null,
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
