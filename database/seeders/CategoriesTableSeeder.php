<?php

namespace Database\Seeders;

use App\Models\Categories;
use Illuminate\Database\Seeder;


class CategoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Root category
        $electronics = Categories::create([
            'name' => 'Điện thoại',
            'slug' => 'dien-thoai',
        ]);

        $laptop = Categories::create([
            'name' => 'Laptop',
            'slug' => 'laptop',
        ]);

        $accessory = Categories::create([
            'name' => 'Phụ kiện',
            'slug' => 'phu-kien',
        ]);

        // Nếu muốn thêm con cho root
        $electronics->children()->createMany([
            ['name' => 'iPhone', 'slug' => 'iphone'],
            ['name' => 'Samsung', 'slug' => 'samsung'],
        ]);

        $laptop->children()->createMany([
            ['name' => 'Macbook', 'slug' => 'macbook'],
            ['name' => 'Asus', 'slug' => 'asus'],
        ]);
    }
}
