<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\GHNService;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DANH SÁCH ĐƠN HÀNG CỦA USER
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $orders = Order::where(
            'user_id',
            Auth::id()
        )
        ->with('orderItems.product')
        ->orderByDesc('created_at')
        ->get();

        return view(
            'orders.index',
            compact('orders')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | USER HỦY ĐƠN HÀNG
    |--------------------------------------------------------------------------
    */

    public function cancel(
    Order $order,
    GHNService $ghn
)
{
    /*
    |--------------------------------------------------------------------------
    | 1. CHỈ ĐƯỢC HỦY ĐƠN CỦA CHÍNH MÌNH
    |--------------------------------------------------------------------------
    */

    if ($order->user_id !== Auth::id()) {
        abort(403);
    }


    /*
    |--------------------------------------------------------------------------
    | 2. NHỮNG TRẠNG THÁI ĐƠN ĐƯỢC PHÉP HỦY
    |--------------------------------------------------------------------------
    |
    | pending     : đơn cũ
    | cod_ordered : đơn COD mới
    | paid        : đơn MoMo đã thanh toán
    |
    */

    $allowedOrderStatuses = [
        'pending',
        'cod_ordered',
        'paid',
    ];


    if (
        !in_array(
            $order->status,
            $allowedOrderStatuses,
            true
        )
    ) {

        return back()->with(
            'error',
            'Đơn hàng này không còn có thể hủy.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 3. KIỂM TRA TRẠNG THÁI VẬN CHUYỂN GHN
    |--------------------------------------------------------------------------
    */

    $allowedShippingStatuses = [
        'not_shipped',
        'pending',
        'ready_to_pick',
    ];


    if (
        !in_array(
            $order->shipping_status,
            $allowedShippingStatuses,
            true
        )
    ) {

        return back()->with(
            'error',
            'Đơn hàng đã được giao cho đơn vị vận chuyển nên không thể hủy.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 4. NẾU ĐÃ CÓ MÃ GHN
    |    → HỦY VẬN ĐƠN GHN TRƯỚC
    |--------------------------------------------------------------------------
    */

    if ($order->ghn_order_code) {

        $response = $ghn->cancelOrder([
            $order->ghn_order_code
        ]);


        if (($response['code'] ?? null) !== 200) {

            return back()->with(
                'error',
                'Không thể hủy vận đơn GHN: '
                . ($response['message']
                    ?? 'GHN từ chối yêu cầu hủy.')
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | 5. NẾU ĐƠN MOMO ĐÃ THANH TOÁN
    |--------------------------------------------------------------------------
    |
    | Chưa hoàn tiền thật ở đây.
    | Chỉ đánh dấu giao dịch đang chờ hoàn tiền.
    |
    */

    if ($order->status === 'paid') {

        $order->paymentTransactions()
            ->where('gateway', 'momo')
            ->where('status', 'paid')
            ->update([
                'status' => 'refund_pending',
                'message' => 'Đơn hàng đã hủy, chờ hoàn tiền MoMo',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 6. CẬP NHẬT ORDER THÀNH ĐÃ HỦY
    |--------------------------------------------------------------------------
    */

    $wasPaid = $order->status === 'paid';

    $order->update([
        'status' => 'cancelled',
        'shipping_status' => 'cancelled',
    ]);


    /*
    |--------------------------------------------------------------------------
    | 7. THÔNG BÁO
    |--------------------------------------------------------------------------
    */

    if ($wasPaid) {

        return back()->with(
            'success',
            'Đơn hàng đã được hủy. Thanh toán MoMo đang chờ xử lý hoàn tiền.'
        );
    }


    return back()->with(
        'success',
        'Đơn hàng đã được hủy thành công.'
    );
}
}