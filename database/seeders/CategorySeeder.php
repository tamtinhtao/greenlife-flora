<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::insert([
            ['name' => 'Cây Để Bàn', 'slug' => 'cay-de-ban', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cây Phong Thủy', 'slug' => 'cay-phong-thuy', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sen Đá & Xương Rồng', 'slug' => 'sen-da-xuong-rong', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hoa Tươi & Chậu Hoa', 'slug' => 'hoa-tuoi-chau-hoa', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}