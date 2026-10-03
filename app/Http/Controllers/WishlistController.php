<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DANH SÁCH YÊU THÍCH
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $wishlists = Wishlist::where(
            'user_id',
            Auth::id()
        )
            ->with([
                'product.category'
            ])
            ->latest()
            ->get();

        return view(
            'wishlist.index',
            compact('wishlists')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | THÊM VÀO YÊU THÍCH
    |--------------------------------------------------------------------------
    */
    public function store(
        Request $request,
        Product $product
    ) {
        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ]);

        if ($request->expectsJson()) {

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào danh sách yêu thích.',
                'wishlist_count' =>
                    Wishlist::where(
                        'user_id',
                        Auth::id()
                    )->count(),
            ]);
        }

        return back()->with(
            'success',
            '❤️ Đã thêm sản phẩm vào danh sách yêu thích.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | XÓA KHỎI YÊU THÍCH
    |--------------------------------------------------------------------------
    */
    public function destroy(
        Request $request,
        Product $product
    ) {
        Wishlist::where(
            'user_id',
            Auth::id()
        )
            ->where(
                'product_id',
                $product->id
            )
            ->delete();

        if ($request->expectsJson()) {

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa khỏi danh sách yêu thích.',
                'wishlist_count' =>
                    Wishlist::where(
                        'user_id',
                        Auth::id()
                    )->count(),
            ]);
        }

        return back()->with(
            'success',
            'Đã xóa sản phẩm khỏi danh sách yêu thích.'
        );
    }
}