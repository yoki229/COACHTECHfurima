<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Order;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        $productIds = Product::pluck('id')->shuffle();

        // Ordersを10件作成（1商品につき1注文だけにする）
        $productIds->take(10)->each(function ($productId) {
            Order::factory()->create([
                'product_id' => $productId,
            ]);
        });
    }
}
