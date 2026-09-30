<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Đơn Hàng Của Tôi - GreenLife Flora</title>

   <link
    href="{{ asset('bootstrap/css/bootstrap.min.css') }}"
    rel="stylesheet"
>

    <style>
        body {
            background-color: #f8f9fa;
        }

        .order-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        }

        .product-image {
            width: 65px;
            height: 65px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>
</head>

<body>

{{-- NAVBAR --}}
<nav class="navbar navbar-dark bg-success mb-4">

    <div class="container">

        <a
            href="{{ route('home') }}"
            class="navbar-brand fw-bold"
        >
            🌿 GreenLife Flora
        </a>

        <div class="d-flex align-items-center gap-2">

            <span class="text-white">
                👤 {{ auth()->user()->name }}
            </span>

            <a
                href="{{ route('cart.index') }}"
                class="btn btn-light btn-sm"
            >
                🛒 Giỏ Hàng
            </a>

            <a
                href="{{ route('home') }}"
                class="btn btn-outline-light btn-sm"
            >
                🏠 Trang Chủ
            </a>

            <form
                action="{{ route('logout') }}"
                method="POST"
                class="d-inline"
            >
                @csrf

                <button
                    type="submit"
                    class="btn btn-outline-light btn-sm"
                >
                    🚪 Đăng Xuất
                </button>
            </form>

        </div>

    </div>

</nav>


<div class="container mb-5">

    <div class="mb-4">

        <h2 class="fw-bold text-success">
            📦 Đơn Hàng Của Tôi
        </h2>

        <p class="text-muted">
            Danh sách các đơn hàng bạn đã đặt.
        </p>

    </div>


    {{-- THÔNG BÁO --}}
    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- DANH SÁCH ĐƠN --}}
    @forelse($orders as $order)

        <div class="card order-card mb-4">

            <div class="card-header bg-white">

                <div class="row align-items-center">

                    <div class="col-md-4">

                        <strong>Mã đơn:</strong>
                        #{{ $order->id }}

                    </div>


                    <div class="col-md-4">

                        <strong>Ngày đặt:</strong>

                        {{ $order->created_at->format('d/m/Y H:i') }}

                    </div>


                    <div class="col-md-4 text-md-end">

                        @if($order->status === 'pending')

                            <span class="badge bg-warning text-dark">
                                ⏳ Chờ xác nhận
                            </span>

                        @elseif($order->status === 'processing')

                            <span class="badge bg-primary">
                                📦 Đang xử lý
                            </span>

                        @elseif($order->status === 'shipping')

                            <span class="badge bg-info text-dark">
                                🚚 Đang giao
                            </span>

                        @elseif($order->status === 'completed')

                            <span class="badge bg-success">
                                ✅ Hoàn thành
                            </span>

                        @elseif($order->status === 'cancelled')

                            <span class="badge bg-danger">
                                ❌ Đã hủy
                            </span>

                        @else

                            <span class="badge bg-secondary">
                                {{ $order->status }}
                            </span>

                        @endif

                    </div>

                </div>

            </div>


            <div class="card-body">

                {{-- THÔNG TIN NGƯỜI NHẬN --}}
                <div class="row mb-4">

                    <div class="col-md-6">

                        <h6 class="fw-bold">
                            👤 Người nhận
                        </h6>

                        <p class="mb-1">
                            <strong>Họ tên:</strong>
                            {{ $order->customer_name }}
                        </p>

                        <p class="mb-1">
                            <strong>Số điện thoại:</strong>
                            {{ $order->customer_phone }}
                        </p>

                        <p class="mb-1">
                            <strong>Email:</strong>
                            {{ $order->customer_email }}
                        </p>

                    </div>


                    <div class="col-md-6">

                        <h6 class="fw-bold">
                            📍 Địa chỉ giao hàng
                        </h6>

                        <p class="mb-1">
                            {{ $order->shipping_address }}
                        </p>

                        @if($order->note)

                            <p class="text-muted">
                                <strong>Ghi chú:</strong>
                                {{ $order->note }}
                            </p>

                        @endif

                    </div>

                </div>


                {{-- SẢN PHẨM --}}
                <h6 class="fw-bold">
                    🌱 Sản phẩm
                </h6>

                <div class="table-responsive">

                    <table class="table align-middle">

                        <thead class="table-light">

                            <tr>
                                <th>Sản phẩm</th>
                                <th>Đơn giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>

                        </thead>


                        <tbody>

                            @foreach($order->orderItems as $item)

                                <tr>

                                    <td>

                                        <div class="d-flex align-items-center gap-3">

                                            @if(
                                                $item->product &&
                                                $item->product->image
                                            )

                                                <img
                                                    src="{{ asset($item->product->image) }}"
                                                    class="product-image"
                                                    alt="{{ $item->product->name }}"
                                                >

                                            @endif


                                            <span>

                                                {{
                                                    $item->product->name
                                                    ?? 'Sản phẩm không còn tồn tại'
                                                }}

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

                                        {{
                                            number_format(
                                                $item->price *
                                                $item->quantity
                                            )
                                        }} đ

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>


                <div class="text-end">

                    <span class="fs-5">

                        Tổng tiền:

                        <strong class="text-danger">

                            {{ number_format($order->total_price) }} đ

                        </strong>

                    </span>

                </div>

            </div>

        </div>


    @empty

        <div class="card shadow-sm border-0">

            <div class="card-body text-center py-5">

                <div class="fs-1 mb-3">
                    📦
                </div>

                <h4>
                    Bạn chưa có đơn hàng nào
                </h4>

                <p class="text-muted">
                    Hãy chọn cây cảnh bạn yêu thích và đặt hàng.
                </p>

                <a
                    href="{{ route('home') }}"
                    class="btn btn-success"
                >
                    🌿 Mua Cây Ngay
                </a>

            </div>

        </div>

    @endforelse

</div>


<link
    href="{{ asset('bootstrap/css/bootstrap.min.css') }}"
    rel="stylesheet"
>

</body>
</html>