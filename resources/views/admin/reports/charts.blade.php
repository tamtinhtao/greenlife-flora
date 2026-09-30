@extends('layouts.admin')


@section(
    'title',
    'Biểu đồ báo cáo - GreenLife Admin'
)


@section(
    'page-title',
    'Biểu đồ báo cáo'
)


@section(
    'page-subtitle',
    'Theo dõi doanh thu trực quan theo nhiều tiêu chí'
)



@push('styles')

<style>

    .chart-page-header {

        display: flex;

        justify-content:
            space-between;

        align-items: center;

        gap: 15px;

        flex-wrap: wrap;

        margin-bottom: 22px;
    }


    .chart-page-title {

        margin: 0 0 4px;

        font-size: 27px;

        font-weight: 500;
    }


    .chart-page-description {

        margin: 0;

        color: #8997a4;
    }


    .chart-card {

        height: 100%;

        background: white;

        border:
            1px solid #e1e5e8;

        box-shadow:
            0 1px 4px
            rgba(0,0,0,.05);
    }


    .chart-card-body {

        padding: 20px;
    }


    .chart-title {

        font-size: 17px;

        font-weight: 700;
    }


    .chart-container {

        position: relative;

        height: 350px;
    }


    .chart-container-small {

        position: relative;

        height: 320px;
    }

</style>

@endpush



@section('content')


<div class="chart-page-header">


    <div>

        <h1 class="chart-page-title">

            Biểu đồ báo cáo

        </h1>


        <p class="chart-page-description">

            Theo dõi trực quan doanh thu
            của GreenLife Flora.

        </p>

    </div>


    <a
        href="{{
            route(
                'admin.reports.index'
            )
        }}"
        class="btn btn-outline-primary"
    >

        ← Xem bảng số liệu

    </a>

</div>



{{-- =========================================================
     CATEGORY + PAYMENT
========================================================= --}}

<div class="row g-4 mb-4">


    <div class="col-lg-7">

        <div class="chart-card">

            <div class="chart-card-body">


                <div class="chart-title">

                    🌿 Doanh thu theo danh mục

                </div>


                <div
                    class="
                        small
                        text-muted
                        mb-3
                    "
                >

                    Chỉ tính giá trị sản phẩm,
                    không bao gồm phí vận chuyển.

                </div>


                <div class="chart-container">

                    <canvas
                        id="categoryRevenueChart"
                    ></canvas>

                </div>

            </div>

        </div>

    </div>



    <div class="col-lg-5">

        <div class="chart-card">

            <div class="chart-card-body">


                <div class="chart-title">

                    💳 Doanh thu theo thanh toán

                </div>


                <div
                    class="
                        small
                        text-muted
                        mb-3
                    "
                >

                    So sánh doanh thu
                    MoMo và COD.

                </div>


                <div
                    class="
                        chart-container-small
                    "
                >

                    <canvas
                        id="revenueByPaymentMethodChart"
                    ></canvas>

                </div>

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
     30 DAYS
========================================================= --}}

<div class="chart-card mb-4">

    <div class="chart-card-body">


        <div class="chart-title">

            📅 Doanh thu 30 ngày gần nhất

        </div>


        <div
            class="
                small
                text-muted
                mb-3
            "
        >

            Theo dõi biến động doanh thu
            từng ngày.

        </div>


        <div class="chart-container">

            <canvas
                id="revenueByDateChart"
            ></canvas>

        </div>

    </div>

</div>



{{-- =========================================================
     12 MONTHS
========================================================= --}}

<div class="chart-card mb-4">

    <div class="chart-card-body">


        <div class="chart-title">

            🗓️ Doanh thu 12 tháng gần nhất

        </div>


        <div
            class="
                small
                text-muted
                mb-3
            "
        >

            Tổng doanh thu của từng tháng.

        </div>


        <div class="chart-container">

            <canvas
                id="revenueByMonthChart"
            ></canvas>

        </div>

    </div>

</div>



{{-- =========================================================
     YEARS
========================================================= --}}

<div class="chart-card">

    <div class="chart-card-body">


        <div class="chart-title">

            📈 Doanh thu theo năm

        </div>


        <div
            class="
                small
                text-muted
                mb-3
            "
        >

            So sánh tổng doanh thu
            giữa các năm.

        </div>


        <div class="chart-container">

            <canvas
                id="revenueByYearChart"
            ></canvas>

        </div>

    </div>

</div>


@endsection



@push('scripts')


{{-- =========================================================
     CHART.JS
========================================================= --}}

<script
    src="https://cdn.jsdelivr.net/npm/chart.js"
></script>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        const reportData = {

            catLabels:
                @json($catLabels),

            catRevenue:
                @json($catRevenue),

            revDateLabels:
                @json($revDateLabels),

            revDateData:
                @json($revDateData),

            revMonthLabels:
                @json($revMonthLabels),

            revMonthData:
                @json($revMonthData),

            revYearLabels:
                @json($revYearLabels),

            revYearData:
                @json($revYearData),

            paymentMethodLabels:
                @json($paymentMethodLabels),

            paymentMethodRevenue:
                @json($paymentMethodRevenue)
        };



        /*
        |--------------------------------------------------------------------------
        | MONEY FORMATTER
        |--------------------------------------------------------------------------
        */

        const moneyFormatter =
            new Intl.NumberFormat(
                'vi-VN',
                {
                    style:
                        'currency',

                    currency:
                        'VND'
                }
            );



        /*
        |--------------------------------------------------------------------------
        | COMMON CHART
        |--------------------------------------------------------------------------
        */

        function createChart(
            element,
            type,
            labels,
            data,
            label
        ) {

            if (!element) {
                return;
            }


            return new Chart(
                element,
                {

                    type: type,


                    data: {

                        labels:
                            labels,


                        datasets: [

                            {

                                label:
                                    label,

                                data:
                                    data,

                                borderWidth:
                                    2,

                                tension:
                                    0.3,

                                fill:
                                    type
                                    ===
                                    'line'
                            }
                        ]
                    },


                    options: {

                        responsive:
                            true,

                        maintainAspectRatio:
                            false,


                        interaction: {

                            intersect:
                                false,

                            mode:
                                'index'
                        },


                        plugins: {

                            legend: {

                                display:
                                    true
                            },


                            tooltip: {

                                callbacks: {

                                    label:
                                        function (
                                            context
                                        ) {

                                            return (
                                                context
                                                    .dataset
                                                    .label
                                                +
                                                ': '
                                                +
                                                moneyFormatter
                                                    .format(
                                                        context
                                                            .raw
                                                    )
                                            );
                                        }
                                }
                            }
                        },


                        scales: {

                            y: {

                                beginAtZero:
                                    true,


                                ticks: {

                                    callback:
                                        function (
                                            value
                                        ) {

                                            return (
                                                new Intl
                                                    .NumberFormat(
                                                        'vi-VN'
                                                    )
                                                    .format(
                                                        value
                                                    )
                                                +
                                                ' đ'
                                            );
                                        }
                                }
                            }
                        }
                    }
                }
            );
        }



        /*
        |--------------------------------------------------------------------------
        | CATEGORY
        |--------------------------------------------------------------------------
        */

        createChart(

            document.getElementById(
                'categoryRevenueChart'
            ),

            'bar',

            reportData.catLabels,

            reportData
                .catRevenue
                .map(Number),

            'Doanh thu'
        );



        /*
        |--------------------------------------------------------------------------
        | 30 DAYS
        |--------------------------------------------------------------------------
        */

        createChart(

            document.getElementById(
                'revenueByDateChart'
            ),

            'line',

            reportData.revDateLabels,

            reportData
                .revDateData
                .map(Number),

            'Doanh thu'
        );



        /*
        |--------------------------------------------------------------------------
        | 12 MONTHS
        |--------------------------------------------------------------------------
        */

        createChart(

            document.getElementById(
                'revenueByMonthChart'
            ),

            'bar',

            reportData.revMonthLabels,

            reportData
                .revMonthData
                .map(Number),

            'Doanh thu'
        );



        /*
        |--------------------------------------------------------------------------
        | YEAR
        |--------------------------------------------------------------------------
        */

        createChart(

            document.getElementById(
                'revenueByYearChart'
            ),

            'bar',

            reportData.revYearLabels,

            reportData
                .revYearData
                .map(Number),

            'Doanh thu'
        );



        /*
        |--------------------------------------------------------------------------
        | MOMO / COD
        |--------------------------------------------------------------------------
        */

        const paymentCanvas =
            document.getElementById(
                'revenueByPaymentMethodChart'
            );


        if (paymentCanvas) {

            new Chart(
                paymentCanvas,
                {

                    type:
                        'pie',


                    data: {

                        labels:
                            reportData
                                .paymentMethodLabels,


                        datasets: [

                            {

                                label:
                                    'Doanh thu',

                                data:
                                    reportData
                                        .paymentMethodRevenue
                                        .map(Number),

                                borderWidth:
                                    1
                            }
                        ]
                    },


                    options: {

                        responsive:
                            true,

                        maintainAspectRatio:
                            false,


                        plugins: {

                            tooltip: {

                                callbacks: {

                                    label:
                                        function (
                                            context
                                        ) {

                                            return (
                                                context
                                                    .label
                                                +
                                                ': '
                                                +
                                                moneyFormatter
                                                    .format(
                                                        context
                                                            .raw
                                                    )
                                            );
                                        }
                                }
                            }
                        }
                    }
                }
            );
        }

    }
);

</script>


@endpush