<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Order;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        $itemIds = Item::pluck('id')->shuffle();

        // Ordersを10件作成（1商品につき1注文だけにする）
        $itemIds->take(10)->each(function ($itemId) {
            Order::factory()->create([
                'item_id' => $itemId,
            ]);
        });
    }
}
