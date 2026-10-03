<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Đơn Hàng Của Tôi - GreenLife Flora
    </title>

    <link
        href="{{ asset('bootstrap/css/bootstrap.min.css') }}"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f5f6f7;
        }

        .page-title {
            color: #168754;
            font-weight: bold;
        }

        .order-card {
            border: none;
            border-radius: 14px;
            overflow: hidden;
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }

        .order-summary {
            max-width: 520px;
            margin-left: auto;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 25px;
            margin-bottom: 9px;
        }

        .summary-label {
            color: #6c757d;
        }

        .summary-value {
            min-width: 145px;
            text-align: right;
            font-weight: 600;
        }

        .voucher-row {
            color: #198754;
        }

        .total-row {
            padding-top: 12px;
            margin-top: 8px;
            border-top: 1px solid #dee2e6;
        }

        .total-value {
            color: #dc3545;
            font-size: 25px;
            font-weight: 700;
        }

    </style>

</head>


<body>


{{-- ========================================================= --}}
{{-- NAVBAR CHUNG --}}
{{-- ========================================================= --}}

@include('partials.store-navbar')


<div
    style="
        height: 5px;
        background: #198754;
    "
></div>



<div class="container py-4">


    {{-- ===================================================== --}}
    {{-- TIÊU ĐỀ --}}
    {{-- ===================================================== --}}

    <div
        class="
            d-flex
            justify-content-between
            align-items-center
            mb-3
        "
    >

        <div>

            <h1 class="page-title mb-1">
                📦 Đơn Hàng Của Tôi
            </h1>

            <p class="text-muted mb-0">
                Danh sách các đơn hàng bạn đã đặt.
            </p>

        </div>

    </div>



    {{-- ===================================================== --}}
    {{-- THÔNG BÁO THÀNH CÔNG --}}
    {{-- ===================================================== --}}

    @if(session('success'))

        <div
            class="
                alert
                alert-success
                alert-dismissible
                fade
                show
            "
        >

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif



    {{-- ===================================================== --}}
    {{-- THÔNG BÁO WARNING --}}
    {{-- ===================================================== --}}

    @if(session('warning'))

        <div
            class="
                alert
                alert-warning
                alert-dismissible
                fade
                show
            "
        >

            {{ session('warning') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif



    {{-- ===================================================== --}}
    {{-- THÔNG BÁO LỖI --}}
    {{-- ===================================================== --}}

    @if(session('error'))

        <div
            class="
                alert
                alert-danger
                alert-dismissible
                fade
                show
            "
        >

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif



    {{-- ===================================================== --}}
    {{-- DANH SÁCH ĐƠN HÀNG --}}
    {{-- ===================================================== --}}

    @forelse($orders as $order)


        @php

            /*
            |--------------------------------------------------------------------------
            | PHÍ VẬN CHUYỂN
            |--------------------------------------------------------------------------
            */

            $shippingFee =
                (int) (
                    $order->ghn_total_fee
                    ?? 0
                );


            /*
            |--------------------------------------------------------------------------
            | GIẢM GIÁ VOUCHER
            |--------------------------------------------------------------------------
            */

            $discount =
                (int) (
                    $order->discount_amount
                    ?? 0
                );


            /*
            |--------------------------------------------------------------------------
            | TIỀN HÀNG
            |--------------------------------------------------------------------------
            |
            | Đơn mới:
            | - lấy subtotal_price đã snapshot lúc checkout.
            |
            | Đơn cũ:
            | - nếu chưa có subtotal_price thì tính trực tiếp từ OrderItem.
            |--------------------------------------------------------------------------
            */

            $subtotal =
                (int) (
                    $order->subtotal_price
                    ?? 0
                );


            if ($subtotal <= 0) {

                $subtotal =
                    (int) $order
                        ->orderItems
                        ->sum(
                            function ($item) {

                                return
                                    (int) $item->price
                                    *
                                    (int) $item->quantity;
                            }
                        );
            }


            /*
            |--------------------------------------------------------------------------
            | TỔNG THANH TOÁN
            |--------------------------------------------------------------------------
            */

            $finalTotal =
                (int) (
                    $order->total_price
                    ?? 0
                );


            /*
            |--------------------------------------------------------------------------
            | TRẠNG THÁI ĐƠN
            |--------------------------------------------------------------------------
            */

            $orderStatus =
                $order->status
                ?? 'pending';


            /*
            |--------------------------------------------------------------------------
            | TRẠNG THÁI GHN
            |--------------------------------------------------------------------------
            */

            $shippingStatus =
                $order->shipping_status
                ?? 'pending';

        @endphp



        <div
            class="
                card
                order-card
                shadow-sm
                mb-4
            "
        >


            {{-- ================================================= --}}
            {{-- HEADER ĐƠN --}}
            {{-- ================================================= --}}

            <div class="card-header bg-white py-3">

                <div
                    class="
                        row
                        align-items-center
                        g-3
                    "
                >


                    {{-- MÃ ĐƠN --}}
                    <div class="col-md-4">

                        <strong>
                            Mã đơn:
                        </strong>

                        #{{ $order->id }}

                    </div>



                    {{-- NGÀY ĐẶT --}}
                    <div class="col-md-4">

                        <strong>
                            Ngày đặt:
                        </strong>

                        {{
                            $order
                                ->created_at
                                ->format(
                                    'd/m/Y H:i'
                                )
                        }}

                    </div>



                    {{-- TRẠNG THÁI --}}
                    <div
                        class="
                            col-md-4
                            text-md-end
                        "
                    >

                        @switch($orderStatus)


                            @case('pending')

                                <span
                                    class="
                                        badge
                                        bg-warning
                                        text-dark
                                    "
                                >
                                    ⏳ Chờ xác nhận
                                </span>

                                @break



                            @case('cod_ordered')

                                <span class="badge bg-info text-dark">
                                    📦 Đã đặt COD
                                </span>

                                @break



                            @case('processing')

                                <span class="badge bg-primary">
                                    📦 Đang xử lý
                                </span>

                                @break



                            @case('shipping')

                                <span class="badge bg-primary">
                                    🚚 Đang giao
                                </span>

                                @break



                            @case('paid')

                            @case('paid_momo')

                            @case('cod_paid')

                                <span class="badge bg-success">
                                    💳 Đã thanh toán
                                </span>

                                @break



                            @case('completed')

                                <span class="badge bg-success">
                                    ✅ Đã hoàn thành
                                </span>

                                @break



                            @case('cancelled')

                                <span class="badge bg-danger">
                                    ❌ Đã hủy
                                </span>

                                @break



                            @default

                                <span class="badge bg-secondary">
                                    {{ $orderStatus }}
                                </span>

                        @endswitch

                    </div>

                </div>

            </div>



            {{-- ================================================= --}}
            {{-- BODY --}}
            {{-- ================================================= --}}

            <div class="card-body">


                {{-- ================================================= --}}
                {{-- NGƯỜI NHẬN + ĐỊA CHỈ --}}
                {{-- ================================================= --}}

                <div class="row mb-4">


                    {{-- NGƯỜI NHẬN --}}
                    <div class="col-md-6">

                        <h5 class="fw-bold">
                            👤 Người nhận
                        </h5>


                        <div class="mb-1">

                            <strong>
                                Họ tên:
                            </strong>

                            {{ $order->customer_name }}

                        </div>


                        <div class="mb-1">

                            <strong>
                                Số điện thoại:
                            </strong>

                            {{ $order->customer_phone }}

                        </div>


                        <div class="mb-1">

                            <strong>
                                Email:
                            </strong>

                            {{
                                $order->customer_email
                                ?? 'Không có'
                            }}

                        </div>

                    </div>



                    {{-- ĐỊA CHỈ + GHN --}}
                    <div class="col-md-6">

                        <h5 class="fw-bold">
                            📍 Địa chỉ giao hàng
                        </h5>


                        <div class="mb-3">
                            {{ $order->shipping_address }}
                        </div>



                        {{-- MÃ VẬN ĐƠN --}}
                        @if($order->ghn_order_code)

                            <div class="mb-1">

                                <strong>
                                    🚚 Mã vận đơn GHN:
                                </strong>

                                <span
                                    class="
                                        text-primary
                                        fw-bold
                                    "
                                >
                                    {{ $order->ghn_order_code }}
                                </span>

                            </div>

                        @endif



                        {{-- PHÍ SHIP --}}
                        <div class="mb-1">

                            <strong>
                                💰 Phí vận chuyển:
                            </strong>

                            {{ number_format($shippingFee) }} đ

                        </div>



                        {{-- TRẠNG THÁI GHN --}}
                        <div>

                            <strong>
                                📦 Trạng thái GHN:
                            </strong>


                            @if(
                                in_array(
                                    $shippingStatus,
                                    [
                                        'pending',
                                        'not_shipped',
                                        'processing',
                                    ]
                                )
                            )

                                <span
                                    class="
                                        badge
                                        bg-warning
                                        text-dark
                                    "
                                >
                                    Chờ xác nhận
                                </span>


                            @elseif(
                                $shippingStatus
                                === 'ready_to_pick'
                            )

                                <span
                                    class="
                                        badge
                                        bg-info
                                        text-dark
                                    "
                                >
                                    Chờ GHN lấy hàng
                                </span>


                            @elseif(
                                $shippingStatus
                                === 'picking'
                            )

                                <span class="badge bg-primary">
                                    GHN đang lấy hàng
                                </span>


                            @elseif(
                                in_array(
                                    $shippingStatus,
                                    [
                                        'picked',
                                        'storing',
                                        'transporting',
                                        'sorting',
                                        'delivering',
                                    ]
                                )
                            )

                                <span class="badge bg-primary">
                                    Đang giao hàng
                                </span>


                            @elseif(
                                $shippingStatus
                                === 'delivered'
                            )

                                <span class="badge bg-success">
                                    Đã giao hàng
                                </span>


                            @elseif(
                                in_array(
                                    $shippingStatus,
                                    [
                                        'return',
                                        'returning',
                                        'return_transporting',
                                        'return_sorting',
                                        'returned',
                                    ]
                                )
                            )

                                <span
                                    class="
                                        badge
                                        bg-warning
                                        text-dark
                                    "
                                >
                                    Đang hoàn hàng
                                </span>


                            @elseif(
                                $shippingStatus
                                === 'cancelled'
                            )

                                <span class="badge bg-danger">
                                    Đã hủy vận đơn
                                </span>


                            @else

                                <span class="badge bg-secondary">
                                    {{ $shippingStatus }}
                                </span>

                            @endif

                        </div>


                        {{-- GHI CHÚ --}}
                        @if($order->note)

                            <div class="mt-3">

                                <strong>
                                    📝 Ghi chú:
                                </strong>

                                {{ $order->note }}

                            </div>

                        @endif

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- SẢN PHẨM --}}
                {{-- ================================================= --}}

                <h5 class="fw-bold">
                    🌱 Sản phẩm
                </h5>


                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Sản phẩm
                                </th>

                                <th>
                                    Đơn giá
                                </th>

                                <th>
                                    Số lượng
                                </th>

                                <th>
                                    Thành tiền
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @foreach(
                                $order->orderItems
                                as $item
                            )

                                <tr>


                                    {{-- SẢN PHẨM --}}
                                    <td>

                                        <div
                                            class="
                                                d-flex
                                                align-items-center
                                                gap-3
                                            "
                                        >

                                            @if(
                                                $item->product
                                                &&
                                                $item->product->image
                                            )

                                                <img
                                                    src="{{
                                                        asset(
                                                            $item
                                                                ->product
                                                                ->image
                                                        )
                                                    }}"
                                                    class="product-image"
                                                    alt="{{
                                                        $item
                                                            ->product
                                                            ->name
                                                    }}"
                                                >

                                            @else

                                                <div
                                                    class="
                                                        product-image
                                                        bg-light
                                                        border
                                                        d-flex
                                                        align-items-center
                                                        justify-content-center
                                                        text-muted
                                                    "
                                                >
                                                    🌿
                                                </div>

                                            @endif


                                            <span>

                                                {{
                                                    $item
                                                        ->product
                                                        ?->name
                                                    ??
                                                    'Sản phẩm đã xóa'
                                                }}

                                            </span>

                                        </div>

                                    </td>



                                    {{-- GIÁ --}}
                                    <td>

                                        {{
                                            number_format(
                                                $item->price
                                            )
                                        }} đ

                                    </td>



                                    {{-- SỐ LƯỢNG --}}
                                    <td>

                                        {{ $item->quantity }}

                                    </td>



                                    {{-- THÀNH TIỀN --}}
                                    <td class="fw-bold">

                                        {{
                                            number_format(
                                                $item->price
                                                *
                                                $item->quantity
                                            )
                                        }} đ

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>



                {{-- ================================================= --}}
                {{-- CHI TIẾT THANH TOÁN --}}
                {{-- ================================================= --}}

                <div class="order-summary mt-4">


                    {{-- TIỀN HÀNG --}}
                    <div class="summary-row">

                        <span class="summary-label">
                            Tiền hàng:
                        </span>

                        <span class="summary-value">

                            {{
                                number_format(
                                    $subtotal
                                )
                            }} đ

                        </span>

                    </div>



                    {{-- ================================================= --}}
                    {{-- VOUCHER --}}
                    {{-- ================================================= --}}

                    @if(
                        !empty($order->coupon_code)
                        &&
                        $discount > 0
                    )

                        <div
                            class="
                                summary-row
                                voucher-row
                            "
                        >

                            <span>

                                🎟 Voucher

                                <strong>
                                    {{ $order->coupon_code }}
                                </strong>:

                            </span>


                            <span class="summary-value">

                                -{{
                                    number_format(
                                        $discount
                                    )
                                }} đ

                            </span>

                        </div>

                    @endif



                    {{-- PHÍ VẬN CHUYỂN --}}
                    <div class="summary-row">

                        <span class="summary-label">
                            Phí vận chuyển:
                        </span>

                        <span class="summary-value">

                            {{
                                number_format(
                                    $shippingFee
                                )
                            }} đ

                        </span>

                    </div>



                    {{-- TỔNG --}}
                    <div
                        class="
                            summary-row
                            total-row
                            mb-0
                        "
                    >

                        <span class="fs-5 fw-semibold">
                            Tổng thanh toán:
                        </span>

                        <span
                            class="
                                summary-value
                                total-value
                            "
                        >

                            {{
                                number_format(
                                    $finalTotal
                                )
                            }} đ

                        </span>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- NÚT HỦY ĐƠN --}}
                {{-- ================================================= --}}

                @if(
                    in_array(
                        $orderStatus,
                        [
                            'pending',
                            'cod_ordered',
                            'paid',
                        ]
                    )
                    &&
                    in_array(
                        $shippingStatus,
                        [
                            'not_shipped',
                            'pending',
                            'ready_to_pick',
                        ]
                    )
                )

                    <div
                        class="
                            d-flex
                            justify-content-end
                            mt-3
                        "
                    >

                        <form
                            action="{{
                                route(
                                    'my-orders.cancel',
                                    $order->id
                                )
                            }}"
                            method="POST"
                            onsubmit="
                                return confirm(
                                    'Bạn có chắc muốn hủy đơn hàng này không?'
                                );
                            "
                        >

                            @csrf


                            <button
                                type="submit"
                                class="btn btn-danger"
                            >
                                ❌ Hủy Đơn Hàng
                            </button>

                        </form>

                    </div>

                @endif


            </div>

        </div>


    @empty


        {{-- ================================================= --}}
        {{-- CHƯA CÓ ĐƠN --}}
        {{-- ================================================= --}}

        <div
            class="
                alert
                alert-info
                text-center
                py-5
            "
        >

            <div class="fs-1 mb-3">
                📦
            </div>


            <h4>
                Bạn chưa có đơn hàng nào.
            </h4>


            <p class="text-muted">
                Hãy chọn cây cảnh bạn yêu thích và đặt hàng.
            </p>


            <a
                href="{{ route('home') }}"
                class="btn btn-success"
            >
                🌱 Tiếp tục mua cây
            </a>

        </div>


    @endforelse


</div>



<script
    src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"
></script>


</body>

</html>