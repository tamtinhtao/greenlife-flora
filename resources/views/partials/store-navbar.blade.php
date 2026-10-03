@php

    /*
    |--------------------------------------------------------------------------
    | SỐ LƯỢNG GIỎ HÀNG
    |--------------------------------------------------------------------------
    */

    $navCart = session()->get('cart', []);

    $navCartCount = array_sum(
        array_column(
            $navCart,
            'quantity'
        )
    );


    /*
    |--------------------------------------------------------------------------
    | SỐ LƯỢNG YÊU THÍCH
    |--------------------------------------------------------------------------
    */

    $navWishlistCount = 0;

    if (
        auth()->check()
        && auth()->user()->role === 'user'
    ) {

        $navWishlistCount =
            auth()
                ->user()
                ->wishlistProducts()
                ->count();
    }

@endphp


<style>

    .store-navbar {
        z-index: 1030;
    }

    .store-navbar .navbar-brand {
        font-weight: 700;
    }

    .store-navbar .nav-action {
        min-height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        white-space: nowrap;
    }

    .store-navbar .account-button {
        min-height: 38px;
        border: 1px solid rgba(255, 255, 255, .55);
        color: #fff;
        background: rgba(255, 255, 255, .08);
    }

    .store-navbar .account-button:hover,
    .store-navbar .account-button:focus {
        background: #fff;
        color: #198754;
    }

</style>


<nav
    class="
        navbar
        navbar-expand-lg
        navbar-dark
        bg-success
        store-navbar
        sticky-top
        shadow-sm
    "
>

    <div class="container">


        {{-- LOGO --}}
        <a
            class="navbar-brand fs-4"
            href="{{ route('home') }}"
        >
            🌿 GreenLife Flora
        </a>



        <div
            class="
                d-flex
                align-items-center
                gap-2
            "
        >


            {{-- ============================================= --}}
            {{-- CHƯA ĐĂNG NHẬP --}}
            {{-- ============================================= --}}

            @guest

                <a
                    href="{{ route('login') }}"
                    class="
                        btn
                        btn-outline-light
                        btn-sm
                        nav-action
                    "
                >
                    🔐 Đăng Nhập
                </a>


                <a
                    href="{{ route('register') }}"
                    class="
                        btn
                        btn-light
                        btn-sm
                        text-success
                        nav-action
                    "
                >
                    📝 Đăng Ký
                </a>

            @endguest



            {{-- ============================================= --}}
            {{-- ĐÃ ĐĂNG NHẬP --}}
            {{-- ============================================= --}}

            @auth


                {{-- ========================================= --}}
                {{-- USER --}}
                {{-- ========================================= --}}

                @if(auth()->user()->role === 'user')


                    {{-- GIỎ HÀNG --}}
                    <a
                        href="{{ route('cart.index') }}"
                        class="
                            btn
                            {{
                                request()->routeIs('cart.*')
                                    ? 'btn-warning'
                                    : 'btn-light'
                            }}
                            btn-sm
                            nav-action
                            position-relative
                        "
                    >

                        🛒 Giỏ Hàng


                        <span
                            id="cart-count-badge"
                            class="
                                position-absolute
                                top-0
                                start-100
                                translate-middle
                                badge
                                rounded-pill
                                bg-danger
                            "
                            style="
                                font-size: 10px;
                                {{
                                    $navCartCount <= 0
                                        ? 'display:none;'
                                        : ''
                                }}
                            "
                        >
                            {{ $navCartCount }}
                        </span>

                    </a>



                    {{-- YÊU THÍCH --}}
                    <a
                        href="{{ route('wishlist.index') }}"
                        class="
                            btn
                            {{
                                request()->routeIs('wishlist.*')
                                    ? 'btn-warning'
                                    : 'btn-light'
                            }}
                            btn-sm
                            nav-action
                            position-relative
                        "
                    >

                        ❤️ Yêu Thích


                        @if($navWishlistCount > 0)

                            <span
                                id="wishlist-count-badge"
                                class="
                                    position-absolute
                                    top-0
                                    start-100
                                    translate-middle
                                    badge
                                    rounded-pill
                                    bg-danger
                                "
                                style="font-size: 10px;"
                            >
                                {{ $navWishlistCount }}
                            </span>

                        @endif

                    </a>



                    {{-- ĐƠN HÀNG --}}
                    <a
                        href="{{
                            route(
                                'my-orders.index'
                            )
                        }}"
                        class="
                            btn
                            {{
                                request()->routeIs('my-orders.*')
                                    ? 'btn-warning'
                                    : 'btn-info'
                            }}
                            btn-sm
                            nav-action
                        "
                    >
                        📦 Đơn Hàng Của Tôi
                    </a>


                @endif



                {{-- ========================================= --}}
                {{-- ADMIN --}}
                {{-- ========================================= --}}

                @if(auth()->user()->role === 'admin')

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="
                            btn
                            btn-light
                            btn-sm
                            nav-action
                        "
                    >
                        ⚙️ Trang Quản Trị
                    </a>

                @endif



                {{-- ========================================= --}}
                {{-- MENU TÀI KHOẢN --}}
                {{-- ========================================= --}}

                <div class="dropdown">

                    <button
                        type="button"
                        class="
                            btn
                            btn-sm
                            dropdown-toggle
                            account-button
                        "
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >
                        👤 {{ auth()->user()->name }}
                    </button>


                    <ul
                        class="
                            dropdown-menu
                            dropdown-menu-end
                            shadow
                        "
                    >


                        <li>

                            <a
                                class="dropdown-item"
                                href="{{
                                    route(
                                        'account.profile'
                                    )
                                }}"
                            >
                                👤 Thông tin tài khoản
                            </a>

                        </li>


                        <li>

                            <a
                                class="dropdown-item"
                                href="{{
                                    route(
                                        'account.password'
                                    )
                                }}"
                            >
                                🔑 Đổi mật khẩu
                            </a>

                        </li>


                        @if(auth()->user()->role === 'user')

                            <li>

                                <a
                                    class="dropdown-item"
                                    href="{{
                                        route(
                                            'product-history.index'
                                        )
                                    }}"
                                >
                                    👀 Sản phẩm đã xem
                                </a>

                            </li>


                            <li>

                                <a
                                    class="dropdown-item"
                                    href="{{
                                        route(
                                            'wishlist.index'
                                        )
                                    }}"
                                >
                                    ❤️ Sản phẩm yêu thích
                                </a>

                            </li>

                        @endif


                        <li>
                            <hr class="dropdown-divider">
                        </li>


                        <li>

                            <form
                                action="{{ route('logout') }}"
                                method="POST"
                            >

                                @csrf


                                <button
                                    type="submit"
                                    class="
                                        dropdown-item
                                        text-danger
                                    "
                                >
                                    🚪 Đăng xuất
                                </button>

                            </form>

                        </li>

                    </ul>

                </div>


            @endauth


        </div>

    </div>

</nav>