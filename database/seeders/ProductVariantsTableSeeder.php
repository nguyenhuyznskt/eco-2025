<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductVariantsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('product_variants')->insert([
            [
                'product_id' => 1,
                'sku' => 'IP15PM-256GB',
                'attributes' => json_encode(['storage' => '256GB', 'color' => 'Titanium']),
                'price' => 31000000,
                'compare_price' => 33000000,
                'weight' => 0.23, // kg
                'length' => 15.9, // cm
                'width' => 7.6,
                'height' => 0.8,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 2,
                'sku' => 'MBPM3-16GB',
                'attributes' => json_encode(['ram' => '16GB', 'chip' => 'M3']),
                'price' => 47000000,
                'compare_price' => 50000000,
                'weight' => 1.4, // kg
                'length' => 30.4,
                'width' => 21.2,
                'height' => 1.6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
