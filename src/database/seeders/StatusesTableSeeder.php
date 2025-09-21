<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder
{
    public function run()
    {
        $statuses = ['良好','目立った傷や汚れなし','やや傷や汚れあり','状態が悪い'];

        foreach ($statuses as $status) {
            Status::create(['name' => $status]);
        }
    }
}
