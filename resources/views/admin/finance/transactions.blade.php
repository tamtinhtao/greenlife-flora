@extends('layouts.admin')


@section(
    'title',
    'Giao dịch thanh toán - GreenLife Admin'
)


@section(
    'page-title',
    'Tài chính & Báo cáo'
)


@section(
    'page-subtitle',
    'Danh sách chi tiết giao dịch thanh toán'
)



@push('styles')

<style>

    .finance-tabs {

        display: flex;

        gap: 8px;

        flex-wrap: wrap;

        margin-bottom: 20px;

        padding: 8px;

        background: white;

        border:
            1px solid #e1e5e8;
    }


    .finance-tab {

        padding:
            8px 14px;

        border-radius: 4px;

        color: #50606d;

        text-decoration: none;

        font-size: 13px;
    }


    .finance-tab:hover {

        background: #eef4f7;

        color: #333;
    }


    .finance-tab.active {

        color: white;

        background:
            #3898c4;
    }



    .finance-filter {

        margin-bottom: 20px;

        padding: 18px;

        background: white;

        border:
            1px solid #e1e5e8;

        box-shadow:
            0 1px 3px
            rgba(0,0,0,.04);
    }


    .finance-filter-grid {

        display: grid;

        grid-template-columns:
            repeat(
                4,
                minmax(
                    150px,
                    1fr
                )
            );

        gap: 12px;
    }


    .finance-filter-grid
    .wide {

        grid-column:
            span 2;
    }


    .finance-label {

        display: block;

        margin-bottom: 5px;

        color: #687580;

        font-size: 12px;

        font-weight: 600;
    }


    .finance-actions {

        display: flex;

        gap: 8px;

        margin-top: 14px;
    }



    .transaction-card {

        background: white;

        border:
            1px solid #e1e5e8;

        box-shadow:
            0 1px 3px
            rgba(0,0,0,.04);
    }


    .transaction-header {

        padding: 15px 18px;

        border-bottom:
            1px solid #e5e8eb;

        font-weight: 700;
    }


    .transaction-table {

        min-width: 1200px;

        margin-bottom: 0;
    }


    .transaction-table th {

        background:
            #eef2f5 !important;

        font-size: 12px;
    }


    .transaction-table td {

        vertical-align: middle;

        font-size: 12px;
    }


    .money {

        color: #dc3545;

        font-weight: 700;

        white-space: nowrap;
    }


    .payment-badge {

        display: inline-block;

        padding:
            5px 8px;

        border-radius: 4px;

        font-size: 11px;

        font-weight: 600;
    }


    .pay-paid {

        background:
            #d1e7dd;

        color:
            #0f5132;
    }


    .pay-pending {

        background:
            #fff3cd;

        color:
            #856404;
    }


    .pay-failed {

        background:
            #f8d7da;

        color:
            #842029;
    }


    .pay-refund {

        background:
            #e2d9f3;

        color:
            #4c2a85;
    }


    .pay-other {

        background:
            #e2e3e5;

        color:
            #41464b;
    }


    .cod-update-form {

        min-width: 185px;
    }


    .transaction-pagination {

        padding: 15px;

        border-top:
            1px solid #e5e8eb;
    }


    @media (
        max-width: 1150px
    ) {

        .finance-filter-grid {

            grid-template-columns:
                repeat(
                    2,
                    minmax(0,1fr)
                );
        }
    }

</style>

@endpush



@section('content')


<div class="mb-4">

    <h1 class="admin-page-title">

        Giao dịch thanh toán

    </h1>


    <div class="admin-page-description">

        Tra cứu thanh toán theo đơn hàng
        và cập nhật trạng thái COD.

    </div>

</div>



{{-- =========================================================
     TABS
========================================================= --}}

<div class="finance-tabs">


    <a
        href="{{
            route(
                'admin.finance.index'
            )
        }}"
        class="finance-tab"
    >

        Tổng quan tài chính

    </a>


    <a
        href="{{
            route(
                'admin.finance.transactions'
            )
        }}"
        class="
            finance-tab
            active
        "
    >

        Giao dịch thanh toán

    </a>


    <a
        href="{{
            route(
                'admin.reports.index'
            )
        }}"
        class="finance-tab"
    >

        Báo cáo doanh thu

    </a>

</div>



{{-- =========================================================
     FILTER
========================================================= --}}

<div class="finance-filter">


    <form
        method="GET"
        action="{{
            route(
                'admin.finance.transactions'
            )
        }}"
    >


        <div class="finance-filter-grid">


            <div class="wide">

                <label class="finance-label">

                    Tìm kiếm

                </label>


                <input
                    type="text"
                    name="search"
                    class="form-control"
                    value="{{
                        $filters['search']
                        ?? ''
                    }}"
                    placeholder="Mã đơn, tên, SĐT hoặc email"
                >

            </div>



            <div>

                <label class="finance-label">

                    Từ ngày

                </label>


                <input
                    type="date"
                    name="date_from"
                    class="form-control"
                    value="{{
                        $filters['date_from']
                        ?? ''
                    }}"
                >

            </div>



            <div>

                <label class="finance-label">

                    Đến ngày

                </label>


                <input
                    type="date"
                    name="date_to"
                    class="form-control"
                    value="{{
                        $filters['date_to']
                        ?? ''
                    }}"
                >

            </div>



            <div>

                <label class="finance-label">

                    Tiền từ

                </label>


                <input
                    type="number"
                    name="min_amount"
                    class="form-control"
                    min="0"
                    value="{{
                        $filters['min_amount']
                        ?? ''
                    }}"
                >

            </div>



            <div>

                <label class="finance-label">

                    Tiền đến

                </label>


                <input
                    type="number"
                    name="max_amount"
                    class="form-control"
                    min="0"
                    value="{{
                        $filters['max_amount']
                        ?? ''
                    }}"
                >

            </div>



            <div>

                <label class="finance-label">

                    Phương thức

                </label>


                <select
                    name="gateway"
                    class="form-select"
                >

                    <option value="">

                        Tất cả

                    </option>


                    @foreach(
                        $methods
                        as $key => $label
                    )

                        <option
                            value="{{ $key }}"

                            @selected(
                                (
                                    $filters[
                                        'gateway'
                                    ]
                                    ?? ''
                                )
                                ===
                                $key
                            )
                        >

                            {{ $label }}

                        </option>

                    @endforeach

                </select>

            </div>



            <div>

                <label class="finance-label">

                    Trạng thái

                </label>


                <select
                    name="payment_status"
                    class="form-select"
                >

                    <option value="">

                        Tất cả

                    </option>


                    @foreach(
                        $statuses
                        as $key => $label
                    )

                        <option
                            value="{{ $key }}"

                            @selected(
                                (
                                    $filters[
                                        'payment_status'
                                    ]
                                    ?? ''
                                )
                                ===
                                $key
                            )
                        >

                            {{ $label }}

                        </option>

                    @endforeach

                </select>

            </div>



            <div>

                <label class="finance-label">

                    Sắp xếp

                </label>


                <select
                    name="sort"
                    class="form-select"
                >

                    <option
                        value="newest"

                        @selected(
                            (
                                $filters['sort']
                                ?? 'newest'
                            )
                            ===
                            'newest'
                        )
                    >

                        Mới nhất

                    </option>


                    <option
                        value="oldest"

                        @selected(
                            (
                                $filters['sort']
                                ?? ''
                            )
                            ===
                            'oldest'
                        )
                    >

                        Cũ nhất

                    </option>


                    <option
                        value="amount_desc"

                        @selected(
                            (
                                $filters['sort']
                                ?? ''
                            )
                            ===
                            'amount_desc'
                        )
                    >

                        Tiền cao → thấp

                    </option>


                    <option
                        value="amount_asc"

                        @selected(
                            (
                                $filters['sort']
                                ?? ''
                            )
                            ===
                            'amount_asc'
                        )
                    >

                        Tiền thấp → cao

                    </option>

                </select>

            </div>

        </div>



        <div class="finance-actions">


            <button
                type="submit"
                class="btn btn-primary"
            >

                🔍 Áp dụng bộ lọc

            </button>


            <a
                href="{{
                    route(
                        'admin.finance.transactions'
                    )
                }}"
                class="
                    btn
                    btn-outline-secondary
                "
            >

                Xóa bộ lọc

            </a>

        </div>

    </form>

</div>



{{-- =========================================================
     TABLE
========================================================= --}}

<div class="transaction-card">


    <div class="transaction-header">

        Danh sách giao dịch

        <span
            class="
                text-muted
                fw-normal
                ms-2
            "
        >

            {{
                number_format(
                    $orders->total()
                )
            }}

            đơn

        </span>

    </div>



    <div class="table-responsive">

        <table
            class="
                table
                table-hover
                transaction-table
            "
        >

            <thead>

                <tr>

                    <th>
                        Đơn hàng
                    </th>

                    <th>
                        Khách hàng
                    </th>

                    <th>
                        Phương thức
                    </th>

                    <th>
                        Số tiền
                    </th>

                    <th>
                        Thanh toán
                    </th>

                    <th>
                        Ngày tạo
                    </th>

                    <th>
                        Đã thanh toán lúc
                    </th>

                    <th>
                        Cập nhật COD
                    </th>

                </tr>

            </thead>


            <tbody>


            @forelse(
                $orders
                as $order
            )


                @php

                    $paymentStatus =
                        $order
                            ->payment_status
                        ?? 'pending';


                    $paymentClass =
                        match (
                            $paymentStatus
                        ) {

                            'paid'
                                =>
                                'pay-paid',

                            'pending',
                            'initiated'
                                =>
                                'pay-pending',

                            'failed',
                            'cancelled'
                                =>
                                'pay-failed',

                            'refund_pending',
                            'refunded'
                                =>
                                'pay-refund',

                            default
                                =>
                                'pay-other',
                        };


                    $allowedTransitions =
                        $codTransitions[
                            $paymentStatus
                        ]
                        ?? [];

                @endphp



                <tr>


                    {{-- ORDER --}}
                    <td>

                        <strong>

                            #{{ $order->id }}

                        </strong>


                        <div
                            class="
                                text-muted
                                small
                            "
                        >

                            Đơn:
                            {{
                                $order->status
                                ?? '---'
                            }}

                        </div>

                    </td>



                    {{-- CUSTOMER --}}
                    <td>

                        <strong>

                            {{
                                $order
                                    ->customer_name
                                ?? '---'
                            }}

                        </strong>


                        <div
                            class="
                                text-muted
                                small
                            "
                        >

                            {{
                                $order
                                    ->customer_phone
                                ?? ''
                            }}

                        </div>

                    </td>



                    {{-- GATEWAY --}}
                    <td>

                        @if(
                            $order->gateway
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

                        @elseif(
                            $order->gateway
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

                        @else

                            <span
                                class="
                                    badge
                                    bg-secondary
                                "
                            >

                                Chưa xác định

                            </span>

                        @endif

                    </td>



                    {{-- AMOUNT --}}
                    <td class="money">

                        {{
                            number_format(
                                (float)
                                $order
                                    ->total_price,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </td>



                    {{-- STATUS --}}
                    <td>

                        <span
                            class="
                                payment-badge
                                {{ $paymentClass }}
                            "
                        >

                            {{
                                $statuses[
                                    $paymentStatus
                                ]
                                ??
                                $paymentStatus
                            }}

                        </span>

                    </td>



                    {{-- CREATED --}}
                    <td>

                        {{
                            \Carbon\Carbon::parse(
                                $order
                                    ->created_at
                            )->format(
                                'd/m/Y H:i'
                            )
                        }}

                    </td>



                    {{-- PAID AT --}}
                    <td>

                        @if(
                            $order
                                ->paid_at
                        )

                            {{
                                \Carbon\Carbon::parse(
                                    $order
                                        ->paid_at
                                )->format(
                                    'd/m/Y H:i'
                                )
                            }}

                        @else

                            <span class="text-muted">

                                ---

                            </span>

                        @endif

                    </td>



                    {{-- COD UPDATE --}}
                    <td>


                        @if(
                            $order->gateway
                            ===
                            'cod'
                            &&
                            !empty(
                                $allowedTransitions
                            )
                        )

                            <form
                                action="{{
                                    route(
                                        'admin.finance.update-status',
                                        $order->id
                                    )
                                }}"
                                method="POST"
                                class="cod-update-form"
                            >

                                @csrf
                                @method('PATCH')


                                <input
                                    type="hidden"
                                    name="current_payment_status"
                                    value="{{ $paymentStatus }}"
                                >


                                <input
                                    type="hidden"
                                    name="current_order_status"
                                    value="{{
                                        $order
                                            ->status
                                    }}"
                                >


                                <input
                                    type="hidden"
                                    name="current_payment_id"
                                    value="{{
                                        $order
                                            ->payment_id
                                        ?? 0
                                    }}"
                                >



                                <select
                                    name="payment_status"
                                    class="
                                        form-select
                                        form-select-sm
                                        mb-2
                                    "
                                >

                                    @foreach(
                                        $allowedTransitions
                                        as $newStatus
                                    )

                                        <option
                                            value="{{ $newStatus }}"

                                            @selected(
                                                $newStatus
                                                ===
                                                $paymentStatus
                                            )
                                        >

                                            {{
                                                $statuses[
                                                    $newStatus
                                                ]
                                                ??
                                                $newStatus
                                            }}

                                        </option>

                                    @endforeach

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
                                            'Xác nhận cập nhật trạng thái thanh toán COD của đơn #{{ $order->id }}?'
                                        );
                                    "
                                >

                                    Lưu trạng thái

                                </button>

                            </form>

                        @elseif(
                            $order->gateway
                            ===
                            'cod'
                        )

                            <span class="text-muted">

                                Đã khóa

                            </span>

                        @elseif(
    $order->gateway === 'momo'
)

    <span
        class="
            text-muted
            small
        "
    >

        MoMo tự động

    </span>

@else

    <span
        class="
            text-muted
            small
        "
    >

        Không hỗ trợ cập nhật

    </span>

@endif

                    </td>

                </tr>


            @empty


                <tr>

                    <td
                        colspan="8"
                        class="
                            text-center
                            text-muted
                            py-5
                        "
                    >

                        Không có đơn hàng
                        phù hợp với bộ lọc.

                    </td>

                </tr>


            @endforelse


            </tbody>

        </table>

    </div>



    @if(
        $orders
            ->hasPages()
    )

        <div class="transaction-pagination">

            {{ $orders->links() }}

        </div>

    @endif

</div>


@endsection