<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'username' => 'admin',
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'phone' => '0900000000',
                'role_id' => 1, // Admin
                'is_active' => true,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'vendor',
                'name' => 'Vendor User',
                'email' => 'vendor@example.com',
                'password' => Hash::make('password'),
                'phone' => '0900000001',
                'role_id' => 2, // Vendor
                'is_active' => true,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'customer',
                'name' => 'Customer User',
                'email' => 'customer@example.com',
                'password' => Hash::make('password'),
                'phone' => '0900000002',
                'role_id' => 3, // Customer
                'is_active' => true,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
