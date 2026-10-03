<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Sản phẩm yêu thích - GreenLife Flora
    </title>

    <link
        href="/bootstrap/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f7f9f7;
        }

        .wishlist-header {
            background: #e8f5e9;
            border-radius: 14px;
            padding: 25px;
        }

        .product-card {
            border: none;
            border-radius: 14px;
            overflow: hidden;
            transition: 0.2s;
        }

        .product-card:hover {
            transform: translateY(-4px);
        }

        .product-image {
            width: 100%;
            height: 210px;
            object-fit: cover;
        }

        .empty-box {
            background: white;
            border-radius: 14px;
            padding: 60px 20px;
        }

    </style>

</head>


<body>
@include('partials.store-navbar')
<div class="container py-4">


    {{-- HEADER --}}
    <div
        class="
            wishlist-header
            d-flex
            justify-content-between
            align-items-center
            mb-4
        "
    >

        <div>

            <h2 class="text-success fw-bold mb-1">
                ❤️ Sản Phẩm Yêu Thích
            </h2>

            <div class="text-muted">

                Bạn đang có

                <strong>
                    {{ $wishlists->count() }}
                </strong>

                sản phẩm yêu thích.

            </div>

        </div>


        <a
            href="{{ route('home') }}"
            class="btn btn-outline-success"
        >
            ← Tiếp tục mua sắm
        </a>

    </div>



    {{-- THÔNG BÁO --}}
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



    {{-- DANH SÁCH --}}
    @if($wishlists->count() > 0)

        <div class="row">

            @foreach($wishlists as $wishlist)

                @php
                    $product = $wishlist->product;
                @endphp


                @if($product)

                    <div class="col-md-3 mb-4">

                        <div
                            class="
                                card
                                product-card
                                h-100
                                shadow-sm
                            "
                        >


                            {{-- ẢNH --}}
                            @if($product->image)

                                <img
                                    src="{{ asset($product->image) }}"
                                    class="product-image"
                                    alt="{{ $product->name }}"
                                >

                            @else

                                <div
                                    class="
                                        product-image
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
                                        $product->category->name
                                        ?? 'Cây cảnh'
                                    }}

                                </span>


                                {{-- TÊN --}}
                                <h5 class="fw-bold">

                                    {{ $product->name }}

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
                                            $product->price
                                        )
                                    }} đ

                                </div>


                                <div class="mt-auto">


                                    {{-- XEM CHI TIẾT --}}
                                    <a
                                        href="{{
                                            route(
                                                'products.show_detail',
                                                $product->slug
                                            )
                                        }}"
                                        class="
                                            btn
                                            btn-outline-secondary
                                            btn-sm
                                            w-100
                                            mb-2
                                        "
                                    >
                                        👁 Xem chi tiết
                                    </a>



                                    {{-- THÊM GIỎ --}}
                                    <form
                                        action="{{
                                            route(
                                                'cart.add',
                                                $product->id
                                            )
                                        }}"
                                        method="POST"
                                        class="mb-2"
                                    >

                                        @csrf

                                        <button
                                            type="submit"
                                            class="
                                                btn
                                                btn-success
                                                btn-sm
                                                w-100
                                            "
                                        >
                                            🛒 Thêm vào giỏ
                                        </button>

                                    </form>



                                    {{-- XÓA YÊU THÍCH --}}
                                    <form
                                        action="{{
                                            route(
                                                'wishlist.destroy',
                                                $product->id
                                            )
                                        }}"
                                        method="POST"
                                    >

                                        @csrf
                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            class="
                                                btn
                                                btn-outline-danger
                                                btn-sm
                                                w-100
                                            "
                                        >
                                            💔 Bỏ yêu thích
                                        </button>

                                    </form>


                                </div>

                            </div>

                        </div>

                    </div>

                @endif

            @endforeach

        </div>


    @else


        {{-- RỖNG --}}
        <div
            class="
                empty-box
                text-center
                shadow-sm
            "
        >

            <div
                style="
                    font-size: 65px;
                "
            >
                🤍
            </div>


            <h4 class="fw-bold mt-3">

                Danh sách yêu thích đang trống

            </h4>


            <p class="text-muted">

                Hãy chọn những cây bạn thích để lưu lại
                và mua sau.

            </p>


            <a
                href="{{ route('home') }}"
                class="btn btn-success"
            >
                🌿 Khám phá cây cảnh
            </a>

        </div>


    @endif


</div>


<script
    src="/bootstrap/js/bootstrap.bundle.min.js"
></script>

</body>

</html>