<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | TRANG CHỦ KHÁCH HÀNG
    |--------------------------------------------------------------------------
    */
    public function home(Request $request)
    {
        $categories = Category::all();

        $query = Product::with('category');

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where(
                'category_id',
                $request->category_id
            );
        }

        // Tìm kiếm theo tên
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(
                'name',
                'LIKE',
                "%{$search}%"
            );
        }

        $products = $query
            ->latest()
            ->get();

        return view(
            'home',
            compact('products', 'categories')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CHI TIẾT SẢN PHẨM CHO KHÁCH
    |--------------------------------------------------------------------------
    */
    public function show($slug)
    {
        $product = Product::with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        return view(
            'products.show',
            compact('product')
        );
    }
}