<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImagesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_images')->insert([
            [
                'product_id' => 1,
                'product_variant_id' => null,
                'path' => 'iphone15.jpg',
                'alt' => 'iPhone 15 Pro Max',
                'is_primary' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 2,
                'product_variant_id' => null,
                'path' => 'macbookpro-m3.jpg',
                'alt' => 'MacBook Pro M3',
                'is_primary' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
