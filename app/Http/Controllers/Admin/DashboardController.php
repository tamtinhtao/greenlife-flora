<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | CÁC SỐ LIỆU TỔNG QUAN
        |--------------------------------------------------------------------------
        */

        $totalOrders =
            Order::count();

        $totalProducts =
            Product::count();

        $totalCategories =
            Category::count();

        $totalUsers =
            User::where(
                'role',
                'user'
            )->count();


        /*
        |--------------------------------------------------------------------------
        | 5 ĐƠN MỚI NHẤT
        |--------------------------------------------------------------------------
        */

        $latestOrders =
            Order::with([
                'user'
            ])
                ->latest()
                ->take(5)
                ->get();


        return view(
            'admin.dashboard',
            compact(
                'totalOrders',
                'totalProducts',
                'totalCategories',
                'totalUsers',
                'latestOrders'
            )
        );
    }
}