@extends('layouts.admin')


@section(
    'title',
    'Quản lý đơn hàng - GreenLife Admin'
)


@section(
    'page-title',
    'Quản lý đơn hàng'
)


@section(
    'page-subtitle',
    'Theo dõi và xử lý đơn hàng của khách hàng'
)



{{-- =========================================================
     CSS RIÊNG TRANG ĐƠN HÀNG
========================================================= --}}

@push('styles')

<style>

    /* =====================================================
       PAGE HEADER
    ===================================================== */

    .order-page-header {
        margin-bottom: 20px;
    }


    .order-page-header h1 {

        margin: 0 0 5px;

        font-size: 27px;

        font-weight: 500;
    }


    .order-page-header p {

        margin: 0;

        color: #8997a4;
    }



    /* =====================================================
       FILTER BOX
    ===================================================== */

    .order-filter-card {

        background: white;

        border:
            1px solid #e2e6e9;

        box-shadow:
            0 1px 4px
            rgba(0,0,0,.04);

        margin-bottom: 20px;
    }


    .order-filter-body {

        padding: 17px;
    }



    /* =====================================================
       TABS
    ===================================================== */

    .order-tabs {

        display: flex;

        flex-wrap: wrap;

        gap: 7px;

        margin-bottom: 18px;

        padding-bottom: 15px;

        border-bottom:
            1px solid #e7e9eb;
    }


    .order-tab {

        display: inline-flex;

        align-items: center;

        gap: 6px;

        padding: 8px 13px;

        border-radius: 5px;

        border:
            1px solid #dfe3e6;

        background: #fff;

        color: #56616b;

        text-decoration: none;

        font-size: 13px;

        transition: .15s;
    }


    .order-tab:hover {

        background: #f3f6f8;

        color: #333;
    }


    .order-tab.active {

        color: white;

        background: #3892bf;

        border-color: #3892bf;
    }


    .order-tab-count {

        min-width: 20px;

        height: 20px;

        padding: 0 6px;

        display: inline-flex;

        align-items: center;

        justify-content: center;

        border-radius: 10px;

        background:
            rgba(0,0,0,.08);

        font-size: 11px;
    }


    .order-tab.active
    .order-tab-count {

        background:
            rgba(
                255,
                255,
                255,
                .22
            );
    }



    /* =====================================================
       SEARCH
    ===================================================== */

    .order-filter-grid {

        display: grid;

        grid-template-columns:
            minmax(260px, 1.5fr)
            minmax(180px, .7fr)
            auto;

        gap: 12px;

        align-items: end;
    }


    .filter-label {

        display: block;

        margin-bottom: 6px;

        color: #6c757d;

        font-size: 12px;

        font-weight: 600;
    }



    /* =====================================================
       BULK
    ===================================================== */

    .bulk-card {

        display: flex;

        align-items: center;

        justify-content: space-between;

        gap: 15px;

        flex-wrap: wrap;

        padding: 13px 15px;

        margin-bottom: 16px;

        background: #eef5f9;

        border:
            1px solid #d8e4eb;

        border-radius: 5px;
    }


    .bulk-left {

        display: flex;

        align-items: center;

        gap: 14px;
    }


    .bulk-right {

        display: flex;

        align-items: center;

        gap: 8px;

        flex-wrap: wrap;
    }


    .bulk-status-select {

        min-width: 190px;
    }


    #selected-count {

        font-weight: bold;

        color: #3892bf;
    }



    /* =====================================================
       TABLE
    ===================================================== */

    .orders-card {

        background: white;

        border:
            1px solid #e1e5e8;

        box-shadow:
            0 1px 4px
            rgba(0,0,0,.05);
    }


    .orders-table {

        min-width: 1450px;

        margin-bottom: 0;
    }


    .orders-table th {

        vertical-align: middle;

        background: #eef2f5 !important;

        font-size: 12px;

        color: #3e4953;

        white-space: nowrap;
    }


    .orders-table td {

        vertical-align: middle;

        font-size: 12px;
    }


    .orders-table tr.selected-row {

        background: #f1f9fd;
    }


    .order-id {

        color: #2787b6;

        font-weight: bold;

        white-space: nowrap;
    }


    .customer-name {

        font-weight: 600;

        color: #333;
    }


    .customer-sub {

        margin-top: 3px;

        color: #89939c;

        font-size: 11px;
    }


    .address-cell {

        min-width: 185px;

        max-width: 230px;
    }


    .product-list {

        min-width: 170px;

        margin: 0;

        padding-left: 18px;
    }


    .product-list li {

        margin-bottom: 4px;
    }


    .order-total {

        color: #dc3545;

        font-weight: bold;

        white-space: nowrap;
    }



    /* =====================================================
       STATUS BADGE
    ===================================================== */

    .shipping-badge {

        display: inline-block;

        padding: 5px 8px;

        border-radius: 4px;

        font-size: 11px;

        font-weight: 600;

        white-space: nowrap;
    }


    .shipping-pending {

        background: #fff3cd;

        color: #8a6d00;
    }


    .shipping-ready {

        background: #cff4fc;

        color: #087990;
    }


    .shipping-picking {

        background: #cfe2ff;

        color: #084298;
    }


    .shipping-delivering {

        background: #dbeafe;

        color: #1d4ed8;
    }


    .shipping-delivered {

        background: #d1e7dd;

        color: #0f5132;
    }


    .shipping-return {

        background: #ffe5d0;

        color: #a14b00;
    }


    .shipping-cancelled {

        background: #f8d7da;

        color: #842029;
    }



    /* =====================================================
       GHN
    ===================================================== */

    .ghn-code {

        display: inline-block;

        padding: 4px 7px;

        border-radius: 4px;

        background: #e6f4fb;

        color: #167ca8;

        font-weight: 600;

        white-space: nowrap;
    }



    /* =====================================================
       ACTION
    ===================================================== */

    .inline-status-form {

        min-width: 170px;
    }


    .inline-status-form
    .form-select {

        font-size: 12px;
    }


    /* =====================================================
       PAGINATION
    ===================================================== */

    .orders-pagination {

        padding: 16px;

        border-top:
            1px solid #e5e8eb;
    }


    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media (max-width: 1000px) {

        .order-filter-grid {

            grid-template-columns: 1fr;
        }

    }

</style>

@endpush



@section('content')


@php

    /*
    |--------------------------------------------------------------------------
    | FALLBACK CHO CÁC BIẾN
    |--------------------------------------------------------------------------
    |
    | Controller hiện tại đã truyền các biến này.
    | Phần fallback chỉ để tránh View lỗi nếu thiếu một biến.
    |
    */

    $currentTab =
        $currentTab
        ?? request('tab', 'all');


    $tabCounts =
        $tabCounts
        ?? [];


    $paymentLabels =
        $paymentLabels
        ?? [
            'cod' => 'COD',
            'momo' => 'MoMo',
        ];


    $tabs =
        $tabs
        ?? [

            'all' => [
                'label' =>
                    'Tất cả',

                'statuses' =>
                    [],
            ],

            'pending' => [
                'label' =>
                    'Chờ xác nhận',

                'statuses' => [
                    'pending',
                    'not_shipped',
                    'processing',
                ],
            ],

            'ready' => [
                'label' =>
                    'Chờ lấy hàng',

                'statuses' => [
                    'ready_to_pick',
                ],
            ],

            'picking' => [
                'label' =>
                    'Đang lấy hàng',

                'statuses' => [
                    'picking',
                ],
            ],

            'delivering' => [
                'label' =>
                    'Đang giao',

                'statuses' => [
                    'delivering',
                    'picked',
                    'storing',
                    'transporting',
                    'sorting',
                ],
            ],

            'delivered' => [
                'label' =>
                    'Thành công',

                'statuses' => [
                    'delivered',
                ],
            ],

            'return' => [
                'label' =>
                    'Hoàn hàng',

                'statuses' => [
                    'return',
                    'returning',
                    'returned',
                    'return_transporting',
                    'return_sorting',
                ],
            ],

            'cancelled' => [
                'label' =>
                    'Đã hủy',

                'statuses' => [
                    'cancelled',
                ],
            ],
        ];

@endphp



{{-- =========================================================
     HEADER
========================================================= --}}

<div class="order-page-header">

    <h1>
        Quản lý đơn hàng
    </h1>

    <p>
        Theo dõi, tìm kiếm, cập nhật hàng loạt
        và đồng bộ trạng thái vận chuyển GHN.
    </p>

</div>



{{-- =========================================================
     FILTER
========================================================= --}}

<div class="order-filter-card">

    <div class="order-filter-body">


        {{-- TABS --}}
        <div class="order-tabs">

            @foreach(
                $tabs
                as $tabKey => $tabData
            )

                <a
                    href="{{
                        route(
                            'admin.orders.index',
                            array_merge(
                                request()->except([
                                    'page',
                                    'tab'
                                ]),
                                [
                                    'tab' =>
                                        $tabKey
                                ]
                            )
                        )
                    }}"
                    class="
                        order-tab

                        {{
                            $currentTab
                            ===
                            $tabKey

                                ? 'active'
                                : ''
                        }}
                    "
                >

                    {{
                        $tabData['label']
                        ?? $tabKey
                    }}


                    <span class="order-tab-count">

                        {{
                            $tabCounts[$tabKey]
                            ?? 0
                        }}

                    </span>

                </a>

            @endforeach

        </div>



        {{-- SEARCH FILTER --}}
        <form
            action="{{ route('admin.orders.index') }}"
            method="GET"
        >


            <input
                type="hidden"
                name="tab"
                value="{{ $currentTab }}"
            >


            <div class="order-filter-grid">


                {{-- SEARCH --}}
                <div>

                    <label class="filter-label">

                        Tìm kiếm đơn hàng

                    </label>


                    <input
                        type="text"
                        name="keyword"
                        class="form-control"
                        placeholder="
                            Mã đơn, khách hàng,
                            SĐT, email, mã GHN...
                        "
                        value="{{ request('keyword') }}"
                    >

                </div>



                {{-- PAYMENT --}}
                <div>

                    <label class="filter-label">

                        Phương thức thanh toán

                    </label>


                    <select
                        name="payment_method"
                        class="form-select"
                    >

                        <option value="">

                            Tất cả phương thức

                        </option>


                        @foreach(
                            $paymentLabels
                            as $paymentValue
                            => $paymentLabel
                        )

                            <option
                                value="{{ $paymentValue }}"

                                @selected(
                                    request(
                                        'payment_method'
                                    )
                                    ===
                                    $paymentValue
                                )
                            >

                                {{ $paymentLabel }}

                            </option>

                        @endforeach

                    </select>

                </div>



                {{-- BUTTON --}}
                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-primary"
                    >

                        🔍 Tìm kiếm

                    </button>


                    <a
                        href="{{
                            route(
                                'admin.orders.index',
                                [
                                    'tab' =>
                                        $currentTab
                                ]
                            )
                        }}"
                        class="
                            btn
                            btn-outline-secondary
                        "
                    >

                        Xóa lọc

                    </a>

                </div>

            </div>

        </form>

    </div>

</div>



{{-- =========================================================
     BULK UPDATE FORM
========================================================= --}}

<form
    id="bulk-order-form"
    action="{{ route('admin.orders.bulkUpdate') }}"
    method="POST"
>

    @csrf


    <div class="bulk-card">


        <div class="bulk-left">


            <label
                class="
                    d-flex
                    align-items-center
                    gap-2
                    mb-0
                "
            >

                <input
                    type="checkbox"
                    id="select-all-orders"
                    class="form-check-input mt-0"
                >

                <span>
                    Chọn tất cả
                </span>

            </label>


            <span>

                Đã chọn

                <span id="selected-count">
                    0
                </span>

                đơn

            </span>

        </div>



        <div class="bulk-right">


            <select
                name="shipping_status"
                class="
                    form-select
                    form-select-sm
                    bulk-status-select
                "
                id="bulk-shipping-status"
            >

                <option value="">

                    -- Chọn trạng thái --

                </option>


                <option value="pending">

                    Chờ xác nhận

                </option>


                <option value="ready_to_pick">

                    Chờ lấy hàng

                </option>


                <option value="picking">

                    Đang lấy hàng

                </option>


                <option value="delivering">

                    Đang giao

                </option>


                <option value="delivered">

                    Thành công

                </option>


                <option value="return">

                    Hoàn hàng

                </option>


                <option value="cancelled">

                    Đã hủy

                </option>

            </select>



            {{-- UPDATE BULK --}}
            <button
                type="submit"
                class="btn btn-primary btn-sm"
                id="bulk-update-btn"
            >

                ✓ Cập nhật đã chọn

            </button>



            {{-- SYNC GHN --}}
            <button
                type="submit"
                formaction="{{
                    route(
                        'admin.orders.syncGhn'
                    )
                }}"
                class="btn btn-success btn-sm"
                id="sync-ghn-btn"
                formnovalidate
            >

                ↻ Đồng bộ GHN

            </button>

        </div>

    </div>

</form>



{{-- =========================================================
     TABLE
========================================================= --}}

<div class="orders-card">

    <div class="table-responsive">

        <table
            class="
                table
                table-hover
                align-middle
                orders-table
            "
        >


            <thead>

                <tr>


                    <th style="width: 42px;">

                    </th>


                    <th>
                        Mã đơn
                    </th>


                    <th>
                        Khách hàng
                    </th>


                    <th>
                        Địa chỉ / SĐT
                    </th>


                    <th>
                        Sản phẩm
                    </th>


                    <th>
                        Thanh toán
                    </th>


                    <th>
                        Phí ship
                    </th>


                    <th>
                        Tổng tiền
                    </th>


                    <th>
                        Mã GHN
                    </th>


                    <th>
                        Trạng thái giao hàng
                    </th>


                    <th>
                        Cập nhật
                    </th>


                    <th>
                        Chi tiết
                    </th>

                </tr>

            </thead>



            <tbody>


            @forelse(
                $orders
                as $order
            )


                @php

                    /*
                    |--------------------------------------------------------------------------
                    | SHIPPING STATUS
                    |--------------------------------------------------------------------------
                    */

                    $shippingStatus =
                        $order
                            ->shipping_status
                        ?? 'pending';



                    /*
                    |--------------------------------------------------------------------------
                    | BADGE + LABEL
                    |--------------------------------------------------------------------------
                    */

                    if (
                        in_array(
                            $shippingStatus,
                            [
                                'pending',
                                'not_shipped',
                                'processing',
                            ]
                        )
                    ) {

                        $shippingLabel =
                            'Chờ xác nhận';

                        $shippingClass =
                            'shipping-pending';


                    } elseif (
                        $shippingStatus
                        ===
                        'ready_to_pick'
                    ) {

                        $shippingLabel =
                            'Chờ lấy hàng';

                        $shippingClass =
                            'shipping-ready';


                    } elseif (
                        $shippingStatus
                        ===
                        'picking'
                    ) {

                        $shippingLabel =
                            'Đang lấy hàng';

                        $shippingClass =
                            'shipping-picking';


                    } elseif (
                        in_array(
                            $shippingStatus,
                            [
                                'delivering',
                                'picked',
                                'storing',
                                'transporting',
                                'sorting',
                            ]
                        )
                    ) {

                        $shippingLabel =
                            'Đang giao';

                        $shippingClass =
                            'shipping-delivering';


                    } elseif (
                        $shippingStatus
                        ===
                        'delivered'
                    ) {

                        $shippingLabel =
                            'Thành công';

                        $shippingClass =
                            'shipping-delivered';


                    } elseif (
                        in_array(
                            $shippingStatus,
                            [
                                'return',
                                'returning',
                                'returned',
                                'return_transporting',
                                'return_sorting',
                            ]
                        )
                    ) {

                        $shippingLabel =
                            'Hoàn hàng';

                        $shippingClass =
                            'shipping-return';


                    } elseif (
                        $shippingStatus
                        ===
                        'cancelled'
                    ) {

                        $shippingLabel =
                            'Đã hủy';

                        $shippingClass =
                            'shipping-cancelled';


                    } else {

                        $shippingLabel =
                            $shippingStatus;

                        $shippingClass =
                            'shipping-pending';
                    }



                    /*
                    |--------------------------------------------------------------------------
                    | PAYMENT METHOD
                    |--------------------------------------------------------------------------
                    */

                    $paymentMethod =
                        strtolower(
                            $order
                                ->payment_method
                            ?? ''
                        );


                    $paymentText =
                        $paymentLabels[
                            $paymentMethod
                        ]
                        ??
                        strtoupper(
                            $paymentMethod
                        )
                        ??
                        '---';

                @endphp



                <tr
                    id="order-row-{{ $order->id }}"
                >


                    {{-- CHECKBOX --}}
                    <td>

                        <input
                            type="checkbox"
                            name="order_ids[]"
                            value="{{ $order->id }}"
                            class="
                                form-check-input
                                order-checkbox
                            "
                            form="bulk-order-form"
                        >

                    </td>



                    {{-- ORDER --}}
                    <td>

                        <span class="order-id">

                            #{{ $order->id }}

                        </span>


                        <div class="customer-sub">

                            {{
                                optional(
                                    $order->created_at
                                )->format(
                                    'd/m/Y H:i'
                                )
                            }}

                        </div>

                    </td>



                    {{-- CUSTOMER --}}
                    <td>

                        <div class="customer-name">

                            {{
                                $order
                                    ->customer_name
                                ?? '---'
                            }}

                        </div>


                        <div class="customer-sub">

                            {{
                                $order
                                    ->customer_email
                                ?? ''
                            }}

                        </div>

                    </td>



                    {{-- ADDRESS --}}
                    <td class="address-cell">


                        <div>

                            📞

                            {{
                                $order
                                    ->customer_phone
                                ?? '---'
                            }}

                        </div>


                        <div
                            class="
                                customer-sub
                                mt-1
                            "
                        >

                            🏠

                            {{
                                $order
                                    ->shipping_address
                                ?? '---'
                            }}

                        </div>

                    </td>



                    {{-- PRODUCTS --}}
                    <td>

                        <ul class="product-list">


                        @forelse(
                            $order->orderItems
                            as $item
                        )

                            <li>

                                {{
                                    $item
                                        ->product
                                        ?->name
                                    ??
                                    'Sản phẩm đã xóa'
                                }}

                                ×

                                {{
                                    $item
                                        ->quantity
                                }}

                            </li>

                        @empty

                            <li class="text-muted">

                                Không có dữ liệu

                            </li>

                        @endforelse


                        </ul>

                    </td>



                    {{-- PAYMENT --}}
                    <td>

                        @if(
                            $paymentMethod
                            ===
                            'momo'
                        )

                            <span
                                class="
                                    badge
                                    bg-danger
                                "
                            >

                                MoMo

                            </span>

                        @elseif(
                            $paymentMethod
                            ===
                            'cod'
                        )

                            <span
                                class="
                                    badge
                                    bg-warning
                                    text-dark
                                "
                            >

                                COD

                            </span>

                        @else

                            <span
                                class="
                                    badge
                                    bg-secondary
                                "
                            >

                                {{
                                    $paymentText
                                    ?: '---'
                                }}

                            </span>

                        @endif

                    </td>



                    {{-- SHIPPING FEE --}}
                    <td>

                        @if(
                            ($order
                                ->ghn_total_fee
                            ?? 0)
                            > 0
                        )

                            <strong
                                class="
                                    text-primary
                                "
                            >

                                {{
                                    number_format(
                                        $order
                                            ->ghn_total_fee,
                                        0,
                                        ',',
                                        '.'
                                    )
                                }} đ

                            </strong>

                        @else

                            <span
                                class="
                                    text-muted
                                "
                            >

                                ---

                            </span>

                        @endif

                    </td>



                    {{-- TOTAL --}}
                    <td>

                        <span class="order-total">

                            {{
                                number_format(
                                    $order
                                        ->total_price
                                    ?? 0,
                                    0,
                                    ',',
                                    '.'
                                )
                            }} đ

                        </span>

                    </td>



                    {{-- GHN CODE --}}
                    <td>

                        @if(
                            $order
                                ->ghn_order_code
                        )

                            <span class="ghn-code">

                                {{
                                    $order
                                        ->ghn_order_code
                                }}

                            </span>

                        @else

                            <span
                                class="text-muted"
                            >

                                Chưa có

                            </span>

                        @endif

                    </td>



                    {{-- SHIPPING STATUS --}}
                    <td>

                        <span
                            class="
                                shipping-badge
                                {{ $shippingClass }}
                            "
                        >

                            {{ $shippingLabel }}

                        </span>

                    </td>



                    {{-- INLINE UPDATE --}}
                    <td>

                        <form
                            action="{{
                                route(
                                    'admin.orders.updateStatus',
                                    $order->id
                                )
                            }}"
                            method="POST"
                            class="inline-status-form"
                        >

                            @csrf


                            <select
                                name="shipping_status"
                                class="
                                    form-select
                                    form-select-sm
                                    mb-2
                                "
                            >


                                <option
                                    value="pending"

                                    @selected(
                                        in_array(
                                            $shippingStatus,
                                            [
                                                'pending',
                                                'not_shipped',
                                                'processing'
                                            ]
                                        )
                                    )
                                >

                                    Chờ xác nhận

                                </option>



                                <option
                                    value="ready_to_pick"

                                    @selected(
                                        $shippingStatus
                                        ===
                                        'ready_to_pick'
                                    )
                                >

                                    Chờ lấy hàng

                                </option>



                                <option
                                    value="picking"

                                    @selected(
                                        $shippingStatus
                                        ===
                                        'picking'
                                    )
                                >

                                    Đang lấy hàng

                                </option>



                                <option
                                    value="delivering"

                                    @selected(
                                        in_array(
                                            $shippingStatus,
                                            [
                                                'delivering',
                                                'picked',
                                                'storing',
                                                'transporting',
                                                'sorting'
                                            ]
                                        )
                                    )
                                >

                                    Đang giao

                                </option>



                                <option
                                    value="delivered"

                                    @selected(
                                        $shippingStatus
                                        ===
                                        'delivered'
                                    )
                                >

                                    Thành công

                                </option>



                                <option
                                    value="return"

                                    @selected(
                                        in_array(
                                            $shippingStatus,
                                            [
                                                'return',
                                                'returning',
                                                'returned',
                                                'return_transporting',
                                                'return_sorting'
                                            ]
                                        )
                                    )
                                >

                                    Hoàn hàng

                                </option>



                                <option
                                    value="cancelled"

                                    @selected(
                                        $shippingStatus
                                        ===
                                        'cancelled'
                                    )
                                >

                                    Đã hủy

                                </option>

                            </select>


                            <button
                                type="submit"
                                class="
                                    btn
                                    btn-primary
                                    btn-sm
                                    w-100
                                "
                                onclick="
                                    return confirm(
                                        'Bạn muốn cập nhật trạng thái đơn #{{ $order->id }}?'
                                    );
                                "
                            >

                                Lưu

                            </button>

                        </form>

                    </td>



                    {{-- DETAIL --}}
                    <td>

                        <a
                            href="{{
                                route(
                                    'admin.orders.show',
                                    $order->id
                                )
                            }}"
                            class="
                                btn
                                btn-outline-primary
                                btn-sm
                            "
                        >

                            Xem

                        </a>

                    </td>

                </tr>


            @empty


                <tr>

                    <td
                        colspan="12"
                        class="
                            text-center
                            py-5
                            text-muted
                        "
                    >

                        Không có đơn hàng phù hợp.

                    </td>

                </tr>


            @endforelse


            </tbody>

        </table>

    </div>



    {{-- =====================================================
         PAGINATION
    ===================================================== --}}

    @if(
        method_exists(
            $orders,
            'links'
        )
    )

        <div class="orders-pagination">

            {{
                $orders->links()
            }}

        </div>

    @endif

</div>


@endsection



{{-- =========================================================
     JAVASCRIPT
========================================================= --}}

@push('scripts')

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        const selectAll =
            document.getElementById(
                'select-all-orders'
            );


        const checkboxes =
            Array.from(
                document.querySelectorAll(
                    '.order-checkbox'
                )
            );


        const selectedCount =
            document.getElementById(
                'selected-count'
            );


        const bulkForm =
            document.getElementById(
                'bulk-order-form'
            );


        const bulkStatus =
            document.getElementById(
                'bulk-shipping-status'
            );


        const bulkUpdateButton =
            document.getElementById(
                'bulk-update-btn'
            );


        const syncButton =
            document.getElementById(
                'sync-ghn-btn'
            );



        /*
        |--------------------------------------------------------------------------
        | UPDATE COUNT
        |--------------------------------------------------------------------------
        */

        function updateSelectedCount()
        {

            const checked =
                checkboxes.filter(
                    checkbox =>
                        checkbox.checked
                );


            selectedCount.textContent =
                checked.length;


            checkboxes.forEach(
                checkbox => {

                    const row =
                        checkbox.closest('tr');


                    if (!row) {
                        return;
                    }


                    row.classList.toggle(
                        'selected-row',
                        checkbox.checked
                    );
                }
            );


            if (selectAll) {

                selectAll.checked =
                    checkboxes.length > 0
                    &&
                    checked.length
                    ===
                    checkboxes.length;


                selectAll.indeterminate =
                    checked.length > 0
                    &&
                    checked.length
                    <
                    checkboxes.length;
            }
        }



        /*
        |--------------------------------------------------------------------------
        | SELECT ALL
        |--------------------------------------------------------------------------
        */

        if (selectAll) {

            selectAll.addEventListener(
                'change',
                function () {

                    checkboxes.forEach(
                        checkbox => {

                            checkbox.checked =
                                this.checked;
                        }
                    );


                    updateSelectedCount();
                }
            );
        }



        /*
        |--------------------------------------------------------------------------
        | EACH CHECKBOX
        |--------------------------------------------------------------------------
        */

        checkboxes.forEach(
            checkbox => {

                checkbox.addEventListener(
                    'change',
                    updateSelectedCount
                );
            }
        );



        /*
        |--------------------------------------------------------------------------
        | BULK UPDATE VALIDATION
        |--------------------------------------------------------------------------
        */

        if (bulkUpdateButton) {

            bulkUpdateButton.addEventListener(
                'click',
                function (event) {


                    const checked =
                        checkboxes.filter(
                            checkbox =>
                                checkbox.checked
                        );


                    if (
                        checked.length
                        === 0
                    ) {

                        event.preventDefault();

                        alert(
                            'Vui lòng chọn ít nhất một đơn hàng.'
                        );

                        return;
                    }


                    if (
                        !bulkStatus.value
                    ) {

                        event.preventDefault();

                        alert(
                            'Vui lòng chọn trạng thái cần cập nhật.'
                        );

                        return;
                    }


                    const confirmed =
                        confirm(
                            'Cập nhật '
                            +
                            checked.length
                            +
                            ' đơn hàng sang trạng thái đã chọn?'
                        );


                    if (!confirmed) {

                        event.preventDefault();
                    }

                }
            );
        }



        /*
        |--------------------------------------------------------------------------
        | SYNC GHN
        |--------------------------------------------------------------------------
        */

        if (syncButton) {

            syncButton.addEventListener(
                'click',
                function (event) {


                    const checked =
                        checkboxes.filter(
                            checkbox =>
                                checkbox.checked
                        );


                    if (
                        checked.length
                        === 0
                    ) {

                        event.preventDefault();

                        alert(
                            'Hãy chọn ít nhất một đơn để đồng bộ GHN.'
                        );

                        return;
                    }


                    const confirmed =
                        confirm(
                            'Đồng bộ trạng thái GHN cho '
                            +
                            checked.length
                            +
                            ' đơn đã chọn?'
                        );


                    if (!confirmed) {

                        event.preventDefault();
                    }

                }
            );
        }


        updateSelectedCount();

    }
);

</script>

@endpush