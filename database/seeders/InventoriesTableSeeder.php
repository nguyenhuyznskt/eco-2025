<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InventoriesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('inventories')->insert([
            [
                'product_variant_id' => 1,
                'qty_available' => 50,
                'qty_reserved' => 0,
                'qty_sold' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_variant_id' => 2,
                'qty_available' => 30,
                'qty_reserved' => 0,
                'qty_sold' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
