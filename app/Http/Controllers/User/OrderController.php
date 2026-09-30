<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentTransaction;
use App\Services\GHNService;
use App\Services\GHNOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // ==========================================
    // XỬ LÝ ĐẶT HÀNG / THANH TOÁN
    // ==========================================
    public function processPayment(
        Request $request,
        GHNService $ghn,
        GHNOrderService $ghnOrders
    ) {
        /*
        |--------------------------------------------------------------------------
        | 1. KIỂM TRA DỮ LIỆU
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'customer_name' => 'required|string|max:100',

            'customer_phone' => [
                'required',
                'regex:/^(03|05|07|08|09)[0-9]{8}$/',
            ],

            'shipping_address' => 'required|string|max:255',

            'to_district_id' => 'required|integer',

            'to_ward_code' => 'required|string',

            'payment_method' => 'required|in:cod,momo',

            'note' => 'nullable|string|max:1000',
        ]);


        /*
        |--------------------------------------------------------------------------
        | 2. LẤY GIỎ CHECKOUT CHUNG
        |--------------------------------------------------------------------------
        |
        | Dù là:
        | - Mua ngay 1 sản phẩm
        | - Chọn 1 sản phẩm trong giỏ
        | - Chọn nhiều sản phẩm trong giỏ
        |
        | đều đọc từ checkout_cart.
        |--------------------------------------------------------------------------
        */

        $cart = session()->get(
            'checkout_cart',
            []
        );

        $checkoutContext = session()->get(
            'checkout_context',
            []
        );

        $mode = $checkoutContext['mode'] ?? 'cart';


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA GIỎ CHECKOUT
        |--------------------------------------------------------------------------
        */

        if (empty($cart)) {
            return redirect()
                ->route('cart.index')
                ->with(
                    'error',
                    'Phiên thanh toán không còn sản phẩm. Vui lòng chọn lại sản phẩm.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | 3. TÍNH TIỀN HÀNG
        |--------------------------------------------------------------------------
        */

        $subtotal = collect($cart)->sum(
            fn ($item) =>
                $item['price'] * $item['quantity']
        );


        /*
        |--------------------------------------------------------------------------
        | 4. TÍNH TỔNG KHỐI LƯỢNG
        |--------------------------------------------------------------------------
        */

        $totalWeight = collect($cart)->sum(
            fn ($item) =>
                $ghn->productWeight()
                * (int) $item['quantity']
        );


        /*
        |--------------------------------------------------------------------------
        | 5. TÍNH LẠI PHÍ SHIP GHN TRÊN SERVER
        |--------------------------------------------------------------------------
        */

        $feeResponse = $ghn->calculateFee(
            array_merge(
                [
                    'from_district_id' =>
                        (int) config(
                            'services.ghn.from_district_id'
                        ),

                    'to_district_id' =>
                        (int) $request->to_district_id,

                    'to_ward_code' =>
                        (string) $request->to_ward_code,
                ],

                $ghn->packageParameters(
                    $totalWeight
                )
            )
        );

        $shippingFee =
            (
                isset($feeResponse['code'])
                &&
                $feeResponse['code'] == 200
            )
                ? (int) ($feeResponse['data']['total'] ?? 0)
                : 0;


        /*
        |--------------------------------------------------------------------------
        | 6. TỔNG TIỀN
        |--------------------------------------------------------------------------
        */

        $finalTotal =
            $subtotal + $shippingFee;


        /*
        |--------------------------------------------------------------------------
        | 7. TẠO ORDER + ORDER ITEM
        |--------------------------------------------------------------------------
        */

        $order = DB::transaction(
            function () use (
                $request,
                $shippingFee,
                $finalTotal,
                $cart
            ) {

                $order = Order::create([

                    'user_id' =>
                        Auth::id(),

                    'customer_name' =>
                        $request->customer_name,

                    'customer_email' =>
                        Auth::user()->email,

                    'customer_phone' =>
                        $request->customer_phone,

                    'shipping_address' =>
                        $request->shipping_address,

                    'note' =>
                        $request->note,

                    'payment_method' =>
                        $request->payment_method,

                    'total_price' =>
                        $finalTotal,

                    'status' =>
                        'pending',

                    'shipping_status' =>
                        'pending',

                    'ghn_total_fee' =>
                        $shippingFee,

                    'to_district_id' =>
                        (int) $request->to_district_id,

                    'to_ward_code' =>
                        (string) $request->to_ward_code,
                ]);


                /*
                |------------------------------------------------------------------
                | PRODUCT ID NẰM Ở KEY CỦA CART
                |------------------------------------------------------------------
                */

                foreach ($cart as $productId => $item) {
                    OrderItem::create([
                        'order_id' =>
                            $order->id,

                        'product_id' =>
                            $productId,

                        'quantity' =>
                            $item['quantity'],

                        'price' =>
                            $item['price'],
                    ]);
                }

                return $order;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | 8. THANH TOÁN MOMO
        |--------------------------------------------------------------------------
        |
        | Không xóa sản phẩm khỏi giỏ chính tại đây.
        | Chỉ ghi nhớ những sản phẩm đang thanh toán.
        | Khi MoMo báo thành công, MomoController mới xóa chúng.
        |--------------------------------------------------------------------------
        */

        if ($request->payment_method === 'momo') {

            PaymentTransaction::create([
                'order_id' =>
                    $order->id,

                'gateway' =>
                    'momo',

                'amount' =>
                    $order->total_price,

                'status' =>
                    'pending',
            ]);


            /*
            |----------------------------------------------------------------------
            | GHI NHỚ CÁC PRODUCT ĐANG THANH TOÁN
            |----------------------------------------------------------------------
            */

            session()->put(
                'momo_purchase_context.' . $order->id,
                [
                    'mode' =>
                        $mode,

                    'product_ids' =>
                        array_keys($cart),
                ]
            );


            /*
            |----------------------------------------------------------------------
            | ORDER ĐÃ ĐƯỢC TẠO, KHÔNG CẦN GIỮ CHECKOUT TẠM
            |----------------------------------------------------------------------
            |
            | Giỏ chính cart vẫn còn nguyên.
            | Nếu MoMo thất bại, sản phẩm vẫn ở giỏ.
            |----------------------------------------------------------------------
            */

            session()->forget('checkout_cart');
            session()->forget('checkout_context');


            return redirect()->route(
                'user.orders.momo.start',
                $order
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 9. THANH TOÁN COD
        |--------------------------------------------------------------------------
        */

        PaymentTransaction::create([
            'order_id' =>
                $order->id,

            'gateway' =>
                'cod',

            'amount' =>
                $order->total_price,

            'status' =>
                'pending',

            'message' =>
                'Thanh toán khi nhận hàng',
        ]);


        /*
        |--------------------------------------------------------------------------
        | COD ĐÃ ĐẶT HÀNG -> XÓA CHỈ SẢN PHẨM VỪA MUA
        |--------------------------------------------------------------------------
        */

        $this->removePurchasedItems(
            $cart,
            $mode
        );

        session()->forget('checkout_cart');
        session()->forget('checkout_context');


        /*
        |--------------------------------------------------------------------------
        | 10. COD -> TẠO VẬN ĐƠN GHN NGAY
        |--------------------------------------------------------------------------
        */

        $order->load('items.product');

        $ghnOrderResponse =
            $ghnOrders->create($order);


        if (
            ($ghnOrderResponse['code'] ?? null) == 200
            &&
            !empty(
                $ghnOrderResponse['data']['order_code']
            )
        ) {

            $order->update([
                'status' =>
                    'cod_ordered',

                'ghn_order_code' =>
                    $ghnOrderResponse['data']['order_code'],

                'shipping_status' =>
                    'ready_to_pick',
            ]);


            return redirect()
                ->route('my-orders.index')
                ->with(
                    'success',
                    'Đặt hàng thành công! Mã vận đơn GHN: '
                    . $ghnOrderResponse['data']['order_code']
                );
        }


        /*
        |--------------------------------------------------------------------------
        | GHN TẠO VẬN ĐƠN THẤT BẠI
        |--------------------------------------------------------------------------
        |
        | Order vẫn đã được tạo thành công nên vẫn giữ trạng thái COD.
        |--------------------------------------------------------------------------
        */

        Log::error(
            'GHN COD Order Failed: ',
            $ghnOrderResponse ?? []
        );

        $order->update([
            'status' => 'cod_ordered',
            'shipping_status' => 'pending',
        ]);


        return redirect()
            ->route('my-orders.index')
            ->with(
                'warning',
                'Đặt hàng thành công nhưng chưa thể tạo vận đơn GHN tự động.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | XÓA SẢN PHẨM ĐÃ MUA KHỎI GIỎ CHÍNH
    |--------------------------------------------------------------------------
    */

    private function removePurchasedItems(
        array $purchasedCart,
        ?string $mode
    ): void {

        /*
        |--------------------------------------------------------------------------
        | MUA NGAY
        |--------------------------------------------------------------------------
        |
        | Mua ngay không lấy sản phẩm từ giỏ chính,
        | vì vậy không được xóa sản phẩm trong cart.
        |--------------------------------------------------------------------------
        */

        if ($mode === 'buy-now') {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | LẤY GIỎ CHÍNH
        |--------------------------------------------------------------------------
        */

        $mainCart = session()->get(
            'cart',
            []
        );


        /*
        |--------------------------------------------------------------------------
        | CHỈ XÓA NHỮNG PRODUCT ĐÃ MUA
        |--------------------------------------------------------------------------
        */

        foreach (
            array_keys($purchasedCart)
            as $productId
        ) {
            unset($mainCart[$productId]);
        }


        /*
        |--------------------------------------------------------------------------
        | LƯU LẠI CÁC SẢN PHẨM CHƯA MUA
        |--------------------------------------------------------------------------
        */

        session()->put(
            'cart',
            $mainCart
        );
    }
}
