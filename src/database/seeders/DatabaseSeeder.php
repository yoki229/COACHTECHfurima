<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 本番でも必要な初期データ
        $this->call([
            CategoriesTableSeeder::class,
            StatusesTableSeeder::class,
        ]);

        // 開発用ダミーデータ local、testing環境でのみ実行
        if (!app()->environment('production')) {
            $this->call([
                UsersTableSeeder::class,
                ItemsTableSeeder::class,
            ]);
        }
    }

}
