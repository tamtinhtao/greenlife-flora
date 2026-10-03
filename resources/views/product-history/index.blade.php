<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        Sản Phẩm Đã Xem - GreenLife Flora
    </title>

    <link
        href="{{ asset('bootstrap/css/bootstrap.min.css') }}"
        rel="stylesheet"
    >

    <style>

        body {
            background: #f5f6f7;
            color: #212529;
        }


        /*
        |--------------------------------------------------------------------------
        | PAGE
        |--------------------------------------------------------------------------
        */

        .history-container {
            padding-top: 26px;
            padding-bottom: 50px;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .history-header {
            background: #e9f7ed;
            border-radius: 16px;
            padding: 28px 30px;
            margin-bottom: 26px;
        }

        .history-header h1 {
            margin: 0 0 7px;
            color: #198754;
            font-size: 32px;
            font-weight: 700;
        }

        .history-header p {
            margin: 0;
            color: #667085;
        }


        /*
        |--------------------------------------------------------------------------
        | PRODUCT CARD
        |--------------------------------------------------------------------------
        */

        .history-card {
            background: #ffffff;
            border: none;
            border-radius: 14px;
            overflow: hidden;
            height: 100%;
            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, 0.08);

            transition:
                transform 0.18s ease,
                box-shadow 0.18s ease;
        }

        .history-card:hover {
            transform: translateY(-3px);

            box-shadow:
                0 6px 18px
                rgba(0, 0, 0, 0.11);
        }


        /*
        |--------------------------------------------------------------------------
        | IMAGE
        |--------------------------------------------------------------------------
        */

        .history-product-image {
            width: 100%;
            height: 230px;
            object-fit: cover;
            background: #f1f3f5;
        }


        /*
        |--------------------------------------------------------------------------
        | BODY
        |--------------------------------------------------------------------------
        */

        .history-card-body {
            padding: 18px;
            display: flex;
            flex-direction: column;
            height: calc(100% - 230px);
        }

        .history-category {
            display: inline-block;
            align-self: flex-start;

            background: #dff3e7;
            color: #198754;

            border-radius: 20px;
            padding: 5px 10px;

            font-size: 13px;
            font-weight: 600;

            margin-bottom: 10px;
        }

        .history-name {
            font-size: 19px;
            font-weight: 700;
            color: #212529;

            margin-bottom: 8px;
        }

        .history-price {
            color: #dc3545;
            font-size: 20px;
            font-weight: 700;

            margin-bottom: 12px;
        }

        .history-meta {
            font-size: 13px;
            color: #7b8794;

            margin-bottom: 15px;
        }


        /*
        |--------------------------------------------------------------------------
        | BUTTONS
        |--------------------------------------------------------------------------
        */

        .history-detail-button {
            margin-top: auto;
        }

        .clear-history-button {
            border-radius: 8px;
        }


        /*
        |--------------------------------------------------------------------------
        | EMPTY
        |--------------------------------------------------------------------------
        */

        .history-empty {
            background: white;

            border-radius: 16px;

            padding: 55px 20px;

            text-align: center;

            box-shadow:
                0 2px 8px
                rgba(0, 0, 0, 0.06);
        }

        .history-empty-icon {
            font-size: 55px;
            margin-bottom: 13px;
        }

        .history-empty h3 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .history-empty p {
            color: #6c757d;
            margin-bottom: 22px;
        }


        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

        @media (
            max-width: 767.98px
        ) {

            .history-header {
                padding: 22px;
            }

            .history-header h1 {
                font-size: 27px;
            }

            .history-product-image {
                height: 210px;
            }

        }

    </style>

</head>


<body>


{{-- ========================================================= --}}
{{-- NAVBAR --}}
{{-- ========================================================= --}}

@include('partials.store-navbar')



{{-- ========================================================= --}}
{{-- CONTENT --}}
{{-- ========================================================= --}}

<div
    class="
        container
        history-container
    "
>


    {{-- ===================================================== --}}
    {{-- CHUẨN HÓA BIẾN TỪ CONTROLLER --}}
    {{-- ===================================================== --}}

    @php

        /*
        |--------------------------------------------------------------------------
        | Controller hiện tại thường truyền biến $views.
        |
        | Các fallback phía sau giúp trang không bị 500 nếu trước đây
        | bạn từng đặt tên biến là $productViews hoặc $histories.
        |--------------------------------------------------------------------------
        */

        $historyItems =
            $views
            ??
            $productViews
            ??
            $histories
            ??
            collect();

    @endphp



    {{-- ===================================================== --}}
    {{-- HEADER --}}
    {{-- ===================================================== --}}

    <div
        class="
            history-header
            d-flex
            justify-content-between
            align-items-center
            flex-wrap
            gap-3
        "
    >

        <div>

            <h1>
                👀 Sản Phẩm Đã Xem
            </h1>

            <p>

                @if(
                    $historyItems->count() > 0
                )

                    Bạn đã xem
                    <strong>
                        {{ $historyItems->count() }}
                    </strong>
                    sản phẩm gần đây.

                @else

                    Lịch sử xem sản phẩm của bạn đang trống.

                @endif

            </p>

        </div>


        {{-- XÓA TOÀN BỘ LỊCH SỬ --}}

        @if(
            $historyItems->count() > 0
        )

            <form
                action="{{
                    route(
                        'product-history.clear'
                    )
                }}"
                method="POST"
                onsubmit="
                    return confirm(
                        'Bạn có chắc muốn xóa toàn bộ lịch sử đã xem?'
                    );
                "
            >

                @csrf
                @method('DELETE')


                <button
                    type="submit"
                    class="
                        btn
                        btn-outline-danger
                        clear-history-button
                    "
                >
                    🗑 Xóa lịch sử
                </button>

            </form>

        @endif

    </div>



    {{-- ===================================================== --}}
    {{-- DANH SÁCH SẢN PHẨM --}}
    {{-- ===================================================== --}}

    @if(
        $historyItems->count() > 0
    )

        <div class="row g-4">

            @foreach(
                $historyItems as $history
            )

                @php

                    $product =
                        $history->product;

                @endphp


                {{-- SẢN PHẨM VẪN CÒN TỒN TẠI --}}

                @if($product)

                    <div
                        class="
                            col-12
                            col-sm-6
                            col-lg-3
                        "
                    >

                        <div class="history-card">


                            {{-- ===================================== --}}
                            {{-- IMAGE --}}
                            {{-- ===================================== --}}

                            @if(
                                !empty(
                                    $product->image
                                )
                            )

                                <img
                                    src="{{
                                        asset(
                                            $product->image
                                        )
                                    }}"
                                    class="
                                        history-product-image
                                    "
                                    alt="{{
                                        $product->name
                                    }}"
                                >

                            @else

                                <div
                                    class="
                                        history-product-image
                                        d-flex
                                        align-items-center
                                        justify-content-center
                                        text-muted
                                    "
                                >
                                    🌱 Chưa có ảnh
                                </div>

                            @endif



                            {{-- ===================================== --}}
                            {{-- BODY --}}
                            {{-- ===================================== --}}

                            <div
                                class="
                                    history-card-body
                                "
                            >


                                {{-- CATEGORY --}}

                                <span
                                    class="
                                        history-category
                                    "
                                >
                                    {{
                                        $product
                                            ->category
                                            ?->name
                                        ??
                                        'Cây cảnh'
                                    }}
                                </span>



                                {{-- NAME --}}

                                <div
                                    class="
                                        history-name
                                    "
                                >
                                    {{
                                        $product->name
                                    }}
                                </div>



                                {{-- PRICE --}}

                                <div
                                    class="
                                        history-price
                                    "
                                >
                                    {{
                                        number_format(
                                            $product->price,
                                            0,
                                            ',',
                                            '.'
                                        )
                                    }} đ
                                </div>



                                {{-- THÔNG TIN LỊCH SỬ --}}

                                <div
                                    class="
                                        history-meta
                                    "
                                >

                                    👁 Đã xem:

                                    <strong>
                                        {{
                                            $history
                                                ->view_count
                                            ??
                                            1
                                        }}
                                        lần
                                    </strong>

                                    <br>

                                    🕒 Gần nhất:

                                    {{
                                        $history
                                            ->viewed_at
                                            ?->format(
                                                'H:i d/m/Y'
                                            )
                                        ??
                                        'Không xác định'
                                    }}

                                </div>



                                {{-- DETAIL --}}

                                <a
                                    href="{{
                                        route(
                                            'products.show_detail',
                                            $product->slug
                                        )
                                    }}"
                                    class="
                                        btn
                                        btn-success
                                        w-100
                                        history-detail-button
                                    "
                                >
                                    👁 Xem lại sản phẩm
                                </a>

                            </div>

                        </div>

                    </div>

                @endif

            @endforeach

        </div>


    @else


        {{-- ================================================= --}}
        {{-- CHƯA CÓ LỊCH SỬ --}}
        {{-- ================================================= --}}

        <div class="history-empty">

            <div
                class="
                    history-empty-icon
                "
            >
                👀
            </div>

            <h3>
                Chưa có sản phẩm đã xem
            </h3>

            <p>
                Khi bạn mở chi tiết một sản phẩm,
                sản phẩm đó sẽ được lưu lại tại đây.
            </p>

            <a
                href="{{ route('home') }}"
                class="
                    btn
                    btn-success
                    px-4
                "
            >
                🌿 Xem sản phẩm
            </a>

        </div>

    @endif


</div>



{{-- ========================================================= --}}
{{-- BOOTSTRAP --}}
{{-- ========================================================= --}}

<script
    src="{{
        asset(
            'bootstrap/js/bootstrap.bundle.min.js'
        )
    }}"
></script>


</body>

</html>