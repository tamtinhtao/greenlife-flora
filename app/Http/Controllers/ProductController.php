<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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


        /*
        |--------------------------------------------------------------------------
        | LỌC THEO DANH MỤC
        |--------------------------------------------------------------------------
        */
        if ($request->filled('category_id')) {

            $query->where(
                'category_id',
                $request->category_id
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TÌM KIẾM THEO TÊN
        |--------------------------------------------------------------------------
        */
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
            compact(
                'products',
                'categories'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CHI TIẾT SẢN PHẨM
    |--------------------------------------------------------------------------
    */
    public function show($slug)
    {
        /*
        |--------------------------------------------------------------------------
        | LẤY SẢN PHẨM
        |--------------------------------------------------------------------------
        */

        $product = Product::with('category')
            ->where(
                'slug',
                $slug
            )
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | LƯU LỊCH SỬ XEM
        |--------------------------------------------------------------------------
        | Chỉ lưu khi tài khoản là USER.
        |--------------------------------------------------------------------------
        */

        if (
            Auth::check()
            &&
            Auth::user()->role === 'user'
        ) {

            $productView =
                ProductView::firstOrNew([
                    'user_id' =>
                        Auth::id(),

                    'product_id' =>
                        $product->id,
                ]);


            /*
            |--------------------------------------------------------------------------
            | ĐÃ XEM TRƯỚC ĐÓ
            |--------------------------------------------------------------------------
            */

            if ($productView->exists) {

                $productView->view_count =
                    $productView->view_count + 1;

            } else {

                /*
                |--------------------------------------------------------------------------
                | LẦN ĐẦU XEM
                |--------------------------------------------------------------------------
                */

                $productView->view_count = 1;
            }


            $productView->viewed_at =
                now();


            $productView->save();
        }


        /*
        |--------------------------------------------------------------------------
        | GỢI Ý SẢN PHẨM
        |--------------------------------------------------------------------------
        */

        $favoriteCategoryId = null;


        /*
        |--------------------------------------------------------------------------
        | TÌM DANH MỤC USER QUAN TÂM NHẤT
        |--------------------------------------------------------------------------
        */

        if (
            Auth::check()
            &&
            Auth::user()->role === 'user'
        ) {

            $favoriteCategory =
                ProductView::query()

                    ->where(
                        'product_views.user_id',
                        Auth::id()
                    )

                    ->join(
                        'products',
                        'products.id',
                        '=',
                        'product_views.product_id'
                    )

                    ->select(
                        'products.category_id'
                    )

                    ->selectRaw(
                        '
                        SUM(product_views.view_count)
                        AS interest_score
                        '
                    )

                    ->groupBy(
                        'products.category_id'
                    )

                    ->orderByDesc(
                        'interest_score'
                    )

                    ->first();


            if ($favoriteCategory) {

                $favoriteCategoryId =
                    $favoriteCategory
                        ->category_id;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | NẾU USER CHƯA CÓ LỊCH SỬ
        |--------------------------------------------------------------------------
        | Dùng danh mục của sản phẩm hiện tại.
        |--------------------------------------------------------------------------
        */

        $recommendCategoryId =
            $favoriteCategoryId
            ??
            $product->category_id;


        /*
        |--------------------------------------------------------------------------
        | LẤY SẢN PHẨM CÙNG DANH MỤC
        |--------------------------------------------------------------------------
        */

        $recommendedProducts =
            Product::with('category')

                ->where(
                    'category_id',
                    $recommendCategoryId
                )

                ->where(
                    'id',
                    '!=',
                    $product->id
                )

                ->where(
                    'stock',
                    '>',
                    0
                )

                ->latest()

                ->take(4)

                ->get();


        /*
        |--------------------------------------------------------------------------
        | NẾU CHƯA ĐỦ 4 SẢN PHẨM
        |--------------------------------------------------------------------------
        | Bổ sung sản phẩm còn hàng ở danh mục khác.
        |--------------------------------------------------------------------------
        */

        if (
            $recommendedProducts->count()
            < 4
        ) {

            $remaining =
                4
                -
                $recommendedProducts->count();


            $extraProducts =
                Product::with('category')

                    ->where(
                        'id',
                        '!=',
                        $product->id
                    )

                    ->whereNotIn(
                        'id',
                        $recommendedProducts
                            ->pluck('id')
                            ->all()
                    )

                    ->where(
                        'stock',
                        '>',
                        0
                    )

                    ->latest()

                    ->take(
                        $remaining
                    )

                    ->get();


            $recommendedProducts =
                $recommendedProducts
                    ->concat(
                        $extraProducts
                    );
        }


        /*
        |--------------------------------------------------------------------------
        | TRẢ VỀ TRANG CHI TIẾT
        |--------------------------------------------------------------------------
        */

        return view(
            'products.show',
            compact(
                'product',
                'recommendedProducts'
            )
        );
    }
}