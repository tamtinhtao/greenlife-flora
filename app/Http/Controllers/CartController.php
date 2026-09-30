<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use App\Services\GHNOrderService;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. Hiển thị giỏ hàng
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $cart = session()->get('cart', []);

        return view('cart.index', compact('cart'));
    }


    /*
|--------------------------------------------------------------------------
| 2. Thêm sản phẩm vào giỏ hàng
|--------------------------------------------------------------------------
*/
public function addToCart(Request $request, int $id)
{
    $product = Product::findOrFail($id);

    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {

        $cart[$id]['quantity']++;

    } else {

        $cart[$id] = [
            'name' => $product->name,
            'quantity' => 1,
            'price' => $product->price,
            'image' => $product->image,
        ];
    }

    session()->put('cart', $cart);

    $cartCount = array_sum(
        array_column($cart, 'quantity')
    );

    // Nếu JavaScript gọi thì trả JSON, không redirect
    if ($request->expectsJson()) {

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm cây vào giỏ hàng!',
            'cart_count' => $cartCount,
        ]);
    }

    // Dự phòng nếu không có JavaScript
    return redirect()
        ->back()
        ->with(
            'success',
            'Đã thêm cây vào giỏ hàng!'
        );
}


/*
|--------------------------------------------------------------------------
| Mua ngay - hiển thị phiếu chọn số lượng
|--------------------------------------------------------------------------
*/
public function buyNow($id)
{
    $product = Product::findOrFail($id);

    return view('cart.buy-now', compact('product'));
}
/*
|--------------------------------------------------------------------------
| Xác nhận mua ngay
|--------------------------------------------------------------------------
*/
public function confirmBuyNow(Request $request, $id)
{
    $request->validate([
        'quantity' => 'required|integer|min:1|max:99',
    ]);

    $product = Product::findOrFail($id);

    $checkoutCart = [
        $product->id => [
            'name' => $product->name,
            'quantity' => (int) $request->quantity,
            'price' => $product->price,
            'image' => $product->image,
        ]
    ];

    /*
    |--------------------------------------------------------------------------
    | DÙNG CHUNG 1 GIỎ CHECKOUT
    |--------------------------------------------------------------------------
    */

    session()->put(
        'checkout_cart',
        $checkoutCart
    );

    session()->put(
        'checkout_context',
        [
            'mode' => 'buy-now',
            'product_ids' => [
                $product->id
            ],
        ]
    );

    return redirect()->route(
        'cart.checkout'
    );
}
    /*
|--------------------------------------------------------------------------
| Cập nhật số lượng sản phẩm trong giỏ hàng
|--------------------------------------------------------------------------
*/
public function updateQuantity(Request $request)
{
    $request->validate([
        'id' => 'required',
        'action' => 'required|in:increase,decrease',
    ]);

    $cart = session()->get('cart', []);

    $id = $request->id;

    if (!isset($cart[$id])) {

        return response()->json([
            'success' => false,
            'message' => 'Sản phẩm không tồn tại trong giỏ hàng.',
        ], 404);
    }


    if ($request->action === 'increase') {

        $cart[$id]['quantity']++;

    } elseif ($request->action === 'decrease') {

        if ($cart[$id]['quantity'] > 1) {
            $cart[$id]['quantity']--;
        }
    }


    session()->put('cart', $cart);


    $subtotal =
        $cart[$id]['price']
        * $cart[$id]['quantity'];


    $total = 0;

    foreach ($cart as $item) {

        $total +=
            $item['price']
            * $item['quantity'];
    }


    $cartCount = array_sum(
        array_column($cart, 'quantity')
    );


    return response()->json([
        'success' => true,
        'quantity' => $cart[$id]['quantity'],
        'subtotal' => $subtotal,
        'total' => $total,
        'cart_count' => $cartCount,
    ]);
}
    /*
    |--------------------------------------------------------------------------
    | 3. Xóa sản phẩm khỏi giỏ hàng
    |--------------------------------------------------------------------------
    */
    public function remove(Request $request)
{
    $cart = session()->get('cart', []);

    $id = $request->id;

    if (isset($cart[$id])) {

        unset($cart[$id]);

        session()->put('cart', $cart);
    }


    $total = 0;

    foreach ($cart as $item) {

        $total +=
            $item['price']
            * $item['quantity'];
    }


    $cartCount = array_sum(
        array_column($cart, 'quantity')
    );


    if ($request->expectsJson()) {

        return response()->json([
            'success' => true,
            'total' => $total,
            'cart_count' => $cartCount,
        ]);
    }


    return redirect()
        ->back()
        ->with(
            'success',
            'Đã xóa sản phẩm khỏi giỏ hàng!'
        );
}

/*
|--------------------------------------------------------------------------
| CHỌN SẢN PHẨM TRONG GIỎ ĐỂ THANH TOÁN
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| CHỌN SẢN PHẨM TRONG GIỎ ĐỂ THANH TOÁN
|--------------------------------------------------------------------------
*/

public function checkoutSelected(Request $request)
{
    $request->validate(
        [
            'selected_products' => 'required|array|min:1',
        ],
        [
            'selected_products.required' =>
                'Vui lòng chọn ít nhất một sản phẩm để thanh toán.',

            'selected_products.min' =>
                'Vui lòng chọn ít nhất một sản phẩm để thanh toán.',
        ]
    );

    $mainCart = session()->get(
        'cart',
        []
    );

    $checkoutCart = [];

    foreach (
        $request->selected_products
        as $productId
    ) {

        if (isset($mainCart[$productId])) {

            $checkoutCart[$productId] =
                $mainCart[$productId];
        }
    }


    if (empty($checkoutCart)) {

        return redirect()
            ->route('cart.index')
            ->with(
                'error',
                'Không tìm thấy sản phẩm đã chọn.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DÙNG CHUNG CHECKOUT_CART
    |--------------------------------------------------------------------------
    */

    session()->put(
        'checkout_cart',
        $checkoutCart
    );

    session()->put(
        'checkout_context',
        [
            'mode' => 'selected',
            'product_ids' =>
                array_keys($checkoutCart),
        ]
    );


    return redirect()->route(
        'cart.checkout'
    );
}
    /*
    |--------------------------------------------------------------------------
    | 4. Hiển thị trang thanh toán
    |--------------------------------------------------------------------------
    */
    /*
|--------------------------------------------------------------------------
| TRANG CHECKOUT
|--------------------------------------------------------------------------
*/

public function checkout(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | LUÔN ĐỌC GIỎ CHECKOUT CHUNG
    |--------------------------------------------------------------------------
    */

    $cart = session()->get(
        'checkout_cart',
        []
    );

    $context = session()->get(
        'checkout_context',
        []
    );


    /*
    |--------------------------------------------------------------------------
    | HỖ TRỢ LUỒNG CHECKOUT CŨ
    |--------------------------------------------------------------------------
    |
    | Nếu user vào /checkout trực tiếp mà chưa có checkout_cart
    | thì lấy toàn bộ cart.
    |--------------------------------------------------------------------------
    */

    if (empty($cart)) {

        $mainCart = session()->get(
            'cart',
            []
        );

        if (!empty($mainCart)) {

            $cart = $mainCart;

            $context = [
                'mode' => 'cart',
                'product_ids' =>
                    array_keys($mainCart),
            ];

            session()->put(
                'checkout_cart',
                $cart
            );

            session()->put(
                'checkout_context',
                $context
            );
        }
    }


    if (empty($cart)) {

        return redirect()
            ->route('cart.index')
            ->with(
                'error',
                'Không có sản phẩm để thanh toán.'
            );
    }


    $mode =
        $context['mode']
        ?? 'cart';


    return view(
        'cart.checkout',
        compact(
            'cart',
            'mode'
        )
    );
}

    /*
    |--------------------------------------------------------------------------
    | 5. Lưu đơn hàng vào Database
    |--------------------------------------------------------------------------
    */
   public function storeOrder(
    Request $request,
    GHNOrderService $ghnOrderService
)
{
    /*
    |--------------------------------------------------------------------------
    | 1. VALIDATE
    |--------------------------------------------------------------------------
    */

    $request->validate(
    [
        'customer_name' => 'required|string|max:255',

        'customer_phone' => [
            'required',
            'regex:/^(03|05|07|08|09)[0-9]{8}$/'
        ],

        'shipping_address' => 'required|string|max:500',

        'payment_method' =>
            'required|in:cod,bank_transfer',

        'to_district_id' =>
            'required|integer',

        'to_ward_code' =>
            'required|string',

        'ghn_total_fee' =>
            'required|integer|min:0',

        'note' =>
            'nullable|string|max:1000',
    ],

    [
        'customer_phone.required' =>
            'Vui lòng nhập số điện thoại.',

        'customer_phone.regex' =>
            'Số điện thoại không hợp lệ. Vui lòng nhập số gồm 10 chữ số và bắt đầu bằng 03, 05, 07, 08 hoặc 09.',
    ]
);


    /*
    |--------------------------------------------------------------------------
    | 2. XÁC ĐỊNH ĐANG MUA NGAY HAY MUA TỪ GIỎ
    |--------------------------------------------------------------------------
    */

    $mode = $request->input('mode');


    if ($mode === 'buy-now') {

        $cart = session()->get(
            'buy_now_cart',
            []
        );

    } else {

        $cart = session()->get(
            'cart',
            []
        );
    }


    if (empty($cart)) {

        return redirect()
            ->route('home')
            ->with(
                'error',
                'Không có sản phẩm để đặt hàng.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | 3. TÍNH TIỀN HÀNG
    |--------------------------------------------------------------------------
    */

    $productTotal = 0;


    foreach ($cart as $item) {

        $productTotal +=
            $item['price']
            * $item['quantity'];
    }


    /*
    |--------------------------------------------------------------------------
    | 4. PHÍ SHIP GHN
    |--------------------------------------------------------------------------
    */

    $shippingFee =
        (int) $request->ghn_total_fee;


    /*
    |--------------------------------------------------------------------------
    | 5. TỔNG KHÁCH PHẢI THANH TOÁN
    |--------------------------------------------------------------------------
    */

    $finalTotal =
        $productTotal
        + $shippingFee;


    try {

        DB::beginTransaction();


        /*
        |--------------------------------------------------------------------------
        | 6. TẠO ORDER TRONG DATABASE
        |--------------------------------------------------------------------------
        */

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

            'status' =>
                'pending',


            /*
            |--------------------------------------------------------------------------
            | GHN
            |--------------------------------------------------------------------------
            */

            'shipping_status' =>
                'not_shipped',

            'ghn_total_fee' =>
                $shippingFee,

            'to_district_id' =>
                $request->to_district_id,

            'to_ward_code' =>
                $request->to_ward_code,


            /*
            |--------------------------------------------------------------------------
            | TIỀN HÀNG + PHÍ SHIP
            |--------------------------------------------------------------------------
            */

            'total_price' =>
                $finalTotal,
        ]);


        /*
        |--------------------------------------------------------------------------
        | 7. TẠO ORDER ITEMS
        |--------------------------------------------------------------------------
        */

        foreach ($cart as $id => $item) {

            OrderItem::create([

                'order_id' =>
                    $order->id,

                'product_id' =>
                    $id,

                'quantity' =>
                    $item['quantity'],

                'price' =>
                    $item['price'],
            ]);
        }


        DB::commit();


    } catch (\Throwable $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with(
                'error',
                'Không thể tạo đơn hàng: '
                . $e->getMessage()
            );
    }


    /*
    |--------------------------------------------------------------------------
    | 8. LOAD SẢN PHẨM ĐỂ GHNOrderService SỬ DỤNG
    |--------------------------------------------------------------------------
    */

    $order->load(
        'items.product'
    );


    /*
    |--------------------------------------------------------------------------
    | 9. GỌI GHN TẠO VẬN ĐƠN
    |--------------------------------------------------------------------------
    |
    | Nếu COD -> chưa thanh toán -> GHN thu tiền
    | Nếu chuyển khoản -> hiện tại vẫn xem là chưa thanh toán.
    |
    */

    $isPaid = false;


    $ghnResponse =
        $ghnOrderService->create(
            $order,
            $isPaid
        );


    /*
    |--------------------------------------------------------------------------
    | 10. GHN TẠO VẬN ĐƠN THÀNH CÔNG
    |--------------------------------------------------------------------------
    */

    if (
        ($ghnResponse['code'] ?? null) === 200
        && !empty($ghnResponse['data'])
    ) {

        $order->update([

            'ghn_order_code' =>
                $ghnResponse['data']['order_code']
                ?? null,

            'shipping_status' =>
                'ready_to_pick',

            'ghn_total_fee' =>
                $ghnResponse['data']['total_fee']
                ?? $shippingFee,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 11. XÓA SESSION SAU KHI ĐẶT HÀNG
    |--------------------------------------------------------------------------
    */

    if ($mode === 'buy-now') {

        session()->forget(
            'buy_now_cart'
        );

    } else {

        session()->forget(
            'cart'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 12. THÔNG BÁO
    |--------------------------------------------------------------------------
    */

    if (($ghnResponse['code'] ?? null) === 200) {

        return redirect()
            ->route('my-orders.index')
            ->with(
                'success',
                'Đặt hàng và tạo vận đơn GHN thành công.'
            );
    }


    return redirect()
    ->route('my-orders.index')
    ->with(
        'success',
        'Đơn hàng đã được tạo, nhưng chưa tạo được vận đơn GHN. Lỗi: '
        . ($ghnResponse['message'] ?? 'Không xác định')
        . ' '
        . ($ghnResponse['code_message'] ?? '')
    );
}

}