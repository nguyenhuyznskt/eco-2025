<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'vendor',
                'display_name' => 'Vendor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'user',
                'display_name' => 'User',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
