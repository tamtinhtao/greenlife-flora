<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            'Cây phong thủy',

            'Cây để bàn',

            'Sen đá & Hoa tươi',

        ];


        foreach ($categories as $name) {

            Category::firstOrCreate([
                'name' => $name,
            ]);

        }


        $this->command?->info(
            'Đã tạo 3 danh mục GreenLife Flora.'
        );
    }
}