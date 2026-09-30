<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [

            [
                'name' => 'Cây Kim Tiền',
                'category' => 'Cây phong thủy',
                'price' => 250000,
                'stock' => 30,
                'image' => 'uploads/products/1786412181.jpg',
            ],

            [
                'name' => 'Cây Kim Ngân',
                'category' => 'Cây phong thủy',
                'price' => 320000,
                'stock' => 25,
                'image' => 'uploads/products/1786412192.jpg',
            ],

            [
                'name' => 'Cây Phát Tài',
                'category' => 'Cây phong thủy',
                'price' => 280000,
                'stock' => 20,
                'image' => 'uploads/products/1786412473.jpg',
            ],

            [
                'name' => 'Cây Trầu Bà',
                'category' => 'Cây để bàn',
                'price' => 120000,
                'stock' => 40,
                'image' => 'uploads/products/1786412517.jpg',
            ],

            [
                'name' => 'Cây Lưỡi Hổ Mini',
                'category' => 'Cây để bàn',
                'price' => 150000,
                'stock' => 35,
                'image' => 'uploads/products/1786412576.jpg',
            ],

            [
                'name' => 'Cây Lan Ý',
                'category' => 'Cây để bàn',
                'price' => 180000,
                'stock' => 28,
                'image' => 'uploads/products/1786412646.jpg',
            ],

            [
                'name' => 'Sen Đá Nâu',
                'category' => 'Sen đá & Hoa tươi',
                'price' => 65000,
                'stock' => 50,
                'image' => 'uploads/products/1786412700.jpg',
            ],

            [
                'name' => 'Sen Đá Phật Bà',
                'category' => 'Sen đá & Hoa tươi',
                'price' => 75000,
                'stock' => 45,
                'image' => 'uploads/products/1786412765.jpg',
            ],

            [
                'name' => 'Hoa Hồng Chậu Mini',
                'category' => 'Sen đá & Hoa tươi',
                'price' => 190000,
                'stock' => 22,
                'image' => 'uploads/products/1786417439.jpg',
            ],

            [
                'name' => 'Hoa Đồng Tiền',
                'category' => 'Sen đá & Hoa tươi',
                'price' => 160000,
                'stock' => 26,
                'image' => 'uploads/products/1786421325.jpg',
            ],

        ];


        foreach ($products as $data) {

            $category = Category::where(
                'name',
                $data['category']
            )->firstOrFail();


            Product::firstOrCreate(

                [
                    'name' => $data['name'],
                    'category_id' => $category->id,
                ],

                [
                    'slug' =>
                        Str::slug($data['name']),

                    'price' =>
                        $data['price'],

                    'stock' =>
                        $data['stock'],

                    'image' =>
                        $data['image'],
                ]

            );
        }


        $this->command?->info(
            'Đã tạo 10 sản phẩm mẫu GreenLife Flora.'
        );
    }
}