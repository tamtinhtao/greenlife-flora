<?php

namespace App\Http\Controllers;

use App\Models\ProductView;
use Illuminate\Support\Facades\Auth;

class ProductViewController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SẢN PHẨM ĐÃ XEM
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $productViews = ProductView::where(
            'user_id',
            Auth::id()
        )
            ->with([
                'product.category'
            ])
            ->orderByDesc('viewed_at')
            ->get();

        return view(
            'product-history.index',
            compact('productViews')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | XÓA TOÀN BỘ LỊCH SỬ
    |--------------------------------------------------------------------------
    */
    public function clear()
    {
        ProductView::where(
            'user_id',
            Auth::id()
        )->delete();

        return back()->with(
            'success',
            'Đã xóa lịch sử sản phẩm đã xem.'
        );
    }
}