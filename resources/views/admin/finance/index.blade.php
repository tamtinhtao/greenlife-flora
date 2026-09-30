@extends('layouts.admin')


@section(
    'title',
    'Tổng quan tài chính - GreenLife Admin'
)


@section(
    'page-title',
    'Tài chính & Báo cáo'
)


@section(
    'page-subtitle',
    'Theo dõi tình hình thanh toán và doanh thu'
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

        background:
            #3898c4;

        color: white;
    }


    .finance-filter {

        margin-bottom: 22px;

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

        gap: 13px;
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

        margin-top: 14px;

        display: flex;

        gap: 8px;
    }



    /* SUMMARY */

    .finance-summary {

        display: grid;

        grid-template-columns:
            repeat(
                4,
                minmax(
                    0,
                    1fr
                )
            );

        gap: 15px;

        margin-bottom: 22px;
    }


    .finance-stat {

        min-height: 112px;

        padding: 18px;

        background: white;

        border:
            1px solid #e1e5e8;

        border-top:
            3px solid
            #3898c4;

        box-shadow:
            0 1px 3px
            rgba(0,0,0,.04);
    }


    .finance-stat-label {

        color: #798691;

        font-size: 12px;
    }


    .finance-stat-money {

        margin-top: 8px;

        font-size: 21px;

        font-weight: 700;
    }


    .finance-stat-count {

        margin-top: 4px;

        color: #8d98a1;

        font-size: 11px;
    }


    .finance-stat.paid {
        border-top-color: #198754;
    }


    .finance-stat.pending {
        border-top-color: #f0ad4e;
    }


    .finance-stat.failed {
        border-top-color: #dc3545;
    }


    .finance-stat.refund {
        border-top-color: #6f42c1;
    }



    /* TABLE */

    .finance-card {

        margin-bottom: 22px;

        background: white;

        border:
            1px solid #e1e5e8;

        box-shadow:
            0 1px 3px
            rgba(0,0,0,.04);
    }


    .finance-card-header {

        padding: 15px 18px;

        border-bottom:
            1px solid #e5e8eb;

        font-weight: 700;
    }


    .finance-table {

        margin-bottom: 0;
    }


    .finance-table th {

        background:
            #eef2f5 !important;
    }


    .money {

        font-weight: 700;

        color: #198754;
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


        .finance-summary {

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

        Tổng quan tài chính

    </h1>


    <div class="admin-page-description">

        Tổng hợp giá trị thanh toán
        theo trạng thái và phương thức.

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
        class="
            finance-tab
            active
        "
    >

        Tổng quan tài chính

    </a>


    <a
        href="{{
            route(
                'admin.finance.transactions'
            )
        }}"
        class="finance-tab"
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
                'admin.finance.index'
            )
        }}"
    >


        <div class="finance-filter-grid">


            <div class="wide">

                <label class="finance-label">

                    Tìm đơn hàng

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

                    Số tiền từ

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
                    placeholder="0"
                >

            </div>



            <div>

                <label class="finance-label">

                    Số tiền đến

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
                    placeholder="Không giới hạn"
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

                    Trạng thái thanh toán

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
                        'admin.finance.index'
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
     TOTAL
========================================================= --}}

<div class="finance-summary">


    <div class="finance-stat">


        <div class="finance-stat-label">

            Tổng giá trị đơn hàng

        </div>


        <div class="finance-stat-money">

            {{
                number_format(
                    (float) (
                        $summary
                            ->total_amount
                        ?? 0
                    ),
                    0,
                    ',',
                    '.'
                )
            }} đ

        </div>


        <div class="finance-stat-count">

            {{
                number_format(
                    $summary
                        ->order_count
                    ?? 0
                )
            }}

            đơn hàng

        </div>

    </div>



    @php

        $paidRow =
            $statusTotals->get(
                'paid'
            );


        $pendingRow =
            $statusTotals->get(
                'pending'
            );


        $refundPendingRow =
            $statusTotals->get(
                'refund_pending'
            );

    @endphp



    <div
        class="
            finance-stat
            paid
        "
    >

        <div class="finance-stat-label">

            Đã thanh toán

        </div>


        <div class="finance-stat-money">

            {{
                number_format(
                    (float) (
                        $paidRow
                            ->total_amount
                        ?? 0
                    ),
                    0,
                    ',',
                    '.'
                )
            }} đ

        </div>


        <div class="finance-stat-count">

            {{
                number_format(
                    $paidRow
                        ->order_count
                    ?? 0
                )
            }}

            đơn

        </div>

    </div>



    <div
        class="
            finance-stat
            pending
        "
    >

        <div class="finance-stat-label">

            Chờ thanh toán

        </div>


        <div class="finance-stat-money">

            {{
                number_format(
                    (float) (
                        $pendingRow
                            ->total_amount
                        ?? 0
                    ),
                    0,
                    ',',
                    '.'
                )
            }} đ

        </div>


        <div class="finance-stat-count">

            {{
                number_format(
                    $pendingRow
                        ->order_count
                    ?? 0
                )
            }}

            đơn

        </div>

    </div>



    <div
        class="
            finance-stat
            refund
        "
    >

        <div class="finance-stat-label">

            Chờ hoàn tiền

        </div>


        <div class="finance-stat-money">

            {{
                number_format(
                    (float) (
                        $refundPendingRow
                            ->total_amount
                        ?? 0
                    ),
                    0,
                    ',',
                    '.'
                )
            }} đ

        </div>


        <div class="finance-stat-count">

            {{
                number_format(
                    $refundPendingRow
                        ->order_count
                    ?? 0
                )
            }}

            đơn

        </div>

    </div>

</div>



{{-- =========================================================
     STATUS TABLE
========================================================= --}}

<div class="finance-card">


    <div class="finance-card-header">

        Thống kê theo trạng thái thanh toán

    </div>


    <div class="table-responsive">

        <table
            class="
                table
                table-hover
                finance-table
            "
        >

            <thead>

                <tr>

                    <th>
                        Trạng thái
                    </th>

                    <th class="text-end">
                        Số đơn
                    </th>

                    <th class="text-end">
                        Tổng giá trị
                    </th>

                </tr>

            </thead>


            <tbody>


            @foreach(
                $statuses
                as $statusKey => $statusLabel
            )

                @php

                    $row =
                        $statusTotals->get(
                            $statusKey
                        );

                @endphp


                <tr>

                    <td>

                        {{ $statusLabel }}

                    </td>


                    <td class="text-end">

                        {{
                            number_format(
                                $row
                                    ->order_count
                                ?? 0
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
                                (float) (
                                    $row
                                        ->total_amount
                                    ?? 0
                                ),
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </td>

                </tr>

            @endforeach


            </tbody>

        </table>

    </div>

</div>



{{-- =========================================================
     METHOD TABLE
========================================================= --}}

<div class="finance-card mb-0">


    <div class="finance-card-header">

        Thống kê theo phương thức thanh toán

    </div>


    <div class="table-responsive">

        <table
            class="
                table
                table-hover
                finance-table
            "
        >

            <thead>

                <tr>

                    <th>
                        Phương thức
                    </th>

                    <th class="text-end">
                        Số đơn
                    </th>

                    <th class="text-end">
                        Tổng giá trị
                    </th>

                    <th class="text-end">
                        Đã thanh toán
                    </th>

                </tr>

            </thead>


            <tbody>


            @foreach(
                $methods
                as $methodKey => $methodLabel
            )

                @php

                    $method =
                        $methodTotals->get(
                            $methodKey
                        );

                @endphp


                <tr>

                    <td>

                        <strong>
                            {{ $methodLabel }}
                        </strong>

                    </td>


                    <td class="text-end">

                        {{
                            number_format(
                                $method
                                    ->order_count
                                ?? 0
                            )
                        }}

                    </td>


                    <td class="text-end">

                        {{
                            number_format(
                                (float) (
                                    $method
                                        ->total_amount
                                    ?? 0
                                ),
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </td>


                    <td
                        class="
                            text-end
                            money
                        "
                    >

                        {{
                            number_format(
                                (float) (
                                    $method
                                        ->paid_amount
                                    ?? 0
                                ),
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </td>

                </tr>

            @endforeach


            </tbody>

        </table>

    </div>

</div>


@endsection