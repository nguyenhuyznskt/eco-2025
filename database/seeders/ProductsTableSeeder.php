<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'vendor_id' => 1,
                'category_id' => 1,
                'name' => 'iPhone 15 Pro Max',
                'slug' => Str::slug('iPhone 15 Pro Max'),
                'short_description' => 'Điện thoại cao cấp Apple',
                'description' => 'Điện thoại flagship Apple 2025, màn hình ProMotion, chip A19, camera xịn.',
                'price' => 30000000,
                'compare_price' => 32990000,
                'is_active' => true,
                'is_featured' => true,
                'views' => 0,
                'meta' => json_encode(['brand' => 'Apple', 'color' => 'Titanium']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'vendor_id' => 1,
                'category_id' => 2,
                'name' => 'MacBook Pro M3',
                'slug' => Str::slug('MacBook Pro M3'),
                'short_description' => 'Laptop Apple mạnh mẽ',
                'description' => 'MacBook Pro chip M3, màn hình Liquid Retina XDR, pin trâu.',
                'price' => 45000000,
                'compare_price' => 47990000,
                'is_active' => true,
                'is_featured' => false,
                'views' => 0,
                'meta' => json_encode(['brand' => 'Apple', 'ram' => '32GB']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
