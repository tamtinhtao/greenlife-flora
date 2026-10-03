<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $product->name }} - Chi Tiết Cây Cảnh
    </title>

    <link
        href="/bootstrap/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f8f9fa;
        }

        .product-detail-card {
            border: none;
            border-radius: 14px;
        }

        .product-detail-image {
            width: 100%;
            max-height: 420px;
            object-fit: cover;
        }

        .recommend-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            transition: 0.2s;
        }

        .recommend-card:hover {
            transform: translateY(-4px);
        }

        .recommend-image {
            width: 100%;
            height: 210px;
            object-fit: cover;
        }

    </style>

</head>


<body>


{{-- ===================================================== --}}
{{-- NAVBAR DÙNG CHUNG --}}
{{-- ===================================================== --}}

@include('partials.store-navbar')



{{-- ===================================================== --}}
{{-- CHI TIẾT SẢN PHẨM --}}
{{-- ===================================================== --}}

<div class="container my-4">

    <div
        class="
            product-detail-card
            bg-white
            p-4
            shadow-sm
        "
    >

        <div class="row g-4">


            {{-- ================================================= --}}
            {{-- ẢNH SẢN PHẨM --}}
            {{-- ================================================= --}}

            <div class="col-md-5">

                @if($product->image)

                    <img
                        src="{{ asset($product->image) }}"
                        class="
                            product-detail-image
                            img-fluid
                            rounded
                            border
                        "
                        alt="{{ $product->name }}"
                    >

                @else

                    <div
                        class="
                            product-detail-image
                            bg-light
                            border
                            rounded
                            d-flex
                            align-items-center
                            justify-content-center
                            text-muted
                        "
                    >
                        Chưa có ảnh
                    </div>

                @endif

            </div>



            {{-- ================================================= --}}
            {{-- THÔNG TIN SẢN PHẨM --}}
            {{-- ================================================= --}}

            <div class="col-md-7">


                {{-- DANH MỤC --}}
                <span
                    class="
                        badge
                        bg-success
                        mb-2
                    "
                >
                    {{
                        $product->category->name
                        ?? 'Cây cảnh'
                    }}
                </span>



                {{-- TÊN --}}
                <h2 class="fw-bold text-dark">

                    {{ $product->name }}

                </h2>



                {{-- GIÁ --}}
                <h3
                    class="
                        text-danger
                        fw-bold
                        my-3
                    "
                >

                    {{
                        number_format(
                            $product->price
                        )
                    }} VNĐ

                </h3>



                {{-- TỒN KHO --}}
                <p>

                    <strong>
                        Tình trạng:
                    </strong>

                    @if($product->stock > 0)

                        <span>
                            Còn hàng
                            ({{ $product->stock }} chậu)
                        </span>

                    @else

                        <span class="text-danger fw-bold">
                            Hết hàng
                        </span>

                    @endif

                </p>



                {{-- ================================================= --}}
                {{-- HƯỚNG DẪN CHĂM SÓC --}}
                {{-- ================================================= --}}

                <div
                    class="
                        card
                        bg-light
                        border-success
                        my-3
                        p-3
                    "
                >

                    <h5
                        class="
                            fw-bold
                            text-success
                        "
                    >
                        🪴 Hướng Dẫn Chăm Sóc Nhanh:
                    </h5>


                    <ul class="list-unstyled mb-0">

                        <li>

                            ☀️

                            <strong>
                                Ánh sáng:
                            </strong>

                            {{
                                $product->sunlight
                                ??
                                'Nắng nhẹ hoặc ánh sáng đèn văn phòng'
                            }}

                        </li>


                        <li>

                            💧

                            <strong>
                                Tưới nước:
                            </strong>

                            {{
                                $product->water
                                ??
                                'Tưới 1-2 lần/tuần khi đất khô'
                            }}

                        </li>

                    </ul>

                </div>



                {{-- ================================================= --}}
                {{-- MÔ TẢ --}}
                {{-- ================================================= --}}

                <p>

                    <strong>
                        Mô tả chi tiết:
                    </strong>

                    <br>

                    {{
                        $product->description
                        ??
                        'Đang cập nhật mô tả...'
                    }}

                </p>



                {{-- ================================================= --}}
                {{-- GHI CHÚ --}}
                {{-- ================================================= --}}

                <p>

                    <strong>
                        Ghi chú chăm sóc:
                    </strong>

                    <br>

                    {{
                        $product->care_guide
                        ??
                        'Chưa có ghi chú thêm.'
                    }}

                </p>



                {{-- ================================================= --}}
                {{-- YÊU THÍCH --}}
                {{-- ================================================= --}}

                @auth

                    @if(auth()->user()->role === 'user')

                        @php

                            $isWishlisted =
                                auth()
                                    ->user()
                                    ->wishlistProducts
                                    ->contains(
                                        'id',
                                        $product->id
                                    );

                        @endphp


                        <form
                            action="{{
                                $isWishlisted
                                    ? route(
                                        'wishlist.destroy',
                                        $product->id
                                    )
                                    : route(
                                        'wishlist.store',
                                        $product->id
                                    )
                            }}"
                            method="POST"
                            class="mb-3"
                        >

                            @csrf


                            @if($isWishlisted)

                                @method('DELETE')

                            @endif


                            <button
                                type="submit"
                                class="
                                    btn

                                    {{
                                        $isWishlisted
                                            ? 'btn-danger'
                                            : 'btn-outline-danger'
                                    }}

                                    w-100
                                    py-2
                                    fw-semibold
                                "
                            >

                                @if($isWishlisted)

                                    ♥ Đã thêm vào danh sách yêu thích

                                @else

                                    ♡ Thêm vào danh sách yêu thích

                                @endif

                            </button>

                        </form>

                    @endif


                @else

                    <a
                        href="{{ route('login') }}"
                        class="
                            btn
                            btn-outline-danger
                            w-100
                            py-2
                            mb-3
                            fw-semibold
                        "
                    >
                        ♡ Đăng nhập để thêm vào yêu thích
                    </a>

                @endauth



                {{-- ================================================= --}}
                {{-- THÊM GIỎ + MUA NGAY --}}
                {{-- ================================================= --}}

                <div class="d-flex gap-2">


                    {{-- THÊM VÀO GIỎ --}}
                    <form
                        action="{{
                            route(
                                'cart.add',
                                $product->id
                            )
                        }}"
                        method="POST"
                        class="w-50"
                    >

                        @csrf


                        <button
                            type="submit"
                            class="
                                btn
                                btn-success
                                btn-lg
                                w-100
                            "
                            @disabled(
                                $product->stock <= 0
                            )
                        >
                            🛒 Thêm Vào Giỏ Hàng
                        </button>

                    </form>



                    {{-- MUA NGAY --}}
                    @if($product->stock > 0)

                        <a
                            href="{{
                                route(
                                    'cart.buyNow',
                                    $product->id
                                )
                            }}"
                            class="
                                btn
                                btn-danger
                                btn-lg
                                w-50
                            "
                        >
                            ⚡ Mua Ngay
                        </a>

                    @else

                        <button
                            type="button"
                            class="
                                btn
                                btn-secondary
                                btn-lg
                                w-50
                            "
                            disabled
                        >
                            Hết hàng
                        </button>

                    @endif


                </div>

            </div>

        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- SẢN PHẨM GỢI Ý --}}
{{-- ========================================================= --}}

@if(
    isset($recommendedProducts)
    &&
    $recommendedProducts->count() > 0
)

    <div class="container mb-5">

        <div class="mb-4">

            <h3
                class="
                    fw-bold
                    text-success
                "
            >
                🌿 Có Thể Bạn Cũng Thích
            </h3>


            <p class="text-muted mb-0">

                Gợi ý dựa trên những sản phẩm
                bạn đã quan tâm gần đây.

            </p>

        </div>



        <div class="row g-4">

            @foreach(
                $recommendedProducts
                as $recommendedProduct
            )

                <div class="col-md-3">


                    <div
                        class="
                            card
                            recommend-card
                            h-100
                            shadow-sm
                        "
                    >


                        {{-- ẢNH --}}
                        @if(
                            $recommendedProduct->image
                        )

                            <img
                                src="{{
                                    asset(
                                        $recommendedProduct
                                            ->image
                                    )
                                }}"
                                class="
                                    card-img-top
                                    recommend-image
                                "
                                alt="{{
                                    $recommendedProduct
                                        ->name
                                }}"
                            >

                        @else

                            <div
                                class="
                                    recommend-image
                                    bg-light
                                    d-flex
                                    align-items-center
                                    justify-content-center
                                    text-muted
                                "
                            >
                                Chưa có ảnh
                            </div>

                        @endif



                        <div
                            class="
                                card-body
                                d-flex
                                flex-column
                            "
                        >


                            {{-- DANH MỤC --}}
                            @if(
                                $recommendedProduct
                                    ->category
                            )

                                <span
                                    class="
                                        badge
                                        bg-success-subtle
                                        text-success
                                        align-self-start
                                        mb-2
                                    "
                                >

                                    {{
                                        $recommendedProduct
                                            ->category
                                            ->name
                                    }}

                                </span>

                            @endif



                            {{-- TÊN --}}
                            <h5 class="fw-bold">

                                {{
                                    $recommendedProduct
                                        ->name
                                }}

                            </h5>



                            {{-- GIÁ --}}
                            <div
                                class="
                                    text-danger
                                    fw-bold
                                    fs-5
                                    mb-3
                                "
                            >

                                {{
                                    number_format(
                                        $recommendedProduct
                                            ->price
                                    )
                                }} đ

                            </div>



                            {{-- XEM SẢN PHẨM --}}
                            <div class="mt-auto">

                                <a
                                    href="{{
                                        route(
                                            'products.show_detail',
                                            $recommendedProduct
                                                ->slug
                                        )
                                    }}"
                                    class="
                                        btn
                                        btn-outline-success
                                        w-100
                                    "
                                >
                                    Xem sản phẩm
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

@endif



{{-- ===================================================== --}}
{{-- BOOTSTRAP JS --}}
{{-- ===================================================== --}}

<script src="/bootstrap/js/bootstrap.bundle.min.js"></script>


</body>

</html>