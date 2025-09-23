<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandsTableSeeder extends Seeder
{
    public function run()
    {
        $brands = ['Rolax', '西芝', 'なし', 'Starbacks',];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(['name' => $brand]);
        }
    }
}
