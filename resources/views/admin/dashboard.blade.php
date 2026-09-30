@extends('layouts.admin')


@section(
    'title',
    'Dashboard - GreenLife Admin'
)


@section(
    'page-title',
    'Dashboard'
)


@section(
    'page-subtitle',
    'Control panel'
)



@section('content')


{{-- =========================================================
     TITLE
========================================================= --}}

<h1 class="admin-page-title">

    Dashboard

</h1>


<div class="admin-page-description">

    Tổng quan hoạt động của GreenLife Flora

</div>



{{-- =========================================================
     STATISTICS
========================================================= --}}

<div class="dashboard-stats">


    {{-- ORDERS --}}
    <div
        class="
            dashboard-stat
            dashboard-orders
        "
    >

        <div class="dashboard-stat-body">


            <div class="dashboard-stat-number">

                {{ $totalOrders }}

            </div>


            <div class="dashboard-stat-label">

                Đơn hàng

            </div>

        </div>


        <div class="dashboard-stat-icon">

            ▤

        </div>


        <a
            href="{{ route('admin.orders.index') }}"
            class="dashboard-stat-footer"
        >

            Xem chi tiết →

        </a>

    </div>



    {{-- PRODUCTS --}}
    <div
        class="
            dashboard-stat
            dashboard-products
        "
    >

        <div class="dashboard-stat-body">


            <div class="dashboard-stat-number">

                {{ $totalProducts }}

            </div>


            <div class="dashboard-stat-label">

                Sản phẩm

            </div>

        </div>


        <div class="dashboard-stat-icon">

            ▣

        </div>


        <a
            href="{{ route('admin.products.index') }}"
            class="dashboard-stat-footer"
        >

            Xem chi tiết →

        </a>

    </div>



    {{-- CATEGORIES --}}
    <div
        class="
            dashboard-stat
            dashboard-categories
        "
    >

        <div class="dashboard-stat-body">


            <div class="dashboard-stat-number">

                {{ $totalCategories }}

            </div>


            <div class="dashboard-stat-label">

                Danh mục

            </div>

        </div>


        <div class="dashboard-stat-icon">

            ☷

        </div>


        <a
            href="{{ route('admin.categories.index') }}"
            class="dashboard-stat-footer"
        >

            Xem chi tiết →

        </a>

    </div>



    {{-- USERS --}}
    <div
        class="
            dashboard-stat
            dashboard-users
        "
    >

        <div class="dashboard-stat-body">


            <div class="dashboard-stat-number">

                {{ $totalUsers }}

            </div>


            <div class="dashboard-stat-label">

                Khách hàng

            </div>

        </div>


        <div class="dashboard-stat-icon">

            👥

        </div>


        <a
            href="{{ route('admin.users.index') }}"
            class="dashboard-stat-footer"
        >

            Xem chi tiết →

        </a>

    </div>

</div>



{{-- =========================================================
     BOTTOM CONTENT
========================================================= --}}

<div class="dashboard-bottom">


    {{-- =====================================================
         LATEST ORDERS
    ===================================================== --}}

    <section class="dashboard-panel">


        <div class="dashboard-panel-header">


            <span>

                ĐƠN ĐẶT HÀNG MỚI

            </span>


            <a
                href="{{ route('admin.orders.index') }}"
                class="
                    btn
                    btn-info
                    btn-sm
                    text-white
                "
            >

                Xem tất cả

            </a>

        </div>



        <div class="table-responsive">


            <table
                class="
                    table
                    table-hover
                    align-middle
                    mb-0
                "
            >


                <thead>

                    <tr>

                        <th>
                            Mã đơn
                        </th>

                        <th>
                            Khách hàng
                        </th>

                        <th>
                            Trạng thái
                        </th>

                        <th>
                            Ngày đặt
                        </th>

                        <th>
                            Tổng tiền
                        </th>

                        <th>
                            Thao tác
                        </th>

                    </tr>

                </thead>



                <tbody>


                @forelse(
                    $latestOrders
                    as $order
                )


                    @php

                        /*
                        |--------------------------------------------------------------------------
                        | TRẠNG THÁI HIỂN THỊ
                        |--------------------------------------------------------------------------
                        */

                        $shippingStatus =
                            $order->shipping_status
                            ?? 'pending';


                        $statusLabel =
                            match ($shippingStatus) {

                                'ready_to_pick'
                                    =>
                                    'Chờ lấy hàng',

                                'picking'
                                    =>
                                    'Đang lấy hàng',

                                'delivering'
                                    =>
                                    'Đang giao',

                                'delivered'
                                    =>
                                    'Hoàn thành',

                                'cancelled'
                                    =>
                                    'Đã hủy',

                                'return',
                                'returned'
                                    =>
                                    'Hoàn hàng',

                                default
                                    =>
                                    'Chờ xác nhận',
                            };


                        $statusClass =
                            match ($shippingStatus) {

                                'delivered'
                                    =>
                                    'success',

                                'cancelled',
                                'return',
                                'returned'
                                    =>
                                    'danger',

                                'ready_to_pick',
                                'picking',
                                'delivering'
                                    =>
                                    'info',

                                default
                                    =>
                                    'warning',
                            };

                    @endphp



                    <tr>


                        {{-- ID --}}
                        <td>

                            #{{ $order->id }}

                        </td>



                        {{-- CUSTOMER --}}
                        <td>

                            {{
                                $order->customer_name
                                ??
                                $order->user?->name
                                ??
                                'Khách hàng'
                            }}

                        </td>



                        {{-- STATUS --}}
                        <td>

                            <span
                                class="
                                    dashboard-status
                                    {{ $statusClass }}
                                "
                            >

                                {{ $statusLabel }}

                            </span>

                        </td>



                        {{-- DATE --}}
                        <td>

                            {{
                                optional(
                                    $order->created_at
                                )->format(
                                    'd/m/Y'
                                )
                            }}

                        </td>



                        {{-- TOTAL --}}
                        <td>

                            {{
                                number_format(
                                    $order->total_price
                                    ?? 0,
                                    0,
                                    ',',
                                    '.'
                                )
                            }} đ

                        </td>



                        {{-- ACTION --}}
                        <td>

                            {{--
                                Không dùng admin.orders.show
                                vì project hiện tại có thể
                                chưa khai báo route show.

                                Dẫn về quản lý đơn hàng để
                                tránh RouteNotFoundException.
                            --}}

                            <a
                                href="{{ route(
                                    'admin.orders.index',
                                    [
                                        'search'
                                            =>
                                        '#'.$order->id
                                    ]
                                ) }}"
                                class="
                                    btn
                                    btn-info
                                    btn-sm
                                    text-white
                                "
                            >

                                Chi tiết

                            </a>

                        </td>

                    </tr>


                @empty


                    <tr>

                        <td
                            colspan="6"
                            class="
                                text-center
                                text-muted
                                py-4
                            "
                        >

                            Chưa có đơn hàng nào.

                        </td>

                    </tr>


                @endforelse


                </tbody>

            </table>

        </div>

    </section>



    {{-- =====================================================
         SYSTEM INFORMATION
    ===================================================== --}}

    <section
        class="
            dashboard-panel
            orange
        "
    >


        <div class="dashboard-panel-header">

            THÔNG TIN HỆ THỐNG

        </div>



        <div>


            {{-- PRODUCTS --}}
            <div class="dashboard-system-row">

                <span>

                    Sản phẩm

                </span>


                <span class="dashboard-system-value">

                    {{ $totalProducts }}

                </span>

            </div>



            {{-- CATEGORIES --}}
            <div class="dashboard-system-row">

                <span>

                    Danh mục

                </span>


                <span class="dashboard-system-value">

                    {{ $totalCategories }}

                </span>

            </div>



            {{-- ORDERS --}}
            <div class="dashboard-system-row">

                <span>

                    Đơn hàng

                </span>


                <span class="dashboard-system-value">

                    {{ $totalOrders }}

                </span>

            </div>



            {{-- USERS --}}
            <div class="dashboard-system-row">

                <span>

                    Khách hàng

                </span>


                <span class="dashboard-system-value">

                    {{ $totalUsers }}

                </span>

            </div>


        </div>

    </section>

</div>


@endsection