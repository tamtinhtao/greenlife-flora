<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | NHỮNG ĐƠN ĐƯỢC TÍNH DOANH THU
    |--------------------------------------------------------------------------
    |
    | MoMo:
    | - Phải có giao dịch paid.
    |
    | COD:
    | - Chỉ tính khi đơn đã giao thành công / completed.
    |
    | Không tính:
    | - đơn hủy
    | - hoàn hàng
    | - refund_pending
    | - refunded
    |
    */

    private function paidOrders(): Builder
    {
        return Order::query()

            ->where(
                'orders.created_at',
                '<=',
                now()
            )

            /*
            |--------------------------------------------------------------------------
            | KHÔNG TÍNH ĐƠN ĐÃ HỦY
            |--------------------------------------------------------------------------
            */

            ->where(
                'orders.status',
                '!=',
                'cancelled'
            )

            /*
            |--------------------------------------------------------------------------
            | KHÔNG TÍNH ĐƠN HOÀN / HỦY VẬN CHUYỂN
            |--------------------------------------------------------------------------
            */

            ->whereNotIn(
                'orders.shipping_status',
                [
                    'cancelled',
                    'return',
                    'returning',
                    'returned',
                    'return_transporting',
                    'return_sorting',
                ]
            )

            /*
            |--------------------------------------------------------------------------
            | ĐƠN ĐÃ THU TIỀN
            |--------------------------------------------------------------------------
            */

            ->where(function (Builder $query) {

                /*
                |--------------------------------------------------------------------------
                | 1. MOMO ĐÃ THANH TOÁN THÀNH CÔNG
                |--------------------------------------------------------------------------
                */

                $query->where(function (Builder $momo) {

                    $momo->where(
                        'orders.payment_method',
                        'momo'
                    )

                    ->whereHas(
                        'paymentTransactions',
                        function ($payment) {

                            $payment->where(
                                'gateway',
                                'momo'
                            )
                            ->where(
                                'status',
                                'paid'
                            );
                        }
                    )

                    /*
                    |--------------------------------------------------------------------------
                    | Không được có trạng thái hoàn tiền
                    |--------------------------------------------------------------------------
                    */

                    ->whereDoesntHave(
                        'paymentTransactions',
                        function ($payment) {

                            $payment->where(
                                'gateway',
                                'momo'
                            )
                            ->whereIn(
                                'status',
                                [
                                    'refund_pending',
                                    'refunded',
                                ]
                            );
                        }
                    );
                })


                /*
                |--------------------------------------------------------------------------
                | 2. COD ĐÃ GIAO THÀNH CÔNG
                |--------------------------------------------------------------------------
                */

                ->orWhere(function (Builder $cod) {

                    $cod->where(
                        'orders.payment_method',
                        'cod'
                    )

                    ->where(function (Builder $completed) {

                        $completed
                            ->where(
                                'orders.status',
                                'completed'
                            )

                            ->orWhere(
                                'orders.shipping_status',
                                'delivered'
                            );
                    });
                })


                /*
                |--------------------------------------------------------------------------
                | 3. HỖ TRỢ DỮ LIỆU CŨ
                |--------------------------------------------------------------------------
                */

                ->orWhereIn(
                    'orders.status',
                    [
                        'paid',
                        'cod_paid',
                        'paid_momo',
                    ]
                );
            });
    }


    /*
    |--------------------------------------------------------------------------
    | DOANH THU THEO DANH MỤC
    |--------------------------------------------------------------------------
    |
    | Chỉ tính tiền sản phẩm.
    | Không cộng phí vận chuyển.
    |
    */

    private function categoryRevenue(): Collection
    {
        return DB::table('order_items')

            ->join(
                'products',
                'order_items.product_id',
                '=',
                'products.id'
            )

            ->leftJoin(
                'categories',
                'products.category_id',
                '=',
                'categories.id'
            )

            ->whereIn(
                'order_items.order_id',
                $this->paidOrders()
                    ->select('orders.id')
            )

            ->select(
                'products.category_id',
                'categories.name as category_name'
            )

            ->selectRaw(
                '
                SUM(order_items.price * order_items.quantity)
                    as total_revenue,
                SUM(order_items.quantity)
                    as total_qty
                '
            )

            ->groupBy(
                'products.category_id',
                'categories.name'
            )

            ->orderByDesc(
                'total_revenue'
            )

            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | DOANH THU THEO NGÀY
    |--------------------------------------------------------------------------
    |
    | total_price gồm:
    | tiền sản phẩm + phí vận chuyển
    |
    */

    private function dailyRevenue(): Collection
    {
        return $this->paidOrders()

            ->selectRaw(
                '
                DATE(orders.created_at) as date,
                SUM(orders.total_price) as total_revenue,
                COUNT(*) as order_count
                '
            )

            ->groupByRaw(
                'DATE(orders.created_at)'
            )

            ->orderBy('date')

            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | GỘP DOANH THU THEO THÁNG / NĂM
    |--------------------------------------------------------------------------
    */

    private function periodRevenue(
        Collection $days,
        string $period
    ): Collection {

        return $days

            ->groupBy(
                function ($day) use ($period) {

                    return substr(
                        $day->date,
                        0,
                        $period === 'month'
                            ? 7
                            : 4
                    );
                }
            )

            ->map(
                function (
                    Collection $rows,
                    $key
                ) use ($period) {

                    return (object) [

                        $period =>
                            (string) $key,

                        'total_revenue' =>
                            $rows->sum(
                                'total_revenue'
                            ),

                        'order_count' =>
                            $rows->sum(
                                'order_count'
                            ),
                    ];
                }
            )

            ->values();
    }


    /*
    |--------------------------------------------------------------------------
    | TRANG BÁO CÁO DẠNG BẢNG
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | TỔNG ĐƠN
        |--------------------------------------------------------------------------
        */

        $totalOrders =
            Order::where(
                'created_at',
                '<=',
                now()
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TỔNG KHÁCH HÀNG USER
        |--------------------------------------------------------------------------
        */

        $totalCustomers =
            DB::table('users')
                ->where(
                    'role',
                    'user'
                )
                ->count();


        /*
        |--------------------------------------------------------------------------
        | DOANH THU DANH MỤC
        |--------------------------------------------------------------------------
        */

        $categoryRevenue =
            $this->categoryRevenue();


        /*
        |--------------------------------------------------------------------------
        | DOANH THU NGÀY
        |--------------------------------------------------------------------------
        */

        $revenueByDate =
            $this->dailyRevenue();


        /*
        |--------------------------------------------------------------------------
        | DOANH THU THÁNG
        |--------------------------------------------------------------------------
        */

        $revenueByMonth =
            $this->periodRevenue(
                $revenueByDate,
                'month'
            );


        /*
        |--------------------------------------------------------------------------
        | DOANH THU NĂM
        |--------------------------------------------------------------------------
        */

        $revenueByYear =
            $this->periodRevenue(
                $revenueByDate,
                'year'
            );


        /*
        |--------------------------------------------------------------------------
        | TỔNG DOANH THU
        |--------------------------------------------------------------------------
        */

        $totalRevenue =
            $revenueByDate->sum(
                'total_revenue'
            );


        return view(
            'admin.reports.index',
            compact(
                'categoryRevenue',
                'totalOrders',
                'totalCustomers',
                'totalRevenue',
                'revenueByDate',
                'revenueByMonth',
                'revenueByYear'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TRANG BIỂU ĐỒ
    |--------------------------------------------------------------------------
    */

    public function charts()
    {
        /*
        |--------------------------------------------------------------------------
        | DOANH THU DANH MỤC
        |--------------------------------------------------------------------------
        */

        $categories =
            $this->categoryRevenue();


        $catLabels =
            $categories
                ->map(
                    function ($row) {

                        return
                            $row->category_name
                            ??
                            'Danh mục #'
                            . $row->category_id;
                    }
                )
                ->values()
                ->all();


        $catRevenue =
            $categories
                ->pluck(
                    'total_revenue'
                )
                ->map(
                    fn ($value) =>
                        (float) $value
                )
                ->values()
                ->all();


        /*
        |--------------------------------------------------------------------------
        | DOANH THU THEO NGÀY
        |--------------------------------------------------------------------------
        */

        $daily =
            $this->dailyRevenue();


        $byDate =
            $daily->keyBy('date');


        /*
        |--------------------------------------------------------------------------
        | DOANH THU THEO THÁNG
        |--------------------------------------------------------------------------
        */

        $byMonth =
            $this->periodRevenue(
                $daily,
                'month'
            )
            ->keyBy('month');


        /*
        |--------------------------------------------------------------------------
        | DOANH THU THEO NĂM
        |--------------------------------------------------------------------------
        */

        $byYear =
            $this->periodRevenue(
                $daily,
                'year'
            );


        /*
        |--------------------------------------------------------------------------
        | BIỂU ĐỒ 30 NGÀY GẦN NHẤT
        |--------------------------------------------------------------------------
        */

        $startDay =
            Carbon::now()
                ->startOfDay()
                ->subDays(29);


        /*
        |--------------------------------------------------------------------------
        | BIỂU ĐỒ 12 THÁNG GẦN NHẤT
        |--------------------------------------------------------------------------
        */

        $startMonth =
            Carbon::now()
                ->startOfMonth()
                ->subMonths(11);


        $revDateLabels = [];
        $revDateData = [];

        $revMonthLabels = [];
        $revMonthData = [];


        for ($i = 0; $i < 30; $i++) {

            $date =
                $startDay
                    ->copy()
                    ->addDays($i)
                    ->toDateString();


            $revDateLabels[] =
                $date;


            $revDateData[] =
                (float) (
                    $byDate
                        ->get($date)
                        ?->total_revenue
                    ??
                    0
                );
        }


        for ($i = 0; $i < 12; $i++) {

            $month =
                $startMonth
                    ->copy()
                    ->addMonths($i);


            $key =
                $month->format(
                    'Y-m'
                );


            $revMonthLabels[] =
                $month->format(
                    'm/Y'
                );


            $revMonthData[] =
                (float) (
                    $byMonth
                        ->get($key)
                        ?->total_revenue
                    ??
                    0
                );
        }


        /*
        |--------------------------------------------------------------------------
        | THEO NĂM
        |--------------------------------------------------------------------------
        */

        $revYearLabels =
            $byYear
                ->pluck('year')
                ->values()
                ->all();


        $revYearData =
            $byYear
                ->pluck(
                    'total_revenue'
                )
                ->map(
                    fn ($value) =>
                        (float) $value
                )
                ->values()
                ->all();


        /*
        |--------------------------------------------------------------------------
        | DOANH THU MOMO
        |--------------------------------------------------------------------------
        */

        $momoRevenue =
            (clone $this->paidOrders())

                ->where(
                    'orders.payment_method',
                    'momo'
                )

                ->sum(
                    'orders.total_price'
                );


        /*
        |--------------------------------------------------------------------------
        | DOANH THU COD
        |--------------------------------------------------------------------------
        */

        $codRevenue =
            (clone $this->paidOrders())

                ->where(
                    'orders.payment_method',
                    'cod'
                )

                ->sum(
                    'orders.total_price'
                );


        $paymentMethodLabels = [
            'MoMo',
            'COD',
        ];


        $paymentMethodRevenue = [
            (float) $momoRevenue,
            (float) $codRevenue,
        ];


        return view(
            'admin.reports.charts',
            compact(
                'catLabels',
                'catRevenue',
                'revDateLabels',
                'revDateData',
                'revMonthLabels',
                'revMonthData',
                'revYearLabels',
                'revYearData',
                'paymentMethodLabels',
                'paymentMethodRevenue'
            )
        );
    }
}