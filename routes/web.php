<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\AccountController;

use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ChatController as AdminChatController;
use App\Http\Controllers\Admin\FinanceController;
use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\User\GHNController;
use App\Http\Controllers\User\OrderController as UserPaymentOrderController;
use App\Http\Controllers\User\MomoController;
use App\Http\Controllers\User\ChatController as UserChatController;


/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
| Không cần đăng nhập
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [ProductController::class, 'home']
)->name('home');


Route::get(
    '/product/{slug}',
    [ProductController::class, 'show']
)->name('products.show_detail');


/*
|--------------------------------------------------------------------------
| GUEST
|--------------------------------------------------------------------------
| Chỉ người chưa đăng nhập
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/login',
        [AuthController::class, 'showLogin']
    )->name('login');


    Route::post(
        '/login',
        [AuthController::class, 'login']
    )->name('login.post');


    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/register',
        [AuthController::class, 'showRegister']
    )->name('register');


    Route::post(
        '/register',
        [AuthController::class, 'register']
    )->name('register.post');
});


/*
|--------------------------------------------------------------------------
| EMAIL VERIFICATION
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Trang thông báo yêu cầu xác thực email
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/email/verify',
        function () {

            return view(
                'auth.verify-email'
            );
        }
    )->name('verification.notice');


    /*
    |--------------------------------------------------------------------------
    | User bấm link xác thực trong Gmail
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/email/verify/{id}/{hash}',
        function (
            EmailVerificationRequest $request
        ) {

            $request->fulfill();

            return redirect()
                ->route('home')
                ->with(
                    'success',
                    'Xác thực email thành công.'
                );
        }
    )
        ->middleware('signed')
        ->name('verification.verify');


    /*
    |--------------------------------------------------------------------------
    | Gửi lại email xác thực
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/email/verification-notification',
        function (
            Request $request
        ) {

            $request
                ->user()
                ->sendEmailVerificationNotification();

            return back()->with(
                'success',
                'Đã gửi lại email xác thực.'
            );
        }
    )
        ->middleware('throttle:6,1')
        ->name('verification.send');
});


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post(
    '/logout',
    [AuthController::class, 'logout']
)
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| MOMO CALLBACK + IPN
|--------------------------------------------------------------------------
|
| QUAN TRỌNG:
|
| Hai route này phải nằm NGOÀI:
| - auth
| - verified
| - role:user
| - admin
|
| Vì MoMo cần gọi trực tiếp về website.
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| MOMO IPN
|--------------------------------------------------------------------------
| Server MoMo gọi trực tiếp về Laravel
|--------------------------------------------------------------------------
*/

Route::post(
    '/payment/momo/ipn',
    [MomoController::class, 'ipn']
)->name('payment.momo.ipn');


/*
|--------------------------------------------------------------------------
| MOMO CALLBACK
|--------------------------------------------------------------------------
| Trình duyệt được MoMo chuyển trở lại website
|--------------------------------------------------------------------------
*/

Route::get(
    '/payment/momo/callback',
    [MomoController::class, 'callback']
)->name('user.payment.momo.callback');


/*
|--------------------------------------------------------------------------
| USER
|--------------------------------------------------------------------------
| Chỉ user đã:
| - đăng nhập
| - xác thực email
| - role = user
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'verified',
    'role:user',
])->group(function () {


    /*
    |--------------------------------------------------------------------------
    | PAYMENT
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/payment/process',
        [
            UserPaymentOrderController::class,
            'processPayment'
        ]
    )->name('user.payment.process');


    /*
    |--------------------------------------------------------------------------
    | MOMO
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Bắt đầu thanh toán MoMo
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/orders/{order}/start-momo',
        [
            MomoController::class,
            'start'
        ]
    )->name('user.orders.momo.start');


    /*
    |--------------------------------------------------------------------------
    | Thanh toán lại bằng MoMo
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/orders/{order}/pay/momo',
        [
            MomoController::class,
            'payAgain'
        ]
    )->name('user.orders.momo.pay');


    /*
    |--------------------------------------------------------------------------
    | CART
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Xem giỏ hàng
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/cart',
        [
            CartController::class,
            'index'
        ]
    )->name('cart.index');


    /*
    |--------------------------------------------------------------------------
    | Cập nhật số lượng
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/cart/update-quantity',
        [
            CartController::class,
            'updateQuantity'
        ]
    )->name('cart.updateQuantity');


    /*
    |--------------------------------------------------------------------------
    | Thêm vào giỏ hàng
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/add-to-cart/{id}',
        [
            CartController::class,
            'addToCart'
        ]
    )->name('cart.add');


    /*
    |--------------------------------------------------------------------------
    | MUA NGAY
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Trang chọn số lượng mua ngay
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/buy-now/{id}',
        [
            CartController::class,
            'buyNow'
        ]
    )->name('cart.buyNow');


    /*
    |--------------------------------------------------------------------------
    | Xác nhận số lượng mua ngay
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/buy-now/{id}',
        [
            CartController::class,
            'confirmBuyNow'
        ]
    )->name('cart.buyNow.confirm');


    /*
    |--------------------------------------------------------------------------
    | Xóa sản phẩm khỏi giỏ
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/remove-from-cart',
        [
            CartController::class,
            'remove'
        ]
    )->name('cart.remove');


    /*
    |--------------------------------------------------------------------------
    | CHECKOUT
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Hiển thị trang checkout
    |--------------------------------------------------------------------------
    */
    Route::post(
    '/cart/checkout-selected',
    [CartController::class, 'checkoutSelected']
)->name('cart.checkoutSelected');

    Route::get(
        '/checkout',
        [
            CartController::class,
            'checkout'
        ]
    )->name('cart.checkout');


    /*
    |--------------------------------------------------------------------------
    | Route đặt hàng CŨ
    |--------------------------------------------------------------------------
    |
    | Hiện checkout mới đang gửi sang:
    |
    | user.payment.process
    |
    | Route này tạm thời giữ lại để không ảnh hưởng code cũ.
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/checkout',
        [
            CartController::class,
            'storeOrder'
        ]
    )->name('cart.storeOrder');


   

    /*
|--------------------------------------------------------------------------
| GHN LOCATIONS + SHIPPING FEE
|--------------------------------------------------------------------------
*/

Route::prefix('locations')
    ->name('locations.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Lấy tỉnh / thành phố
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/provinces',
            [
                GHNController::class,
                'getProvinces'
            ]
        )->name('provinces');


        /*
        |--------------------------------------------------------------------------
        | Lấy quận / huyện
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/districts/{provinceId}',
            [
                GHNController::class,
                'getDistricts'
            ]
        )->name('districts');


        /*
        |--------------------------------------------------------------------------
        | Lấy phường / xã
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/wards/{districtId}',
            [
                GHNController::class,
                'getWards'
            ]
        )->name('wards');


        /*
        |--------------------------------------------------------------------------
        | Tính phí GHN
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/calculate-fee',
            [
                GHNController::class,
                'getShippingFee'
            ]
        )->name('fee');

    }); // ĐÓNG GROUP LOCATIONS Ở ĐÂY


/*
|--------------------------------------------------------------------------
| CHAT USER
|--------------------------------------------------------------------------
|
| Hai route này vẫn nằm trong middleware USER,
| nhưng KHÔNG nằm trong prefix locations.
|
*/

Route::post(
    '/user/chat/send',
    [
        UserChatController::class,
        'send'
    ]
)->name('user.chat.send');


Route::get(
    '/user/chat/messages',
    [
        UserChatController::class,
        'getMessages'
    ]
)->name('user.chat.messages');

    /*
    |--------------------------------------------------------------------------
    | ĐƠN HÀNG CỦA USER
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Danh sách đơn hàng
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/my-orders',
        [
            UserOrderController::class,
            'index'
        ]
    )->name('my-orders.index');


    /*
    |--------------------------------------------------------------------------
    | User hủy đơn hàng
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/my-orders/{order}/cancel',
        [
            UserOrderController::class,
            'cancel'
        ]
    )->name('my-orders.cancel');
});


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
| Chỉ role = admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware([
        'auth',
        'role:admin',
    ])
    ->group(function () {

        /*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get(
    '/dashboard',
    [
        DashboardController::class,
        'index'
    ]
)->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | PRODUCTS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'products',
            AdminProductController::class
        )->except([
            'show',
        ]);


        /*
        |--------------------------------------------------------------------------
        | CATEGORIES
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'categories',
            AdminCategoryController::class
        )->except([
            'show',
        ]);

        /*
        |--------------------------------------------------------------------------
        | REPORTS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/reports',
            [ReportController::class, 'index']
        )->name('reports.index');


        Route::get(
            '/reports/charts',
            [ReportController::class, 'charts']
        )->name('reports.charts');

        /*
        |--------------------------------------------------------------------------
        | ORDERS
        |--------------------------------------------------------------------------
        */


        /*
        |--------------------------------------------------------------------------
        | Danh sách đơn Admin
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/orders',
            [
                AdminOrderController::class,
                'index'
            ]
        )->name('orders.index');

        /*
|--------------------------------------------------------------------------
| FINANCE
|--------------------------------------------------------------------------
*/

Route::get(
    '/finance',
    [
        FinanceController::class,
        'index'
    ]
)->name('finance.index');


Route::get(
    '/finance/transactions',
    [
        FinanceController::class,
        'transactions'
    ]
)->name('finance.transactions');


Route::patch(
    '/finance/{order}/status',
    [
        FinanceController::class,
        'updateStatus'
    ]
)->name('finance.update-status');


        /*
        |--------------------------------------------------------------------------
        | Admin cập nhật trạng thái đơn
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/orders/{id}/status',
            [
                AdminOrderController::class,
                'updateStatus'
            ]
        )->name('orders.updateStatus');
        /*
|--------------------------------------------------------------------------
| CẬP NHẬT NHIỀU ĐƠN HÀNG
|--------------------------------------------------------------------------
*/

Route::post(
    '/orders/bulk-update',
    [AdminOrderController::class, 'bulkUpdate']
)->name('orders.bulkUpdate');
/*
|--------------------------------------------------------------------------
| ĐỒNG BỘ TRẠNG THÁI GHN
|--------------------------------------------------------------------------
*/

Route::post(
    '/orders/sync-ghn',
    [AdminOrderController::class, 'syncGhn']
)->name('orders.syncGhn');
Route::get(
    '/orders/{id}',
    [AdminOrderController::class, 'show']
)->name('orders.show');
/*
|--------------------------------------------------------------------------
| QUẢN LÝ NGƯỜI DÙNG
|--------------------------------------------------------------------------
*/

Route::resource(
    'users',
    AdminUserController::class
);
/*
|--------------------------------------------------------------------------
| CHAT ADMIN
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| TRANG CHAT ADMIN
|--------------------------------------------------------------------------
*/

Route::view(
    '/chat',
    'admin.chat.index'
)->name('chat.index');


/*
|--------------------------------------------------------------------------
| DANH SÁCH KHÁCH HÀNG ĐÃ CHAT
|--------------------------------------------------------------------------
*/

Route::get(
    '/chat/users',
    [
        AdminChatController::class,
        'getUsers'
    ]
)->name('chat.users');


/*
|--------------------------------------------------------------------------
| LỊCH SỬ CHAT VỚI USER
|--------------------------------------------------------------------------
*/

Route::get(
    '/chat/messages/{userId}',
    [
        AdminChatController::class,
        'getMessages'
    ]
)->name('chat.messages');


/*
|--------------------------------------------------------------------------
| ADMIN GỬI TIN
|--------------------------------------------------------------------------
*/

Route::post(
    '/chat/send',
    [
        AdminChatController::class,
        'send'
    ]
)->name('chat.send');


/*
|--------------------------------------------------------------------------
| ĐÓNG GROUP ADMIN
|--------------------------------------------------------------------------
*/

});


/*
|--------------------------------------------------------------------------
| TÀI KHOẢN
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {

        Route::get(
            '/account',
            [
                AccountController::class,
                'profile'
            ]
        )->name('account.profile');


        Route::put(
            '/account',
            [
                AccountController::class,
                'updateProfile'
            ]
        )->name('account.update');


        Route::get(
            '/account/change-password',
            [
                AccountController::class,
                'showChangePassword'
            ]
        )->name('account.password');


        Route::put(
            '/account/change-password',
            [
                AccountController::class,
                'changePassword'
            ]
        )->name('account.password.update');

    });