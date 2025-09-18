<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VendorsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vendors')->insert([
            [
                'user_id'    => 1, // gắn cho Admin user
                'shop_name'  => 'Cửa hàng Admin',
                'slug'       => Str::slug('Cửa hàng Admin'),
                'phone'      => '0900000000',
                'address'    => 'Hà Nội',
                'description'=> 'Shop chính thức của Admin',
                'status'     => 'active', // override luôn pending
                'settings'   => json_encode(['shipping' => 'GHN', 'support' => '24/7']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
