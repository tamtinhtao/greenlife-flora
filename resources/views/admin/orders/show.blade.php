@extends('layouts.admin')


@section(
    'title',
    'Chi tiết đơn #' . $order->id . ' - GreenLife Admin'
)


@section(
    'page-title',
    'Chi tiết đơn hàng'
)


@section(
    'page-subtitle',
    'Thông tin đầy đủ của đơn #' . $order->id
)



@push('styles')

<style>

    .order-detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;

        flex-wrap: wrap;

        gap: 15px;

        margin-bottom: 22px;
    }


    .order-detail-title {
        margin: 0 0 4px;

        font-size: 27px;

        font-weight: 500;
    }


    .order-detail-description {
        color: #8997a4;
    }


    .detail-card {
        height: 100%;

        background: #fff;

        border:
            1px solid #e1e5e8;

        box-shadow:
            0 1px 4px
            rgba(0,0,0,.05);
    }


    .detail-card-body {
        padding: 22px;
    }


    .section-title {
        margin-bottom: 18px;

        font-size: 17px;

        font-weight: 700;
    }


    .info-row {
        margin-bottom: 15px;
    }


    .info-row:last-child {
        margin-bottom: 0;
    }


    .info-label {
        margin-bottom: 3px;

        color: #83909b;

        font-size: 12px;
    }


    .info-value {
        font-weight: 600;

        color: #333;
    }


    .order-products-card {
        margin-bottom: 24px;

        background: #fff;

        border:
            1px solid #e1e5e8;

        box-shadow:
            0 1px 4px
            rgba(0,0,0,.05);
    }


    .order-products-header {
        padding: 15px 20px;

        border-bottom:
            1px solid #e5e8eb;

        font-weight: 700;
    }


    .order-products-table {
        margin-bottom: 0;
    }


    .order-products-table th {
        background: #eef2f5 !important;
    }


    .total-value {
        color: #dc3545;

        font-size: 20px;

        font-weight: 700;
    }

</style>

@endpush



@section('content')


@php

    $shippingLabels = [

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
            'Đang chuyển hàng hoàn',

        'return_sorting' =>
            'Đang phân loại hàng hoàn',

        'returned' =>
            'Đã hoàn hàng',

        'cancelled' =>
            'Đã hủy',
    ];


    /*
    |--------------------------------------------------------------------------
    | Controller đã load paymentTransactions theo latest()
    |--------------------------------------------------------------------------
    */

    $latestPayment =
        $order
            ->paymentTransactions
            ->first();


    $shippingFee =
    (int) (
        $order->ghn_total_fee
        ?? 0
    );

$total =
    (int) (
        $order->total_price
        ?? 0
    );

$discount =
    (int) (
        $order->discount_amount
        ?? 0
    );

$productTotal =
    (int) (
        $order->subtotal_price
        ?? 0
    );


/*
|--------------------------------------------------------------------------
| HỖ TRỢ ĐƠN CŨ
|--------------------------------------------------------------------------
| Những đơn tạo trước khi có voucher chưa có subtotal_price.
*/
if ($productTotal <= 0) {

    $productTotal =
        max(
            0,
            $total
            - $shippingFee
            + $discount
        );
}

@endphp



{{-- =========================================================
     HEADER
========================================================= --}}

<div class="order-detail-header">


    <div>

        <h1 class="order-detail-title">

            📦 Đơn hàng #{{ $order->id }}

        </h1>


        <div class="order-detail-description">

            Đặt lúc

            {{
                $order
                    ->created_at
                    ->format(
                        'H:i d/m/Y'
                    )
            }}

        </div>

    </div>


    <a
        href="{{ route('admin.orders.index') }}"
        class="btn btn-outline-secondary"
    >

        ← Quay lại quản lý đơn

    </a>

</div>



{{-- =========================================================
     CUSTOMER + SHIPPING
========================================================= --}}

<div class="row g-4 mb-4">


    {{-- CUSTOMER --}}
    <div class="col-lg-6">

        <div class="detail-card">

            <div class="detail-card-body">


                <div class="section-title">

                    👤 Thông tin người nhận

                </div>



                <div class="info-row">

                    <div class="info-label">
                        Họ tên
                    </div>

                    <div class="info-value">

                        {{ $order->customer_name }}

                    </div>

                </div>



                <div class="info-row">

                    <div class="info-label">
                        Số điện thoại
                    </div>

                    <div class="info-value">

                        {{ $order->customer_phone }}

                    </div>

                </div>



                <div class="info-row">

                    <div class="info-label">
                        Email
                    </div>

                    <div class="info-value">

                        {{
                            $order->customer_email
                            ??
                            'Không có'
                        }}

                    </div>

                </div>



                <div class="info-row">

                    <div class="info-label">
                        Địa chỉ nhận hàng
                    </div>

                    <div class="info-value">

                        {{ $order->shipping_address }}

                    </div>

                </div>



                <div class="info-row">

                    <div class="info-label">
                        Ghi chú
                    </div>

                    <div class="info-value">

                        {{
                            $order->note
                            ?: 'Không có'
                        }}

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- SHIPPING --}}
    <div class="col-lg-6">

        <div class="detail-card">

            <div class="detail-card-body">


                <div class="section-title">

                    🚚 Thông tin vận chuyển

                </div>



                <div class="info-row">

                    <div class="info-label">
                        Mã vận đơn GHN
                    </div>

                    <div class="info-value">

                        @if(
                            $order
                                ->ghn_order_code
                        )

                            <span
                                class="
                                    badge
                                    bg-info
                                    text-dark
                                "
                            >

                                {{
                                    $order
                                        ->ghn_order_code
                                }}

                            </span>

                        @else

                            <span class="text-muted">

                                Chưa có vận đơn

                            </span>

                        @endif

                    </div>

                </div>



                <div class="info-row">

                    <div class="info-label">

                        Trạng thái giao hàng

                    </div>

                    <div class="info-value">

                        {{
                            $shippingLabels[
                                $order
                                    ->shipping_status
                            ]
                            ??
                            $order
                                ->shipping_status
                            ??
                            'Chờ xác nhận'
                        }}

                    </div>

                </div>



                <div class="info-row">

                    <div class="info-label">

                        Phí vận chuyển GHN

                    </div>

                    <div class="info-value">

                        {{
                            number_format(
                                $shippingFee,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </div>

                </div>



                <div class="info-row">

                    <div class="info-label">

                        Trạng thái đơn

                    </div>

                    <div class="info-value">

                        {{ $order->status }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>



{{-- =========================================================
     PRODUCTS
========================================================= --}}

<div class="order-products-card">


    <div class="order-products-header">

        🌿 Sản phẩm trong đơn

    </div>


    <div class="table-responsive">

        <table
            class="
                table
                table-hover
                align-middle
                order-products-table
            "
        >

            <thead>

                <tr>

                    <th>
                        Sản phẩm
                    </th>

                    <th class="text-center">
                        Số lượng
                    </th>

                    <th class="text-end">
                        Đơn giá
                    </th>

                    <th class="text-end">
                        Thành tiền
                    </th>

                </tr>

            </thead>


            <tbody>


            @forelse(
                $order->orderItems
                as $item
            )

                <tr>

                    <td>

                        <strong>

                            {{
                                $item
                                    ->product
                                    ?->name
                                ??
                                'Sản phẩm đã xóa'
                            }}

                        </strong>

                    </td>


                    <td class="text-center">

                        {{ $item->quantity }}

                    </td>


                    <td class="text-end">

                        {{
                            number_format(
                                $item->price,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </td>


                    <td
                        class="
                            text-end
                            fw-bold
                        "
                    >

                        {{
                            number_format(
                                $item->price
                                *
                                $item->quantity,
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

                        Không có sản phẩm.

                    </td>

                </tr>


            @endforelse


            </tbody>

        </table>

    </div>

</div>



{{-- =========================================================
     PAYMENT + TOTAL
========================================================= --}}

<div class="row g-4">


    {{-- PAYMENT --}}
    <div class="col-lg-7">

        <div class="detail-card">

            <div class="detail-card-body">


                <div class="section-title">

                    💳 Thanh toán

                </div>



                <div class="info-row">

                    <div class="info-label">
                        Phương thức
                    </div>


                    <div class="info-value">

                        @if(
                            $order->payment_method
                            ===
                            'momo'
                        )

                            MoMo

                        @elseif(
                            $order->payment_method
                            ===
                            'cod'
                        )

                            COD - Thanh toán khi nhận hàng

                        @else

                            {{
                                strtoupper(
                                    $order
                                        ->payment_method
                                    ??
                                    'Không xác định'
                                )
                            }}

                        @endif

                    </div>

                </div>



                <div class="info-row">

                    <div class="info-label">

                        Trạng thái thanh toán

                    </div>


                    <div class="info-value">


                        @if($latestPayment)


                            @switch(
                                $latestPayment
                                    ->status
                            )


                                @case('paid')

                                    <span
                                        class="text-success"
                                    >

                                        ✓ Đã thanh toán

                                    </span>

                                    @break



                                @case('pending')

                                @case('initiated')

                                    <span
                                        class="text-warning"
                                    >

                                        Chờ thanh toán

                                    </span>

                                    @break



                                @case('failed')

                                    <span
                                        class="text-danger"
                                    >

                                        Thanh toán thất bại

                                    </span>

                                    @break



                                @case('refund_pending')

                                    <span
                                        class="text-warning"
                                    >

                                        Chờ hoàn tiền

                                    </span>

                                    @break



                                @case('refunded')

                                    <span
                                        class="text-primary"
                                    >

                                        Đã hoàn tiền

                                    </span>

                                    @break



                                @case('cancelled')

                                    <span
                                        class="text-danger"
                                    >

                                        Đã hủy

                                    </span>

                                    @break



                                @default

                                    {{
                                        $latestPayment
                                            ->status
                                    }}


                            @endswitch


                        @else


                            @if(
                                $order
                                    ->payment_method
                                ===
                                'cod'
                            )

                                Thanh toán khi nhận hàng

                            @else

                                Chưa có giao dịch

                            @endif


                        @endif

                    </div>

                </div>



                @if($latestPayment)

                    <div class="info-row">

                        <div class="info-label">

                            Mã giao dịch

                        </div>


                        <div class="info-value">

                            {{
                                $latestPayment
                                    ->transaction_id
                                ?: 'Chưa có'
                            }}

                        </div>

                    </div>


                    <div class="info-row">

                        <div class="info-label">

                            Thời gian thanh toán

                        </div>


                        <div class="info-value">

                            {{
                                $latestPayment
                                    ->paid_at
                                    ?->format(
                                        'H:i d/m/Y'
                                    )
                                ??
                                'Chưa thanh toán'
                            }}

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>



    {{-- TOTAL --}}
    <div class="col-lg-5">

        <div class="detail-card">

            <div class="detail-card-body">


                <div class="section-title">

                    💰 Giá trị đơn hàng

                </div>



                <div
                    class="
                        d-flex
                        justify-content-between
                        mb-3
                    "
                >

                    <span>
                        Tiền hàng
                    </span>

                    <strong>

                        {{
                            number_format(
                                $productTotal,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </strong>

                </div>

                @if(
    !empty($order->coupon_code)
    &&
    $discount > 0
)

    <div
        class="
            d-flex
            justify-content-between
            mb-3
            text-success
        "
    >

        <span>
            🎟 Voucher
            <strong>
                {{ $order->coupon_code }}
            </strong>
        </span>

        <strong>
            -{{
                number_format(
                    $discount,
                    0,
                    ',',
                    '.'
                )
            }} đ
        </strong>

    </div>

@endif


                <div
                    class="
                        d-flex
                        justify-content-between
                        mb-3
                    "
                >

                    <span>
                        Phí vận chuyển
                    </span>

                    <strong>

                        {{
                            number_format(
                                $shippingFee,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </strong>

                </div>


                <hr>


                <div
                    class="
                        d-flex
                        justify-content-between
                        align-items-center
                    "
                >

                    <strong>
                        Tổng cộng
                    </strong>


                    <span class="total-value">

                        {{
                            number_format(
                                $total,
                                0,
                                ',',
                                '.'
                            )
                        }} đ

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>


@endsection