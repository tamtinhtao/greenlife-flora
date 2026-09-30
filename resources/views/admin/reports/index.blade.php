@extends('layouts.admin')


@section(
    'title',
    'Báo cáo thống kê - GreenLife Admin'
)


@section(
    'page-title',
    'Báo cáo - Thống kê'
)


@section(
    'page-subtitle',
    'Tổng hợp đơn hàng, khách hàng và doanh thu'
)



@push('styles')

<style>

    .report-page-header {

        display: flex;

        justify-content:
            space-between;

        align-items: center;

        flex-wrap: wrap;

        gap: 15px;

        margin-bottom: 22px;
    }


    .report-title {

        margin: 0 0 4px;

        font-size: 27px;

        font-weight: 500;
    }


    .report-description {

        margin: 0;

        color: #8997a4;
    }


    .report-stat-card {

        height: 100%;

        background: white;

        border:
            1px solid #e1e5e8;

        box-shadow:
            0 1px 4px
            rgba(0,0,0,.05);
    }


    .report-stat-body {

        padding: 20px;
    }


    .report-stat-label {

        color: #77848e;

        font-size: 13px;
    }


    .report-stat-number {

        margin:
            7px 0 4px;

        font-size: 28px;

        font-weight: 700;
    }


    .report-section {

        margin-bottom: 24px;

        background: white;

        border:
            1px solid #e1e5e8;

        box-shadow:
            0 1px 4px
            rgba(0,0,0,.05);
    }


    .report-section-header {

        padding: 15px 18px;

        border-bottom:
            1px solid #e5e8eb;
    }


    .report-section-title {

        font-size: 17px;

        font-weight: 700;
    }


    .report-table {

        margin-bottom: 0;
    }


    .report-table thead th {

        background: #eef2f5;

        white-space: nowrap;
    }


    .money {

        color: #198754;

        font-weight: 700;
    }

</style>

@endpush



@section('content')


<div class="report-page-header">


    <div>

        <h1 class="report-title">

            Báo cáo thống kê

        </h1>


        <p class="report-description">

            Tổng hợp kết quả kinh doanh
            của GreenLife Flora.

        </p>

    </div>



    <a
        href="{{
            route(
                'admin.reports.charts'
            )
        }}"
        class="btn btn-primary"
    >

        📊 Xem biểu đồ

    </a>

</div>



{{-- =========================================================
     SUMMARY
========================================================= --}}

<div class="row g-4 mb-4">


    <div class="col-md-4">

        <div class="report-stat-card">

            <div class="report-stat-body">

                <div class="report-stat-label">

                    📦 Tổng số đơn hàng

                </div>


                <div class="report-stat-number">

                    {{
                        number_format(
                            $totalOrders
                        )
                    }}

                </div>


                <small class="text-muted">

                    Tổng số đơn đã được tạo

                </small>

            </div>

        </div>

    </div>



    <div class="col-md-4">

        <div class="report-stat-card">

            <div class="report-stat-body">

                <div class="report-stat-label">

                    👥 Tổng khách hàng

                </div>


                <div class="report-stat-number">

                    {{
                        number_format(
                            $totalCustomers
                        )
                    }}

                </div>


                <small class="text-muted">

                    Các tài khoản có vai trò User

                </small>

            </div>

        </div>

    </div>



    <div class="col-md-4">

        <div class="report-stat-card">

            <div class="report-stat-body">

                <div class="report-stat-label">

                    💰 Tổng doanh thu

                </div>


                <div
                    class="
                        report-stat-number
                        text-success
                    "
                >

                    {{
                        number_format(
                            $totalRevenue,
                            0,
                            ',',
                            '.'
                        )
                    }} đ

                </div>


                <small class="text-muted">

                    Doanh thu của các đơn hợp lệ

                </small>

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
     CATEGORY
========================================================= --}}

<div class="report-section">


    <div class="report-section-header">

        <div class="report-section-title">

            🌿 Doanh thu theo danh mục

        </div>


        <div class="small text-muted">

            Chỉ tính giá trị sản phẩm,
            không bao gồm phí vận chuyển.

        </div>

    </div>



    <div class="table-responsive">

        <table
            class="
                table
                table-hover
                align-middle
                report-table
            "
        >

            <thead>

                <tr>

                    <th>
                        #
                    </th>

                    <th>
                        Danh mục
                    </th>

                    <th class="text-end">
                        Số lượng bán
                    </th>

                    <th class="text-end">
                        Doanh thu sản phẩm
                    </th>

                </tr>

            </thead>


            <tbody>


            @forelse(
                $categoryRevenue
                as $index => $revenue
            )

                <tr>


                    <td>

                        {{ $index + 1 }}

                    </td>


                    <td>

                        <strong>

                            {{
                                $revenue
                                    ->category_name
                                ??
                                (
                                    'Danh mục #'
                                    .
                                    $revenue
                                        ->category_id
                                )
                            }}

                        </strong>

                    </td>


                    <td class="text-end">

                        {{
                            number_format(
                                $revenue
                                    ->total_qty
                            )
                        }}

                    </td>


                    <td
                        class="
                            text-end
                            money
                        "
                    >

                        {{
                            number_format(
                                $revenue
                                    ->total_revenue,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </td>

                </tr>


            @empty


                <tr>

                    <td
                        colspan="4"
                        class="
                            text-center
                            text-muted
                            py-4
                        "
                    >

                        Chưa có dữ liệu doanh thu
                        theo danh mục.

                    </td>

                </tr>


            @endforelse


            </tbody>

        </table>

    </div>

</div>



{{-- =========================================================
     DATE
========================================================= --}}

<div class="report-section">


    <div class="report-section-header">

        <div class="report-section-title">

            📅 Doanh thu theo ngày

        </div>

    </div>



    <div class="table-responsive">

        <table
            class="
                table
                table-striped
                align-middle
                report-table
            "
        >

            <thead>

                <tr>

                    <th>
                        Ngày
                    </th>

                    <th class="text-end">
                        Số đơn
                    </th>

                    <th class="text-end">
                        Doanh thu
                    </th>

                </tr>

            </thead>


            <tbody>


            @forelse(
                $revenueByDate
                as $row
            )

                <tr>


                    <td>

                        {{
                            \Carbon\Carbon::parse(
                                $row->date
                            )->format(
                                'd/m/Y'
                            )
                        }}

                    </td>


                    <td class="text-end">

                        {{
                            number_format(
                                $row
                                    ->order_count
                            )
                        }}

                    </td>


                    <td
                        class="
                            text-end
                            money
                        "
                    >

                        {{
                            number_format(
                                $row
                                    ->total_revenue,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </td>

                </tr>


            @empty


                <tr>

                    <td
                        colspan="3"
                        class="
                            text-center
                            text-muted
                            py-4
                        "
                    >

                        Chưa có doanh thu theo ngày.

                    </td>

                </tr>


            @endforelse


            </tbody>

        </table>

    </div>

</div>



{{-- =========================================================
     MONTH
========================================================= --}}

<div class="report-section">


    <div class="report-section-header">

        <div class="report-section-title">

            🗓️ Doanh thu theo tháng

        </div>

    </div>



    <div class="table-responsive">

        <table
            class="
                table
                table-striped
                align-middle
                report-table
            "
        >

            <thead>

                <tr>

                    <th>
                        Tháng
                    </th>

                    <th class="text-end">
                        Số đơn
                    </th>

                    <th class="text-end">
                        Doanh thu
                    </th>

                </tr>

            </thead>


            <tbody>


            @forelse(
                $revenueByMonth
                as $row
            )


                @php

                    [
                        $year,
                        $month
                    ] =
                        explode(
                            '-',
                            $row->month
                        );

                @endphp


                <tr>


                    <td>

                        {{ $month }}/{{ $year }}

                    </td>


                    <td class="text-end">

                        {{
                            number_format(
                                $row
                                    ->order_count
                            )
                        }}

                    </td>


                    <td
                        class="
                            text-end
                            money
                        "
                    >

                        {{
                            number_format(
                                $row
                                    ->total_revenue,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </td>

                </tr>


            @empty


                <tr>

                    <td
                        colspan="3"
                        class="
                            text-center
                            text-muted
                            py-4
                        "
                    >

                        Chưa có doanh thu theo tháng.

                    </td>

                </tr>


            @endforelse


            </tbody>

        </table>

    </div>

</div>



{{-- =========================================================
     YEAR
========================================================= --}}

<div class="report-section">


    <div class="report-section-header">

        <div class="report-section-title">

            📈 Doanh thu theo năm

        </div>

    </div>



    <div class="table-responsive">

        <table
            class="
                table
                table-striped
                align-middle
                report-table
            "
        >

            <thead>

                <tr>

                    <th>
                        Năm
                    </th>

                    <th class="text-end">
                        Số đơn
                    </th>

                    <th class="text-end">
                        Doanh thu
                    </th>

                </tr>

            </thead>


            <tbody>


            @forelse(
                $revenueByYear
                as $row
            )

                <tr>


                    <td>

                        <strong>
                            {{ $row->year }}
                        </strong>

                    </td>


                    <td class="text-end">

                        {{
                            number_format(
                                $row
                                    ->order_count
                            )
                        }}

                    </td>


                    <td
                        class="
                            text-end
                            money
                        "
                    >

                        {{
                            number_format(
                                $row
                                    ->total_revenue,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </td>

                </tr>


            @empty


                <tr>

                    <td
                        colspan="3"
                        class="
                            text-center
                            text-muted
                            py-4
                        "
                    >

                        Chưa có doanh thu theo năm.

                    </td>

                </tr>


            @endforelse


            </tbody>

        </table>

    </div>

</div>



<div class="report-section mb-0">

    <div
        class="
            p-3
            d-flex
            justify-content-between
            align-items-center
            flex-wrap
            gap-3
        "
    >

        <div>

            <strong>

                📊 Báo cáo trực quan

            </strong>


            <div class="small text-muted">

                Xem biểu đồ doanh thu theo
                danh mục, ngày, tháng, năm
                và phương thức thanh toán.

            </div>

        </div>


        <a
            href="{{
                route(
                    'admin.reports.charts'
                )
            }}"
            class="btn btn-primary"
        >

            📊 Xem biểu đồ

        </a>

    </div>

</div>


@endsection