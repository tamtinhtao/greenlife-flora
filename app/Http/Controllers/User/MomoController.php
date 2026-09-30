<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\GHNOrderService;
use App\Services\MomoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MomoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | 1. BẮT ĐẦU THANH TOÁN MOMO
    |--------------------------------------------------------------------------
    */

    public function start(
        Order $order,
        MomoService $momo
    )
    {
        // Chỉ chủ đơn hàng mới được thanh toán
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }


        /*
        |--------------------------------------------------------------------------
        | OrderController đã tạo transaction pending trước đó.
        | Lấy lại để tránh tạo trùng 2 transaction.
        |--------------------------------------------------------------------------
        */

        $transaction = PaymentTransaction::where(
                'order_id',
                $order->id
            )
            ->where(
                'gateway',
                'momo'
            )
            ->where(
                'status',
                'pending'
            )
            ->latest()
            ->first();


        // Nếu chưa có thì mới tạo
        if (!$transaction) {

            $transaction =
                $this->newTransaction(
                    $order
                );
        }


        return $this->redirectToMomo(
            $order,
            $transaction,
            $momo
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 2. THANH TOÁN LẠI
    |--------------------------------------------------------------------------
    */

    public function payAgain(
        Order $order,
        MomoService $momo
    )
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }


        $transaction =
            $this->newTransaction(
                $order
            );


        return $this->redirectToMomo(
            $order,
            $transaction,
            $momo
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 3. CALLBACK
    |
    | Sau khi người dùng thanh toán trên MoMo,
    | MoMo chuyển trình duyệt về đây.
    |--------------------------------------------------------------------------
    */

    public function callback(
        Request $request,
        GHNOrderService $ghnOrders,
        MomoService $momo
    )
    {
        Log::info(
            'MoMo callback received',
            [
                'payload' =>
                    $request->except(
                        'signature'
                    ),

                'has_signature' =>
                    $request->has(
                        'signature'
                    ),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA CHỮ KÝ + KẾT QUẢ THANH TOÁN
        |--------------------------------------------------------------------------
        */

        if (
            !$momo->isValidSuccessfulResponse(
                $request->all()
            )
        ) {

            Log::warning(
                'MoMo callback rejected',
                [
                    'result_code' =>
                        $request->input(
                            'resultCode'
                        ),

                    'order_id' =>
                        $request->input(
                            'orderId'
                        ),

                    'signature_valid' =>
                        $momo->isValidResponse(
                            $request->all()
                        ),
                ]
            );


            /*
            |--------------------------------------------------------------------------
            | Chữ ký đúng nhưng thanh toán thất bại
            |--------------------------------------------------------------------------
            */

            if (
                $momo->isValidResponse(
                    $request->all()
                )
            ) {

                $this->markFailed(
                    $request->all(),
                    $momo
                );
            }


            return redirect()
                ->route('my-orders.index')
                ->with(
                    'error',
                    'Giao dịch MoMo thất bại.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | THANH TOÁN THÀNH CÔNG
        |--------------------------------------------------------------------------
        */

        $result =
            $this->completePayment(
                $request->all(),
                $ghnOrders,
                $momo
            );
            /*
|--------------------------------------------------------------------------
| MOMO THANH TOÁN THÀNH CÔNG
| → CHỈ XÓA CÁC SẢN PHẨM VỪA MUA KHỎI GIỎ
|--------------------------------------------------------------------------
*/

$orderId =
    $momo->orderId(
        $request->all()
    );


if ($orderId) {

    $paidOrder =
        Order::find(
            $orderId
        );


    if (
        $paidOrder
        &&
        $paidOrder->status === 'paid'
    ) {

        $this->removePurchasedItemsAfterMomo(
            $paidOrder
        );
    }
}


        $message =
            in_array(
                $result,
                [
                    'created',
                    'already_created'
                ],
                true
            )

                ? 'Thanh toán MoMo thành công! Vận đơn GHN đã được khởi tạo.'

                : 'Thanh toán thành công! Đơn hàng đang chờ tạo vận đơn GHN.';


        return redirect()
            ->route('my-orders.index')
            ->with(
                'success',
                $message
            );
    }


    /*
    |--------------------------------------------------------------------------
    | 4. IPN
    |
    | MoMo gọi trực tiếp từ server MoMo về server Laravel.
    |--------------------------------------------------------------------------
    */

    public function ipn(
        Request $request,
        GHNOrderService $ghnOrders,
        MomoService $momo
    )
    {
        Log::info(
            'MoMo IPN received',
            [
                'payload' =>
                    $request->except(
                        'signature'
                    ),

                'has_signature' =>
                    $request->has(
                        'signature'
                    ),
            ]
        );


        if (
            $momo->isValidSuccessfulResponse(
                $request->all()
            )
        ) {

            $this->completePayment(
                $request->all(),
                $ghnOrders,
                $momo
            );

        } elseif (
            $momo->isValidResponse(
                $request->all()
            )
        ) {

            $this->markFailed(
                $request->all(),
                $momo
            );
        }


        return response()->json([
            'message' => 'Received'
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 5. TẠO MỘT LẦN THỬ THANH TOÁN
    |--------------------------------------------------------------------------
    */

    private function newTransaction(
        Order $order
    ): PaymentTransaction
    {
        return PaymentTransaction::create([

            'order_id' =>
                $order->id,

            'gateway' =>
                'momo',

            'amount' =>
                $order->total_price,

            'status' =>
                'pending',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 6. GỌI MOMO VÀ CHUYỂN KHÁCH SANG PAYURL
    |--------------------------------------------------------------------------
    */

    private function redirectToMomo(
        Order $order,
        PaymentTransaction $transaction,
        MomoService $momo
    )
    {
        $result =
            $momo->createPayment(
                $order,
                $transaction
            );


        /*
        |--------------------------------------------------------------------------
        | MoMo trả payUrl
        |--------------------------------------------------------------------------
        */

        if (isset($result['payUrl'])) {

            return redirect(
                $result['payUrl']
            );
        }


        return redirect()
            ->route('my-orders.index')
            ->with(
                'error',
                'Không thể kết nối tới MoMo.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | 7. HOÀN THÀNH THANH TOÁN
    |--------------------------------------------------------------------------
    */

    private function completePayment(
        array $payload,
        GHNOrderService $ghnOrders,
        MomoService $momo
    ): string
    {
        /*
        |--------------------------------------------------------------------------
        | XỬ LÝ DATABASE TRONG TRANSACTION
        |--------------------------------------------------------------------------
        */

        $result = DB::transaction(
            function () use (
                $payload,
                $momo
            ) {

                /*
                |--------------------------------------------------------------------------
                | TÌM PAYMENT TRANSACTION
                |--------------------------------------------------------------------------
                */

                $transaction =
                    PaymentTransaction::where(
                        'gateway',
                        'momo'
                    )
                    ->where(
                        'gateway_order_id',
                        $payload['orderId'] ?? ''
                    )
                    ->lockForUpdate()
                    ->first();


                if (!$transaction) {

                    return 'invalid';
                }


                /*
                |--------------------------------------------------------------------------
                | TÌM ORDER
                |--------------------------------------------------------------------------
                */

                $order =
                    Order::lockForUpdate()
                        ->find(
                            $transaction->order_id
                        );


                if (!$order) {

                    return 'invalid';
                }


                /*
                |--------------------------------------------------------------------------
                | NẾU ĐÃ CÓ VẬN ĐƠN GHN
                |--------------------------------------------------------------------------
                */

                if ($order->ghn_order_code) {

                    return 'already_created';
                }


                /*
                |--------------------------------------------------------------------------
                | NẾU ĐANG XỬ LÝ TẠO VẬN ĐƠN
                |--------------------------------------------------------------------------
                */

                if (
                    $order->shipping_status
                    === 'processing'
                ) {

                    return 'processing';
                }


                /*
                |--------------------------------------------------------------------------
                | KIỂM TRA SỐ TIỀN MOMO TRẢ VỀ
                |--------------------------------------------------------------------------
                */

                if (
                    (int) $transaction->amount
                    !==
                    (int) (
                        $payload['amount']
                        ?? 0
                    )
                ) {

                    $momo->markFailed(
                        $transaction,
                        $payload
                    );


                    return 'invalid';
                }


                /*
                |--------------------------------------------------------------------------
                | CẬP NHẬT ORDER ĐÃ THANH TOÁN
                |--------------------------------------------------------------------------
                */

                $order->update([

                    'status' =>
                        'paid',

                    'shipping_status' =>
                        'processing',
                ]);


                /*
                |--------------------------------------------------------------------------
                | CẬP NHẬT TRANSACTION ĐÃ PAID
                |--------------------------------------------------------------------------
                */

                $momo->markPaid(
                    $transaction,
                    $payload
                );


                return [
                    'create',
                    $order->id
                ];
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Nếu không cần tạo GHN nữa
        |--------------------------------------------------------------------------
        */

        if (!is_array($result)) {

            return (string) $result;
        }


        /*
        |--------------------------------------------------------------------------
        | 8. TẠO VẬN ĐƠN GHN SAU KHI MOMO THÀNH CÔNG
        |--------------------------------------------------------------------------
        */

        $order =
            Order::with(
                'items.product'
            )
            ->find(
                $result[1]
            );


        $response =
            $ghnOrders->create(
                $order,
                true
            );


        /*
        |--------------------------------------------------------------------------
        | GHN TẠO THÀNH CÔNG
        |--------------------------------------------------------------------------
        */

        if (
            isset($response['code'])
            &&
            $response['code'] === 200
            &&
            !empty(
                $response['data']['order_code']
            )
        ) {

            $order->update([

                'ghn_order_code' =>
                    $response['data']['order_code'],

                'shipping_status' =>
                    'ready_to_pick',
            ]);


            return 'created';
        }


        /*
        |--------------------------------------------------------------------------
        | GHN THẤT BẠI
        |--------------------------------------------------------------------------
        */

        Log::error(
            'GHN order failed after MoMo payment',
            [
                'order_id' =>
                    $order->id,

                'response' =>
                    $response,
            ]
        );


        $order->update([

            'shipping_status' =>
                'pending'
        ]);


        return 'failed';
    }


    /*
    |--------------------------------------------------------------------------
    | 9. ĐÁNH DẤU GIAO DỊCH THẤT BẠI
    |--------------------------------------------------------------------------
    */

    private function markFailed(
        array $payload,
        MomoService $momo
    ): void
    {
        $transaction =
            PaymentTransaction::where(
                'gateway',
                'momo'
            )
            ->where(
                'gateway_order_id',
                $payload['orderId'] ?? ''
            )
            ->first();


        /*
        |--------------------------------------------------------------------------
        | Không đổi paid thành failed nếu callback tới nhiều lần
        |--------------------------------------------------------------------------
        */

        if (
            $transaction
            &&
            $transaction->status !== 'paid'
        ) {

            $momo->markFailed(
                $transaction,
                $payload
            );
        }
    }
    /*
|--------------------------------------------------------------------------
| XÓA SẢN PHẨM ĐÃ THANH TOÁN MOMO KHỎI GIỎ
|--------------------------------------------------------------------------
*/

private function removePurchasedItemsAfterMomo(
    Order $order
): void
{
    /*
    |--------------------------------------------------------------------------
    | LẤY THÔNG TIN SẢN PHẨM ĐÃ GHI NHỚ TRƯỚC KHI SANG MOMO
    |--------------------------------------------------------------------------
    */

    $context =
        session()->pull(
            'momo_purchase_context.'
            . $order->id
        );


    /*
    |--------------------------------------------------------------------------
    | KHÔNG CÓ CONTEXT
    |--------------------------------------------------------------------------
    */

    if (!$context) {

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | NẾU LÀ MUA NGAY
    |--------------------------------------------------------------------------
    |
    | Buy-now không lấy sản phẩm từ giỏ chính
    | nên không xóa cart.
    |
    */

    if (
        ($context['mode'] ?? null)
        === 'buy-now'
    ) {

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | LẤY GIỎ HÀNG CHÍNH
    |--------------------------------------------------------------------------
    */

    $mainCart =
        session()->get(
            'cart',
            []
        );


    /*
    |--------------------------------------------------------------------------
    | CHỈ XÓA NHỮNG PRODUCT ĐÃ THANH TOÁN
    |--------------------------------------------------------------------------
    */

    $productIds =
        $context['product_ids']
        ?? [];


    foreach (
        $productIds
        as $productId
    ) {

        unset(
            $mainCart[$productId]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LƯU LẠI GIỎ CÒN LẠI
    |--------------------------------------------------------------------------
    */

    session()->put(
        'cart',
        $mainCart
    );
}
}