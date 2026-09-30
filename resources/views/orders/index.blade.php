<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Đơn Hàng Của Tôi - GreenLife Flora</title>

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

    </style>

</head>


<body>

<div
    style="
        height: 5px;
        background: #198754;
    "
></div>


<div class="container py-4">


    {{-- ============================================ --}}
    {{-- TIÊU ĐỀ --}}
    {{-- ============================================ --}}

    <div class="d-flex justify-content-between align-items-center mb-3">

    <div>
        <h1 class="page-title mb-1">
            📦 Đơn Hàng Của Tôi
        </h1>

        <p class="text-muted mb-0">
            Danh sách các đơn hàng bạn đã đặt.
        </p>
    </div>


    <a
        href="{{ route('home') }}"
        class="btn btn-success"
    >
        🏠 Về Trang Chủ
    </a>

</div>



    {{-- ============================================ --}}
    {{-- THÔNG BÁO THÀNH CÔNG --}}
    {{-- ============================================ --}}

    @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif



    {{-- ============================================ --}}
    {{-- THÔNG BÁO LỖI --}}
    {{-- ============================================ --}}

    @if(session('error'))

        <div class="alert alert-danger">

            {{ session('error') }}

        </div>

    @endif



    {{-- ============================================ --}}
    {{-- DANH SÁCH ĐƠN --}}
    {{-- ============================================ --}}

    @forelse($orders as $order)


        <div class="card order-card shadow-sm mb-4">


            {{-- ====================================== --}}
            {{-- HEADER ĐƠN --}}
            {{-- ====================================== --}}

            <div class="card-header bg-white py-3">

                <div
                    class="d-flex justify-content-between align-items-center"
                >

                    <div>

                        <strong>
                            Mã đơn:
                        </strong>

                        #{{ $order->id }}

                    </div>


                    <div>

                        <strong>
                            Ngày đặt:
                        </strong>

                        {{ $order->created_at->format('d/m/Y H:i') }}

                    </div>



                    {{-- TRẠNG THÁI ĐƠN --}}

                    <div>

                        @if($order->status === 'pending')

                            <span
                                class="badge bg-warning text-dark"
                            >
                                ⏳ Chờ xác nhận
                            </span>


                        @elseif($order->status === 'shipping')

                            <span
                                class="badge bg-primary"
                            >
                                🚚 Đang giao
                            </span>


                        @elseif($order->status === 'completed')

                            <span
                                class="badge bg-success"
                            >
                                ✅ Đã hoàn thành
                            </span>


                        @elseif($order->status === 'cancelled')

                            <span
                                class="badge bg-danger"
                            >
                                ❌ Đã hủy
                            </span>

                        @endif

                    </div>

                </div>

            </div>



            <div class="card-body">


                {{-- ====================================== --}}
                {{-- NGƯỜI NHẬN + ĐỊA CHỈ --}}
                {{-- ====================================== --}}

                <div class="row mb-4">


                    <div class="col-md-6">

                        <h5 class="fw-bold">

                            👤 Người nhận

                        </h5>


                        <div>

                            <strong>
                                Họ tên:
                            </strong>

                            {{ $order->customer_name }}

                        </div>


                        <div>

                            <strong>
                                Số điện thoại:
                            </strong>

                            {{ $order->customer_phone }}

                        </div>


                        <div>

                            <strong>
                                Email:
                            </strong>

                            {{ $order->customer_email }}

                        </div>

                    </div>



                    <div class="col-md-6">

                        <h5 class="fw-bold">

                            📍 Địa chỉ giao hàng

                        </h5>


                        <div>

                            {{ $order->shipping_address }}

                        </div>


                        {{-- GHN --}}

                        @if($order->ghn_order_code)

                            <div class="mt-3">

                                <strong>
                                    🚚 Mã vận đơn GHN:
                                </strong>

                                <span
                                    class="text-primary fw-bold"
                                >
                                    {{ $order->ghn_order_code }}
                                </span>

                            </div>

                        @endif


                        @if($order->ghn_total_fee > 0)

                            <div>

                                <strong>
                                    💰 Phí vận chuyển:
                                </strong>

                                {{ number_format($order->ghn_total_fee) }} đ

                            </div>

                        @endif


                        <div>

                            <strong>
                                📦 Trạng thái GHN:
                            </strong>


                            @if($order->shipping_status === 'ready_to_pick')

                                <span
                                    class="badge bg-info text-dark"
                                >
                                    Chờ GHN lấy hàng
                                </span>


                            @elseif($order->shipping_status === 'picking')

                                <span
                                    class="badge bg-primary"
                                >
                                    GHN đang lấy hàng
                                </span>


                            @elseif($order->shipping_status === 'delivering')

                                <span
                                    class="badge bg-primary"
                                >
                                    Đang giao hàng
                                </span>


                            @elseif($order->shipping_status === 'delivered')

                                <span
                                    class="badge bg-success"
                                >
                                    Đã giao hàng
                                </span>


                            @elseif($order->shipping_status === 'cancelled')

                                <span
                                    class="badge bg-danger"
                                >
                                    Đã hủy vận đơn
                                </span>


                            @else

                                <span
                                    class="badge bg-secondary"
                                >
                                    {{ $order->shipping_status }}
                                </span>

                            @endif

                        </div>

                    </div>

                </div>



                {{-- ====================================== --}}
                {{-- SẢN PHẨM --}}
                {{-- ====================================== --}}

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

                                    <td>

                                        <div
                                            class="d-flex align-items-center gap-3"
                                        >

                                            @if(
                                                $item->product
                                                &&
                                                $item->product->image
                                            )

                                                <img
                                                    src="{{ asset($item->product->image) }}"
                                                    class="product-image"
                                                >

                                            @endif


                                            <span>

                                                {{ $item->product->name ?? 'Sản phẩm đã xóa' }}

                                            </span>

                                        </div>

                                    </td>


                                    <td>

                                        {{ number_format($item->price) }} đ

                                    </td>


                                    <td>

                                        {{ $item->quantity }}

                                    </td>


                                    <td class="fw-bold">

                                        {{ number_format(
                                            $item->price
                                            * $item->quantity
                                        ) }} đ

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>



                {{-- ====================================== --}}
                {{-- TỔNG TIỀN --}}
                {{-- ====================================== --}}

                <div
                    class="d-flex justify-content-end mt-3"
                >

                    <div class="fs-4">

                        Tổng tiền:

                        <strong class="text-danger">

                            {{ number_format($order->total_price) }} đ

                        </strong>

                    </div>

                </div>



                {{-- ====================================== --}}
{{-- NÚT HỦY ĐƠN --}}
{{-- ====================================== --}}

@if(
    in_array(
        $order->status,
        [
            'pending',
            'cod_ordered',
            'paid'
        ]
    )
    &&
    in_array(
        $order->shipping_status,
        [
            'not_shipped',
            'pending',
            'ready_to_pick'
        ]
    )
)

    <div class="d-flex justify-content-end mt-3">

        <form
            action="{{ route('my-orders.cancel', $order->id) }}"
            method="POST"
            onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này không?');"
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


        <div
            class="alert alert-info text-center py-4"
        >

            Bạn chưa có đơn hàng nào.

            <br><br>

            <a
                href="{{ route('home') }}"
                class="btn btn-success"
            >

                🌱 Tiếp tục mua cây

            </a>

        </div>


    @endforelse


</div>


</body>

</html>