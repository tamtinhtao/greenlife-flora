<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->name }} - Chi Tiết Cây Cảnh</title>
    <link
    href="{{ asset('bootstrap/css/bootstrap.min.css') }}"
    rel="stylesheet"
>
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-success mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">⬅️ Quay lại Trang chủ</a>
        </div>
    </nav>

    <div class="container bg-white p-4 rounded shadow-sm">
        <div class="row">
            <div class="col-md-5">
                @if($product->image)
                    <img src="{{ asset($product->image) }}" class="img-fluid rounded border w-100" style="max-height: 400px; object-fit: cover;">
                @else
                    <img src="https://via.placeholder.com/400x400?text=Cay+Canh" class="img-fluid rounded border">
                @endif
            </div>

            <div class="col-md-7">
                <span class="badge bg-success mb-2">{{ $product->category->name ?? 'Cây cảnh' }}</span>
                <h2 class="fw-bold text-dark">{{ $product->name }}</h2>
                <h3 class="text-danger fw-bold my-3">{{ number_format($product->price) }} VNĐ</h3>
                <p><strong>Tình trạng:</strong> {{ $product->stock > 0 ? 'Còn hàng ('.$product->stock.' chậu)' : 'Hết hàng' }}</p>

                <div class="card bg-light border-success my-3 p-3">
                    <h5 class="fw-bold text-success">🪴 Hướng Dẫn Chăm Sóc Nhanh:</h5>
                    <ul class="list-unstyled mb-0">
                        <li>☀️ <strong>Ánh sáng:</strong> {{ $product->sunlight ?? 'Nắng nhẹ hoặc ánh sáng đèn văn phòng' }}</li>
                        <li>💧 <strong>Tưới nước:</strong> {{ $product->water ?? 'Tưới 1-2 lần/tuần khi đất khô' }}</li>
                    </ul>
                </div>

                <p><strong>Mô tả chi tiết:</strong><br>{{ $product->description ?? 'Đang cập nhật mô tả...' }}</p>
                <p><strong>Ghi chú chăm sóc:</strong><br>{{ $product->care_guide ?? 'Chưa có ghi chú thêm.' }}</p>

                <div class="d-flex gap-2 mt-3">

    {{-- THÊM VÀO GIỎ --}}
    <form
        action="{{ route('cart.add', $product->id) }}"
        method="POST"
        class="w-50"
    >
        @csrf

        <button
            type="submit"
            class="btn btn-success btn-lg w-100"
        >
            🛒 Thêm Vào Giỏ Hàng
        </button>
    </form>


    {{-- MUA NGAY --}}
    <a
        href="{{ route('cart.buyNow', $product->id) }}"
        class="btn btn-danger btn-lg w-50"
    >
        ⚡ Mua Ngay
    </a>

</div>
            </div>
        </div>
    </div>
</body>
</html>