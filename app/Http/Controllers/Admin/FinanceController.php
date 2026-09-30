<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FinanceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | CÁC TRẠNG THÁI THANH TOÁN
    |--------------------------------------------------------------------------
    */

    private const STATUSES = [
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


    /*
    |--------------------------------------------------------------------------
    | LUỒNG CHUYỂN TRẠNG THÁI COD
    |--------------------------------------------------------------------------
    */

    private const COD_TRANSITIONS = [

        'pending' => [
            'pending',
            'paid',
            'failed',
        ],

        'failed' => [
            'failed',
            'pending',
            'paid',
        ],

        'paid' => [
            'paid',
            'refund_pending',
        ],

        'refund_pending' => [
            'refund_pending',
            'refunded',
        ],

        'refunded' => [
            'refunded',
        ],

        'cancelled' => [
            'cancelled',
        ],
    ];


    /*
    |--------------------------------------------------------------------------
    | ƯU TIÊN GIAO DỊCH ĐẠI DIỆN
    |--------------------------------------------------------------------------
    |
    | Nếu một đơn có nhiều lần thanh toán:
    |
    | failed
    | paid
    | failed
    |
    | thì phải ưu tiên paid, không để lần failed mới hơn che mất
    | giao dịch thành công.
    |
    */

    private const PAYMENT_PRIORITY =
        "CASE
            WHEN status IN (
                'paid',
                'refund_pending',
                'refunded'
            )
            THEN 0
            ELSE 1
        END";


    /*
    |--------------------------------------------------------------------------
    | 1. QUERY MỖI ORDER CHỈ XUẤT HIỆN MỘT LẦN
    |--------------------------------------------------------------------------
    */

    private function ordersQuery()
    {
        /*
        |--------------------------------------------------------------------------
        | TÌM PAYMENT ĐẠI DIỆN CHO TỪNG ORDER
        |--------------------------------------------------------------------------
        */

        $paymentId =
            DB::table('payment_transactions')
                ->select('id')

                ->whereColumn(
                    'order_id',
                    'orders.id'
                )

                ->orderByRaw(
                    self::PAYMENT_PRIORITY
                )

                ->orderByDesc('id')

                ->limit(1);


        /*
        |--------------------------------------------------------------------------
        | NỐI ORDERS VỚI PAYMENT ĐẠI DIỆN
        |--------------------------------------------------------------------------
        */

        $orders =
            DB::table('orders')

                ->leftJoin(
                    'payment_transactions as payment',

                    function ($join) use ($paymentId) {

                        $join->on(
                            'payment.order_id',
                            '=',
                            'orders.id'
                        );

                        $join->where(
                            'payment.id',
                            '=',
                            $paymentId
                        );
                    }
                )

                ->select(
                    'orders.*',
                    'payment.id as payment_id',
                    'payment.paid_at'
                )


                /*
                |--------------------------------------------------------------------------
                | XÁC ĐỊNH GATEWAY
                |--------------------------------------------------------------------------
                */

                ->selectRaw(
                    "
                    COALESCE(
                        payment.gateway,

                        CASE

                            WHEN orders.status IN (
                                'cod_ordered',
                                'cod_paid'
                            )
                            THEN 'cod'

                            WHEN orders.status IN (
                                'paid',
                                'paid_momo'
                            )
                            THEN 'momo'

                            ELSE 'unknown'

                        END
                    ) as gateway
                    "
                )


                /*
                |--------------------------------------------------------------------------
                | XÁC ĐỊNH TRẠNG THÁI THANH TOÁN
                |--------------------------------------------------------------------------
                */

                ->selectRaw(
                    "
                    COALESCE(
                        payment.status,

                        CASE

                            WHEN orders.status = 'cod_ordered'
                            THEN 'pending'

                            WHEN orders.status IN (
                                'cod_paid',
                                'paid_momo',
                                'paid'
                            )
                            THEN 'paid'

                            ELSE orders.status

                        END
                    ) as payment_status
                    "
                );


        return DB::query()
            ->fromSub(
                $orders,
                'finance_orders'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | 2. LỌC DANH SÁCH
    |--------------------------------------------------------------------------
    */

    private function filteredOrders(
        Request $request
    ): array {

        /*
        |--------------------------------------------------------------------------
        | VALIDATE BỘ LỌC
        |--------------------------------------------------------------------------
        */

        $filters =
            $request->validate(
                [
                    'search' => [
                        'nullable',
                        'string',
                        'max:100',
                    ],

                    'date_from' => [
                        'nullable',
                        'date_format:Y-m-d',
                    ],

                    'date_to' => [
                        'nullable',
                        'date_format:Y-m-d',

                        ...(
                            $request->filled('date_from')
                                ? [
                                    'after_or_equal:date_from'
                                ]
                                : []
                        ),
                    ],

                    'min_amount' => [
                        'nullable',
                        'numeric',
                        'min:0',
                        'max:9999999999999.99',
                    ],

                    'max_amount' => [
                        'nullable',
                        'numeric',
                        'min:0',
                        'max:9999999999999.99',

                        ...(
                            $request->filled('min_amount')
                                ? [
                                    'gte:min_amount'
                                ]
                                : []
                        ),
                    ],

                    'gateway' => [
                        'nullable',
                        Rule::in([
                            'cod',
                            'momo',
                            'unknown',
                        ]),
                    ],

                    'payment_status' => [
                        'nullable',
                        Rule::in(
                            array_keys(
                                self::STATUSES
                            )
                        ),
                    ],

                    'sort' => [
                        'nullable',
                        Rule::in([
                            'newest',
                            'oldest',
                            'amount_asc',
                            'amount_desc',
                        ]),
                    ],

                    'page' => [
                        'nullable',
                        'integer',
                        'min:1',
                    ],
                ],
                [
                    'date_to.after_or_equal' =>
                        'Ngày kết thúc phải từ ngày bắt đầu trở đi.',

                    'max_amount.gte' =>
                        'Số tiền tối đa phải lớn hơn hoặc bằng số tiền tối thiểu.',

                    '*.date_format' =>
                        'Ngày lọc không hợp lệ (định dạng năm-tháng-ngày).',

                    '*.numeric' =>
                        'Số tiền phải là một giá trị số.',

                    '*.min' =>
                        'Giá trị bộ lọc nhỏ hơn mức cho phép.',

                    '*.in' =>
                        'Giá trị bộ lọc không hợp lệ.',
                ]
            );


        $query =
            $this->ordersQuery()
                ->where(
                    'created_at',
                    '<=',
                    now()
                );


        /*
        |--------------------------------------------------------------------------
        | TÌM KIẾM
        |--------------------------------------------------------------------------
        |
        | TÀI LIỆU THẦY:
        | name, phone
        |
        | GREENLIFE:
        | customer_name, customer_phone
        |
        */

        if ($request->filled('search')) {

            $search =
                trim(
                    $filters['search']
                );


            $query->where(
                function ($query) use ($search) {

                    $query
                        ->where(
                            'customer_name',
                            'like',
                            '%' . $search . '%'
                        )

                        ->orWhere(
                            'customer_phone',
                            'like',
                            '%' . $search . '%'
                        )

                        ->orWhere(
                            'customer_email',
                            'like',
                            '%' . $search . '%'
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | TÌM MÃ ĐƠN
                    |--------------------------------------------------------------------------
                    |
                    | Cho phép:
                    | 15
                    | #15
                    |
                    */

                    if (
                        ctype_digit(
                            ltrim(
                                $search,
                                '#'
                            )
                        )
                    ) {

                        $query->orWhere(
                            'id',
                            ltrim(
                                $search,
                                '#'
                            )
                        );
                    }
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | LỌC PAYMENT METHOD + STATUS
        |--------------------------------------------------------------------------
        */

        foreach (
            [
                'gateway',
                'payment_status',
            ]
            as $field
        ) {

            if (
                $request->filled(
                    $field
                )
            ) {

                $query->where(
                    $field,
                    $filters[$field]
                );
            }
        }


        /*
        |--------------------------------------------------------------------------
        | TỪ NGÀY
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'date_from'
            )
        ) {

            $query->where(
                'created_at',
                '>=',
                Carbon::parse(
                    $filters['date_from']
                )->startOfDay()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ĐẾN NGÀY
        |--------------------------------------------------------------------------
        */

        if (
            $request->filled(
                'date_to'
            )
        ) {

            $query->where(
                'created_at',
                '<',
                Carbon::parse(
                    $filters['date_to']
                )
                    ->addDay()
                    ->startOfDay()
            );
        }


        /*
        |--------------------------------------------------------------------------
        | KHOẢNG TIỀN
        |--------------------------------------------------------------------------
        */

        foreach (
            [
                'min_amount' => '>=',
                'max_amount' => '<=',
            ]
            as $field => $operator
        ) {

            if (
                $request->filled(
                    $field
                )
            ) {

                $query->where(
                    'total_price',
                    $operator,
                    $filters[$field]
                );
            }
        }


        return [
            $query,
            $filters,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | 3. TRANG THỐNG KÊ TÀI CHÍNH
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request
    ) {
        [
            $query,
            $filters
        ] =
            $this->filteredOrders(
                $request
            );


        /*
        |--------------------------------------------------------------------------
        | TỔNG ĐƠN + TỔNG GIÁ TRỊ
        |--------------------------------------------------------------------------
        */

        $summary =
            (clone $query)

                ->selectRaw(
                    '
                    COUNT(*) as order_count,
                    COALESCE(
                        SUM(total_price),
                        0
                    ) as total_amount
                    '
                )

                ->first();


        /*
        |--------------------------------------------------------------------------
        | THỐNG KÊ THEO PAYMENT STATUS
        |--------------------------------------------------------------------------
        */

        $statusTotals =
            (clone $query)

                ->select(
                    'payment_status'
                )

                ->selectRaw(
                    '
                    COUNT(*) as order_count,
                    SUM(total_price) as total_amount
                    '
                )

                ->groupBy(
                    'payment_status'
                )

                ->get()

                ->keyBy(
                    'payment_status'
                );


        /*
        |--------------------------------------------------------------------------
        | THỐNG KÊ THEO COD / MOMO
        |--------------------------------------------------------------------------
        */

        $methodTotals =
            (clone $query)

                ->select(
                    'gateway'
                )

                ->selectRaw(
                    '
                    COUNT(*) as order_count,
                    SUM(total_price) as total_amount
                    '
                )

                ->selectRaw(
                    "
                    SUM(
                        CASE
                            WHEN payment_status = 'paid'
                            THEN total_price
                            ELSE 0
                        END
                    ) as paid_amount
                    "
                )

                ->groupBy(
                    'gateway'
                )

                ->get()

                ->keyBy(
                    'gateway'
                );


        return view(
            'admin.finance.index',
            [
                'filters' =>
                    $filters,

                'summary' =>
                    $summary,

                'statusTotals' =>
                    $statusTotals,

                'methodTotals' =>
                    $methodTotals,

                'statuses' =>
                    self::STATUSES,

                'methods' => [
                    'cod' =>
                        'COD',

                    'momo' =>
                        'MoMo',

                    'unknown' =>
                        'Chưa xác định',
                ],
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 4. DANH SÁCH GIAO DỊCH
    |--------------------------------------------------------------------------
    */

    public function transactions(
        Request $request
    ) {
        [
            $query,
            $filters
        ] =
            $this->filteredOrders(
                $request
            );


        /*
        |--------------------------------------------------------------------------
        | SẮP XẾP
        |--------------------------------------------------------------------------
        */

        [
            $column,
            $direction
        ] =
            match (
                $filters['sort']
                ?? 'newest'
            ) {

                'oldest' => [
                    'created_at',
                    'asc',
                ],

                'amount_asc' => [
                    'total_price',
                    'asc',
                ],

                'amount_desc' => [
                    'total_price',
                    'desc',
                ],

                default => [
                    'created_at',
                    'desc',
                ],
            };


        /*
        |--------------------------------------------------------------------------
        | PHÂN TRANG 15 ĐƠN
        |--------------------------------------------------------------------------
        */

        $orders =
            $query

                ->orderBy(
                    $column,
                    $direction
                )

                ->orderBy(
                    'id',
                    $direction
                )

                ->paginate(15)

                ->withQueryString();


        return view(
            'admin.finance.transactions',
            [
                'orders' =>
                    $orders,

                'filters' =>
                    $filters,

                'statuses' =>
                    self::STATUSES,

                'codTransitions' =>
                    self::COD_TRANSITIONS,

                'methods' => [
                    'cod' =>
                        'COD',

                    'momo' =>
                        'MoMo',

                    'unknown' =>
                        'Chưa xác định',
                ],
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | 5. CẬP NHẬT PAYMENT STATUS COD
    |--------------------------------------------------------------------------
    */

    public function updateStatus(
        Request $request,
        Order $order
    ) {
        /*
        |--------------------------------------------------------------------------
        | VALIDATE
        |--------------------------------------------------------------------------
        */

        $data =
            $request->validate(
                [
                    'payment_status' => [
                        'required',

                        Rule::in(
                            array_keys(
                                self::COD_TRANSITIONS
                            )
                        ),
                    ],

                    'current_payment_status' => [
                        'required',
                        'string',
                    ],

                    'current_order_status' => [
                        'required',
                        'string',
                    ],

                    'current_payment_id' => [
                        'required',
                        'integer',
                        'min:0',
                    ],
                ],
                [
                    'payment_status.in' =>
                        'Trạng thái COD không hợp lệ.',

                    '*.required' =>
                        'Thiếu thông tin trạng thái. Vui lòng tải lại trang.',
                ]
            );


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION + LOCK
        |--------------------------------------------------------------------------
        */

        DB::transaction(
            function () use (
                $order,
                $data,
                $request
            ) {

                /*
                |--------------------------------------------------------------------------
                | KHÓA ORDER
                |--------------------------------------------------------------------------
                */

                $order =
                    Order::whereKey(
                        $order->id
                    )
                        ->lockForUpdate()
                        ->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | PAYMENT ĐẠI DIỆN
                |--------------------------------------------------------------------------
                */

                $payment =
                    $order
                        ->paymentTransactions()

                        ->orderByRaw(
                            self::PAYMENT_PRIORITY
                        )

                        ->orderByDesc('id')

                        ->lockForUpdate()

                        ->first();


                /*
                |--------------------------------------------------------------------------
                | CHỈ COD MỚI ĐƯỢC ADMIN SỬA THỦ CÔNG
                |--------------------------------------------------------------------------
                */

                $isCod =
                    $payment
                        ?
                        $payment->gateway
                            === 'cod'

                        :
                        in_array(
                            $order->status,
                            [
                                'cod_ordered',
                                'cod_paid',
                            ],
                            true
                        );


                if (!$isCod) {

                    throw ValidationException::
                        withMessages([
                            'payment_status' =>
                                'Chỉ được cập nhật thủ công cho đơn COD.',
                        ]);
                }


                /*
                |--------------------------------------------------------------------------
                | STATUS HIỆN TẠI
                |--------------------------------------------------------------------------
                */

                $currentStatus =
                    $payment?->status
                    ??
                    (
                        $order->status
                            === 'cod_paid'

                            ? 'paid'

                            : 'pending'
                    );


                /*
                |--------------------------------------------------------------------------
                | CHỐNG UPDATE TỪ TRANG DỮ LIỆU CŨ
                |--------------------------------------------------------------------------
                */

                if (
                    $currentStatus
                        !==
                    $data[
                        'current_payment_status'
                    ]

                    ||

                    $order->status
                        !==
                    $data[
                        'current_order_status'
                    ]

                    ||

                    (int) (
                        $payment?->id
                        ?? 0
                    )
                        !==
                    (int) $data[
                        'current_payment_id'
                    ]
                ) {

                    throw ValidationException::
                        withMessages([
                            'payment_status' =>
                                'Đơn hàng vừa thay đổi. Vui lòng tải lại trang trước khi cập nhật.',
                        ]);
                }


                $newStatus =
                    $data[
                        'payment_status'
                    ];


                /*
                |--------------------------------------------------------------------------
                | KIỂM TRA LUỒNG COD
                |--------------------------------------------------------------------------
                */

                if (
                    !in_array(
                        $newStatus,
                        self::COD_TRANSITIONS[
                            $currentStatus
                        ]
                        ?? [],
                        true
                    )
                ) {

                    throw ValidationException::
                        withMessages([
                            'payment_status' =>
                                'Không thể chuyển sang trạng thái thanh toán này.',
                        ]);
                }


                /*
                |--------------------------------------------------------------------------
                | KHÔNG THAY ĐỔI
                |--------------------------------------------------------------------------
                */

                if (
                    $newStatus
                        ===
                    $currentStatus
                ) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | KHÔNG THU TIỀN ĐƠN ĐÃ HỦY / HOÀN
                |--------------------------------------------------------------------------
                */

                if (
                    in_array(
                        $newStatus,
                        [
                            'pending',
                            'paid',
                        ],
                        true
                    )

                    &&

                    (
                        $order->status
                            === 'cancelled'

                        ||

                        in_array(
                            $order
                                ->shipping_status,
                            [
                                'cancelled',
                                'return',
                                'returned',
                            ],
                            true
                        )
                    )
                ) {

                    throw ValidationException::
                        withMessages([
                            'payment_status' =>
                                'Không thể xác nhận thu tiền cho đơn đã hủy hoặc hoàn hàng.',
                        ]);
                }


                /*
                |--------------------------------------------------------------------------
                | THUỘC TÍNH PAYMENT
                |--------------------------------------------------------------------------
                */

                $attributes = [

                    'status' =>
                        $newStatus,

                    'message' =>
                        'Quản trị viên #'
                        . $request
                            ->user()
                            ->id
                        . ' cập nhật: '
                        . self::STATUSES[
                            $newStatus
                        ],

                    'paid_at' =>
                        $newStatus
                            === 'paid'

                            ?
                            (
                                $payment?->paid_at
                                ?? now()
                            )

                            :
                            $payment?->paid_at,
                ];


                /*
                |--------------------------------------------------------------------------
                | PAYMENT ĐÃ CÓ
                |--------------------------------------------------------------------------
                */

                if ($payment) {

                    $payment->update(
                        $attributes
                    );

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | ĐƠN COD CŨ CHƯA CÓ PAYMENT_TRANSACTION
                    |--------------------------------------------------------------------------
                    */

                    $order
                        ->paymentTransactions()
                        ->create(
                            array_merge(
                                $attributes,
                                [
                                    'gateway' =>
                                        'cod',

                                    'amount' =>
                                        $order
                                            ->total_price,
                                ]
                            )
                        );
                }


                /*
                |--------------------------------------------------------------------------
                | ĐỒNG BỘ ORDER STATUS
                |--------------------------------------------------------------------------
                */

                if (
                    $newStatus
                        === 'paid'
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | Nếu GHN đã giao xong thì giữ nghiệp vụ hoàn thành.
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $order
                            ->shipping_status
                            === 'delivered'
                    ) {

                        $order->update([
                            'status' =>
                                'completed',
                        ]);

                    } else {

                        $order->update([
                            'status' =>
                                'cod_paid',
                        ]);
                    }

                } elseif (
                    in_array(
                        $newStatus,
                        [
                            'pending',
                            'failed',
                        ],
                        true
                    )
                ) {

                    $order->update([
                        'status' =>
                            'cod_ordered',
                    ]);
                }
            }
        );


        return back()->with(
            'success',
            'Đã lưu trạng thái thanh toán đơn COD #'
            . $order->id
            . '.'
        );
    }
}