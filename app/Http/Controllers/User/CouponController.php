<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CouponUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ÁP DỤNG VOUCHER
    |--------------------------------------------------------------------------
    */

    public function apply(Request $request)
    {
        $request->validate([
            'code' =>
                'required|string|max:50',
        ]);


        /*
        |--------------------------------------------------------------------------
        | GIỎ HÀNG ĐANG CHECKOUT
        |--------------------------------------------------------------------------
        */

        $cart =
            session()->get(
                'checkout_cart',
                []
            );


        if (empty($cart)) {

            return response()->json(
                [
                    'success' => false,

                    'message' =>
                        'Phiên thanh toán không còn sản phẩm.',
                ],
                422
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TÍNH TIỀN HÀNG
        |--------------------------------------------------------------------------
        */

        $subtotal =
            collect($cart)->sum(
                fn ($item) =>
                    $item['price']
                    *
                    $item['quantity']
            );


        /*
        |--------------------------------------------------------------------------
        | CHUẨN HÓA CODE
        |--------------------------------------------------------------------------
        */

        $code =
            strtoupper(
                trim(
                    $request->code
                )
            );


        /*
        |--------------------------------------------------------------------------
        | TÌM VOUCHER
        |--------------------------------------------------------------------------
        */

        $coupon =
            Coupon::where(
                'code',
                $code
            )->first();


        if (!$coupon) {

            return response()->json(
                [
                    'success' => false,

                    'message' =>
                        'Mã voucher không tồn tại.',
                ],
                422
            );
        }


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA USER ĐÃ DÙNG CHƯA
        |--------------------------------------------------------------------------
        */

        $alreadyUsed =
            CouponUsage::where(
                'coupon_id',
                $coupon->id
            )
                ->where(
                    'user_id',
                    Auth::id()
                )
                ->exists();


        if ($alreadyUsed) {

            return response()->json(
                [
                    'success' => false,

                    'message' =>
                        'Bạn đã sử dụng voucher '
                        .
                        $coupon->code
                        .
                        ' trước đó.',
                ],
                422
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TRẠNG THÁI VOUCHER
        |--------------------------------------------------------------------------
        */

        if (
            $coupon->status
            !== 'active'
        ) {

            return response()->json(
                [
                    'success' => false,

                    'message' =>
                        'Voucher hiện đang tạm ngừng.',
                ],
                422
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CHƯA ĐẾN NGÀY BẮT ĐẦU
        |--------------------------------------------------------------------------
        */

        if (
            $coupon->starts_at
            &&
            now()->lt(
                $coupon->starts_at
            )
        ) {

            return response()->json(
                [
                    'success' => false,

                    'message' =>
                        'Voucher chưa đến thời gian sử dụng.',
                ],
                422
            );
        }


        /*
        |--------------------------------------------------------------------------
        | HẾT HẠN
        |--------------------------------------------------------------------------
        */

        if (
            $coupon->expires_at
            &&
            now()->gt(
                $coupon->expires_at
            )
        ) {

            return response()->json(
                [
                    'success' => false,

                    'message' =>
                        'Voucher đã hết hạn.',
                ],
                422
            );
        }


        /*
        |--------------------------------------------------------------------------
        | HẾT LƯỢT
        |--------------------------------------------------------------------------
        */

        if (
            $coupon->usage_limit !== null
            &&
            $coupon->used_count
            >=
            $coupon->usage_limit
        ) {

            return response()->json(
                [
                    'success' => false,

                    'message' =>
                        'Voucher đã hết lượt sử dụng.',
                ],
                422
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ĐƠN CHƯA ĐỦ GIÁ TRỊ TỐI THIỂU
        |--------------------------------------------------------------------------
        */

        if (
            $subtotal
            <
            (float)
            $coupon->min_order_amount
        ) {

            return response()->json(
                [
                    'success' => false,

                    'message' =>
                        'Đơn hàng phải đạt tối thiểu '
                        .
                        number_format(
                            $coupon
                                ->min_order_amount
                        )
                        .
                        'đ để sử dụng voucher này.',
                ],
                422
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TÍNH TIỀN GIẢM
        |--------------------------------------------------------------------------
        */

        $discount =
            (int) round(
                $coupon
                    ->calculateDiscount(
                        (float) $subtotal
                    )
            );


        if ($discount <= 0) {

            return response()->json(
                [
                    'success' => false,

                    'message' =>
                        'Voucher không hợp lệ với đơn hàng này.',
                ],
                422
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LƯU VOUCHER TẠM TRONG SESSION
        |--------------------------------------------------------------------------
        |
        | Chưa tạo CouponUsage ở đây.
        |
        | Vì user mới chỉ bấm Áp dụng, chưa chắc đã đặt hàng.
        | CouponUsage chỉ được tạo khi Order được tạo thành công.
        |--------------------------------------------------------------------------
        */

        session()->put(
            'coupon_code',
            $coupon->code
        );

        session()->put(
            'coupon_discount',
            $discount
        );


        return response()->json([
            'success' => true,

            'code' =>
                $coupon->code,

            'discount' =>
                $discount,

            'message' =>
                'Áp dụng voucher '
                .
                $coupon->code
                .
                ' thành công.',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | BỎ VOUCHER
    |--------------------------------------------------------------------------
    */

    public function remove()
    {
        session()->forget([
            'coupon_code',
            'coupon_discount',
        ]);


        return response()->json([
            'success' => true,

            'message' =>
                'Đã bỏ voucher.',
        ]);
    }
}