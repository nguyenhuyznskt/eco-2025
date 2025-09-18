<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Điện thoại', 'slug' => 'dien-thoai', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Laptop', 'slug' => 'laptop', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Phụ kiện', 'slug' => 'phu-kien', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
