<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\GHNService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CÁC TAB QUẢN LÝ ĐƠN HÀNG
    |--------------------------------------------------------------------------
    */

    private const TABS = [

        'all' => [
            'label' => 'Tất cả',
            'statuses' => [],
        ],

        'pending' => [
            'label' => 'Chờ xác nhận',
            'statuses' => [
                'pending',
                'not_shipped',
                'processing',
            ],
        ],

        'ready' => [
            'label' => 'Chờ lấy hàng',
            'statuses' => [
                'ready_to_pick',
            ],
        ],

        'picking' => [
            'label' => 'Đang lấy hàng',
            'statuses' => [
                'picking',
            ],
        ],

        'delivering' => [
            'label' => 'Đang giao',
            'statuses' => [
                'picked',
                'storing',
                'transporting',
                'sorting',
                'delivering',
            ],
        ],

        'delivered' => [
            'label' => 'Hoàn thành',
            'statuses' => [
                'delivered',
            ],
        ],

        'return' => [
            'label' => 'Hoàn hàng',
            'statuses' => [
                'return',
                'returning',
                'return_transporting',
                'return_sorting',
                'returned',
            ],
        ],

        'cancelled' => [
            'label' => 'Đã hủy',
            'statuses' => [
                'cancelled',
            ],
        ],
    ];


    /*
    |--------------------------------------------------------------------------
    | QUY TẮC CHUYỂN TRẠNG THÁI
    |--------------------------------------------------------------------------
    |
    | Ví dụ:
    |
    | Chờ xác nhận
    |      ↓
    | Chờ lấy hàng
    |      ↓
    | Đang lấy hàng
    |      ↓
    | Đang giao
    |      ↓
    | Hoàn thành
    |
    */

    private const ALLOWED_TRANSITIONS = [

        'pending' => [
            'ready_to_pick',
            'cancelled',
        ],

        'not_shipped' => [
            'ready_to_pick',
            'cancelled',
        ],

        'processing' => [
            'ready_to_pick',
            'cancelled',
        ],

        'ready_to_pick' => [
            'picking',
            'cancelled',
        ],

        'picking' => [
            'delivering',
            'cancelled',
        ],

        /*
        |--------------------------------------------------------------------------
        | Các trạng thái GHN đang vận chuyển
        |--------------------------------------------------------------------------
        */

        'picked' => [
            'delivering',
        ],

        'storing' => [
            'delivering',
        ],

        'transporting' => [
            'delivering',
        ],

        'sorting' => [
            'delivering',
        ],

        /*
        |--------------------------------------------------------------------------
        | Đã bắt đầu giao
        | → KHÔNG cho hủy
        |--------------------------------------------------------------------------
        */

        'delivering' => [
            'delivered',
            'returning',
        ],

        /*
        |--------------------------------------------------------------------------
        | Hoàn thành
        | → Không được chuyển ngược
        |--------------------------------------------------------------------------
        */

        'delivered' => [],


        /*
        |--------------------------------------------------------------------------
        | Hoàn hàng
        |--------------------------------------------------------------------------
        */

        'return' => [
            'returning',
        ],

        'returning' => [
            'returned',
        ],

        'return_transporting' => [
            'returned',
        ],

        'return_sorting' => [
            'returned',
        ],

        'returned' => [],


        /*
        |--------------------------------------------------------------------------
        | Đã hủy
        | → Không được phục hồi tùy ý
        |--------------------------------------------------------------------------
        */

        'cancelled' => [],
    ];


    /*
    |--------------------------------------------------------------------------
    | DANH SÁCH ĐƠN HÀNG
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $tab = $request->get(
            'tab',
            'all'
        );


        if (!array_key_exists($tab, self::TABS)) {
            $tab = 'all';
        }


        /*
        |--------------------------------------------------------------------------
        | QUERY
        |--------------------------------------------------------------------------
        */

        $query = Order::query()
            ->with([
                'orderItems.product',

                'paymentTransactions' => function ($query) {
                    $query->latest();
                },
            ]);


        /*
        |--------------------------------------------------------------------------
        | LỌC THEO TAB
        |--------------------------------------------------------------------------
        */

        $statuses =
            self::TABS[$tab]['statuses'];


        if (!empty($statuses)) {

            /*
            |--------------------------------------------------------------------------
            | Đơn cũ chưa có shipping_status
            | vẫn tính là Chờ xác nhận
            |--------------------------------------------------------------------------
            */

            if ($tab === 'pending') {

                $query->where(
                    function ($q) use ($statuses) {

                        $q->whereIn(
                            'shipping_status',
                            $statuses
                        )
                        ->orWhereNull(
                            'shipping_status'
                        );
                    }
                );

            } else {

                $query->whereIn(
                    'shipping_status',
                    $statuses
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | TÌM KIẾM
        |--------------------------------------------------------------------------
        */

        $keyword = trim(
            (string) $request->get(
                'keyword',
                ''
            )
        );


        if ($keyword !== '') {

            $query->where(
                function ($q) use ($keyword) {

                    $q->where(
                        'customer_name',
                        'like',
                        '%' . $keyword . '%'
                    )

                    ->orWhere(
                        'customer_phone',
                        'like',
                        '%' . $keyword . '%'
                    )

                    ->orWhere(
                        'customer_email',
                        'like',
                        '%' . $keyword . '%'
                    )

                    ->orWhere(
                        'ghn_order_code',
                        'like',
                        '%' . $keyword . '%'
                    );


                    if (is_numeric($keyword)) {

                        $q->orWhere(
                            'id',
                            (int) $keyword
                        );
                    }
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LỌC COD / MOMO
        |--------------------------------------------------------------------------
        */

        $paymentMethod =
            $request->get(
                'payment_method'
            );


        if (
            in_array(
                $paymentMethod,
                [
                    'cod',
                    'momo',
                ],
                true
            )
        ) {

            $query->where(
                'payment_method',
                $paymentMethod
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PHÂN TRANG
        |--------------------------------------------------------------------------
        */

        $orders = $query
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | ĐẾM SỐ ĐƠN MỖI TAB
        |--------------------------------------------------------------------------
        */

        $tabCounts = [];


        foreach (
            self::TABS
            as $key => $config
        ) {

            if ($key === 'all') {

                $tabCounts[$key] =
                    Order::count();

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | Chờ xác nhận bao gồm đơn cũ shipping_status = null
            |--------------------------------------------------------------------------
            */

            if ($key === 'pending') {

                $tabCounts[$key] =
                    Order::where(
                        function ($q) use ($config) {

                            $q->whereIn(
                                'shipping_status',
                                $config['statuses']
                            )
                            ->orWhereNull(
                                'shipping_status'
                            );
                        }
                    )
                    ->count();

                continue;
            }


            $tabCounts[$key] =
                Order::whereIn(
                    'shipping_status',
                    $config['statuses']
                )
                ->count();
        }


        /*
        |--------------------------------------------------------------------------
        | LABEL THANH TOÁN
        |--------------------------------------------------------------------------
        */

        $paymentLabels = [

            'pending' =>
                'Chờ thanh toán',

            'initiated' =>
                'Đang chờ MoMo',

            'paid' =>
                'Đã thanh toán',

            'failed' =>
                'Thanh toán thất bại',

            'cancelled' =>
                'Đã hủy',

            'refund_pending' =>
                'Chờ hoàn tiền',

            'refunded' =>
                'Đã hoàn tiền',
        ];


        return view(
            'admin.orders.index',
            [
                'orders' =>
                    $orders,

                'tabs' =>
                    self::TABS,

                'currentTab' =>
                    $tab,

                'tabCounts' =>
                    $tabCounts,

                'paymentLabels' =>
                    $paymentLabels,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CẬP NHẬT MỘT ĐƠN
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        Request $request,
        $id,
        GHNService $ghn
    ) {
        $request->validate([
            'shipping_status' =>
                'required|in:pending,ready_to_pick,picking,delivering,delivered,returning,returned,cancelled',
        ]);


        $order =
            Order::findOrFail($id);


        $result =
            $this->applyStatusChange(
                $order,
                $request->shipping_status,
                $ghn
            );


        if (!$result['success']) {

            return back()->with(
                'error',
                $result['message']
            );
        }


        return back()->with(
            'success',
            $result['message']
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CẬP NHẬT NHIỀU ĐƠN
    |--------------------------------------------------------------------------
    */

    public function bulkUpdate(
        Request $request,
        GHNService $ghn
    ) {
        $request->validate(
            [
                'order_ids' =>
                    'required|array|min:1',

                'order_ids.*' =>
                    'integer|exists:orders,id',

                'shipping_status' =>
                    'required|in:pending,ready_to_pick,picking,delivering,delivered,returning,returned,cancelled',
            ],
            [
                'order_ids.required' =>
                    'Bạn chưa chọn đơn hàng nào.',

                'order_ids.min' =>
                    'Bạn chưa chọn đơn hàng nào.',

                'shipping_status.required' =>
                    'Vui lòng chọn trạng thái cần cập nhật.',
            ]
        );


        $orders =
            Order::whereIn(
                'id',
                $request->order_ids
            )
            ->get();


        $newStatus =
            $request->shipping_status;


        $updated = 0;

        $skipped = 0;

        $errors = [];


        /*
        |--------------------------------------------------------------------------
        | DUYỆT TỪNG ĐƠN
        |--------------------------------------------------------------------------
        */

        foreach ($orders as $order) {

            try {

                $result =
                    $this->applyStatusChange(
                        $order,
                        $newStatus,
                        $ghn
                    );


                if ($result['success']) {

                    $updated++;

                } else {

                    $skipped++;

                    $errors[] =
                        'Đơn #'
                        . $order->id
                        . ': '
                        . $result['message'];
                }

            } catch (\Throwable $e) {

                $skipped++;

                $errors[] =
                    'Đơn #'
                    . $order->id
                    . ': '
                    . $e->getMessage();
            }
        }


        /*
        |--------------------------------------------------------------------------
        | THÔNG BÁO
        |--------------------------------------------------------------------------
        */

        $message =
            'Đã cập nhật '
            . $updated
            . ' đơn hàng.';


        if ($skipped > 0) {

            $message .=
                ' Có '
                . $skipped
                . ' đơn bị bỏ qua.';
        }


        return back()
            ->with(
                'success',
                $message
            )
            ->with(
                'bulk_errors',
                $errors
            );
    }


    /*
    |--------------------------------------------------------------------------
    | XỬ LÝ CHUYỂN TRẠNG THÁI
    |--------------------------------------------------------------------------
    |
    | Cả update 1 đơn và bulk update đều gọi vào đây.
    |
    */

    private function applyStatusChange(
        Order $order,
        string $newStatus,
        GHNService $ghn
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Đơn cũ chưa có shipping_status
        |--------------------------------------------------------------------------
        */

        $currentStatus =
            $order->shipping_status
            ?: 'pending';


        /*
        |--------------------------------------------------------------------------
        | ĐANG Ở ĐÚNG TRẠNG THÁI
        |--------------------------------------------------------------------------
        */

        if ($currentStatus === $newStatus) {

            return [
                'success' => true,

                'message' =>
                    'Đơn #'
                    . $order->id
                    . ' đã ở trạng thái này.',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA LUỒNG
        |--------------------------------------------------------------------------
        */

        if (
            !$this->canTransition(
                $currentStatus,
                $newStatus
            )
        ) {

            return [
                'success' => false,

                'message' =>
                    'Không thể chuyển từ "'
                    . $this->statusLabel($currentStatus)
                    . '" sang "'
                    . $this->statusLabel($newStatus)
                    . '".',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | HỦY ĐƠN
        |--------------------------------------------------------------------------
        */

        if ($newStatus === 'cancelled') {

            /*
            |--------------------------------------------------------------------------
            | HỦY VẬN ĐƠN GHN TRƯỚC
            |--------------------------------------------------------------------------
            */

            $cancelResult =
                $this->cancelGhnOrder(
                    $order,
                    $ghn
                );


            if ($cancelResult !== true) {

                return [
                    'success' => false,

                    'message' =>
                        $cancelResult,
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | MOMO ĐÃ THANH TOÁN
            | → ĐÁNH DẤU CHỜ HOÀN TIỀN
            |--------------------------------------------------------------------------
            |
            | Đây chưa phải gọi API refund thật.
            |
            */

            $order->paymentTransactions()
                ->where(
                    'gateway',
                    'momo'
                )
                ->where(
                    'status',
                    'paid'
                )
                ->update([
                    'status' =>
                        'refund_pending',

                    'message' =>
                        'Admin hủy đơn, chờ hoàn tiền MoMo',
                ]);


            /*
            |--------------------------------------------------------------------------
            | CẬP NHẬT ORDER
            |--------------------------------------------------------------------------
            */

            $order->update([
                'status' =>
                    'cancelled',

                'shipping_status' =>
                    'cancelled',
            ]);


            return [
                'success' => true,

                'message' =>
                    'Đã hủy đơn #'
                    . $order->id
                    . '.',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | GIAO HÀNG THÀNH CÔNG
        |--------------------------------------------------------------------------
        */

        if ($newStatus === 'delivered') {

            $order->update([
                'shipping_status' =>
                    'delivered',

                'status' =>
                    'completed',
            ]);


            return [
                'success' => true,

                'message' =>
                    'Đơn #'
                    . $order->id
                    . ' đã hoàn thành.',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | CÁC TRẠNG THÁI KHÁC
        |--------------------------------------------------------------------------
        */

        $order->update([
            'shipping_status' =>
                $newStatus,
        ]);


        return [
            'success' => true,

            'message' =>
                'Đã chuyển đơn #'
                . $order->id
                . ' sang '
                . $this->statusLabel(
                    $newStatus
                )
                . '.',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA CHUYỂN TRẠNG THÁI CÓ HỢP LỆ KHÔNG
    |--------------------------------------------------------------------------
    */

    private function canTransition(
        string $currentStatus,
        string $newStatus
    ): bool {

        if (
            !array_key_exists(
                $currentStatus,
                self::ALLOWED_TRANSITIONS
            )
        ) {

            return false;
        }


        return in_array(
            $newStatus,
            self::ALLOWED_TRANSITIONS[
                $currentStatus
            ],
            true
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HỦY VẬN ĐƠN GHN
    |--------------------------------------------------------------------------
    */

    private function cancelGhnOrder(
        Order $order,
        GHNService $ghn
    ) {
        /*
        |--------------------------------------------------------------------------
        | CHƯA TẠO GHN
        |--------------------------------------------------------------------------
        */

        if (!$order->ghn_order_code) {

            return true;
        }


        /*
        |--------------------------------------------------------------------------
        | GỌI API GHN
        |--------------------------------------------------------------------------
        */

        $response =
            $ghn->cancelOrder([
                $order->ghn_order_code
            ]);


        if (
            ($response['code'] ?? null)
            != 200
        ) {

            return
                'GHN không cho phép hủy vận đơn: '
                . (
                    $response['message']
                    ?? 'Không xác định.'
                );
        }


        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | ĐỔI STATUS THÀNH CHỮ DỄ ĐỌC
    |--------------------------------------------------------------------------
    */

    private function statusLabel(
        string $status
    ): string {

        $labels = [

            'pending' =>
                'Chờ xác nhận',

            'not_shipped' =>
                'Chờ xác nhận',

            'processing' =>
                'Đang xử lý',

            'ready_to_pick' =>
                'Chờ lấy hàng',

            'picking' =>
                'Đang lấy hàng',

            'picked' =>
                'Đã lấy hàng',

            'storing' =>
                'Đang lưu kho',

            'transporting' =>
                'Đang trung chuyển',

            'sorting' =>
                'Đang phân loại',

            'delivering' =>
                'Đang giao',

            'delivered' =>
                'Hoàn thành',

            'return' =>
                'Hoàn hàng',

            'returning' =>
                'Đang hoàn hàng',

            'return_transporting' =>
                'Đang hoàn hàng',

            'return_sorting' =>
                'Đang hoàn hàng',

            'returned' =>
                'Đã hoàn hàng',

            'cancelled' =>
                'Đã hủy',
        ];


        return
            $labels[$status]
            ?? $status;
    }
    /*
|--------------------------------------------------------------------------
| ĐỒNG BỘ TRẠNG THÁI TỪ GHN
|--------------------------------------------------------------------------
*/

public function syncGhn(GHNService $ghn)
{
    /*
    |--------------------------------------------------------------------------
    | CHỈ LẤY CÁC ĐƠN ĐÃ CÓ MÃ VẬN ĐƠN GHN
    |--------------------------------------------------------------------------
    */

    $orders = Order::whereNotNull('ghn_order_code')
        ->where('ghn_order_code', '!=', '')
        ->get();


    $updated = 0;
    $failed = 0;
    $errors = [];


    foreach ($orders as $order) {

        try {

            /*
            |--------------------------------------------------------------------------
            | GỌI GHN LẤY THÔNG TIN THẬT
            |--------------------------------------------------------------------------
            */

            $response =
                $ghn->getOrderInfo(
                    $order->ghn_order_code
                );


            /*
            |--------------------------------------------------------------------------
            | GHN TRẢ LỖI
            |--------------------------------------------------------------------------
            */

            if (
                ($response['code'] ?? null) != 200
                ||
                empty($response['data'])
            ) {

                $failed++;

                $errors[] =
                    'Đơn #'
                    . $order->id
                    . ': '
                    . (
                        $response['message']
                        ?? 'Không lấy được trạng thái GHN.'
                    );

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | LẤY SHIPPING STATUS
            |--------------------------------------------------------------------------
            */

            $ghnStatus =
                $response['data']['status']
                ?? null;


            if (!$ghnStatus) {

                $failed++;

                $errors[] =
                    'Đơn #'
                    . $order->id
                    . ': GHN không trả về trạng thái.';

                continue;
            }


            /*
            |--------------------------------------------------------------------------
            | CHUẨN HÓA MỘT SỐ STATUS
            |--------------------------------------------------------------------------
            |
            | GHN dùng "cancel"
            | Website đang dùng "cancelled"
            |
            */

            $shippingStatus =
                match ($ghnStatus) {

                    'cancel' =>
                        'cancelled',

                    default =>
                        $ghnStatus,
                };


            /*
            |--------------------------------------------------------------------------
            | UPDATE SHIPPING STATUS
            |--------------------------------------------------------------------------
            */

            $updateData = [
                'shipping_status' =>
                    $shippingStatus,
            ];


            /*
            |--------------------------------------------------------------------------
            | GHN GIAO THÀNH CÔNG
            |--------------------------------------------------------------------------
            */

            if ($shippingStatus === 'delivered') {

                $updateData['status'] =
                    'completed';
            }


            /*
            |--------------------------------------------------------------------------
            | GHN ĐÃ HỦY
            |--------------------------------------------------------------------------
            */

            if ($shippingStatus === 'cancelled') {

                $updateData['status'] =
                    'cancelled';
            }


            $order->update(
                $updateData
            );


            $updated++;


        } catch (\Throwable $e) {

            $failed++;

            $errors[] =
                'Đơn #'
                . $order->id
                . ': '
                . $e->getMessage();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | THÔNG BÁO
    |--------------------------------------------------------------------------
    */

    $message =
        'Đồng bộ GHN thành công '
        . $updated
        . ' đơn.';


    if ($failed > 0) {

        $message .=
            ' Có '
            . $failed
            . ' đơn không đồng bộ được.';
    }


    return back()
        ->with(
            'success',
            $message
        )
        ->with(
            'bulk_errors',
            $errors
        );
}
/*
|--------------------------------------------------------------------------
| XEM CHI TIẾT ĐƠN HÀNG
|--------------------------------------------------------------------------
*/

public function show($id)
{
    $order = Order::with([
        'orderItems.product',

        'paymentTransactions' => function ($query) {
            $query->latest();
        },
    ])->findOrFail($id);


    return view(
        'admin.orders.show',
        compact('order')
    );
}
}