<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

// import các Seeder con
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Database\Seeders\CategoriesTableSeeder;
use Database\Seeders\VendorsTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            UsersTableSeeder::class,
            CategoriesTableSeeder::class,
            VendorsTableSeeder::class,
            ProductsTableSeeder::class,
            ProductVariantsTableSeeder::class,
            InventoriesTableSeeder::class,
            ProductImagesTableSeeder::class,
        ]);
        
    }
}
