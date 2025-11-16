<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusesTableSeeder extends Seeder
{
    public function run()
    {
        $statusData = [
        1 => '良好',
        2 => '目立った傷や汚れなし',
        3 => 'やや傷や汚れあり',
        4 => '状態が悪い',
        ];

        // シーディングするたびに重複作成されない為、なければ作るの設定
       foreach ($statusData as $id => $name) {
            Status::updateOrCreate(
                ['id' => $id],
                ['name' => $name]
            );
        }
    }
}
