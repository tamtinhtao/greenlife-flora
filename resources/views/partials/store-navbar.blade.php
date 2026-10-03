@php
    /*
    |--------------------------------------------------------------------------
    | SỐ LƯỢNG GIỎ HÀNG
    |--------------------------------------------------------------------------
    */

    $navCart = session()->get(
        'cart',
        []
    );

    $navCartCount = array_sum(
        array_column(
            $navCart,
            'quantity'
        )
    );


    /*
    |--------------------------------------------------------------------------
    | SỐ LƯỢNG SẢN PHẨM YÊU THÍCH
    |--------------------------------------------------------------------------
    */

    $navWishlistCount = 0;

    if (
        auth()->check()
        &&
        auth()->user()->role === 'user'
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
        background: #198754;
    }

    .store-navbar .navbar-brand {
        color: #fff;
        font-weight: 700;
        font-size: 26px;
    }

    .store-navbar .navbar-brand:hover {
        color: #fff;
    }

    .store-navbar-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .store-nav-btn {
        position: relative;
        white-space: nowrap;
    }

    .store-count-badge {
        position: absolute;
        top: 0;
        left: 100%;
        transform: translate(-50%, -50%);
        font-size: 10px;
        min-width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50rem;
        background: #dc3545;
        color: white;
        padding: 2px 5px;
        z-index: 5;
    }

    .store-account-button {
        border-color: rgba(
            255,
            255,
            255,
            0.7
        );

        color: white;
    }

    .store-account-button:hover,
    .store-account-button:focus {
        background: white;
        color: #198754;
    }

    .store-navbar .dropdown-menu {
        min-width: 215px;
    }

    .store-navbar .dropdown-item {
        padding: 10px 16px;
    }

    .store-navbar .dropdown-item:hover {
        background: #f1f8f4;
        color: #198754;
    }

    .store-navbar .logout-button {
        width: 100%;
        text-align: left;
        border: none;
        background: transparent;
        padding: 10px 16px;
        color: #dc3545;
    }

    .store-navbar .logout-button:hover {
        background: #fff1f2;
    }

    @media (
        max-width: 991.98px
    ) {
        .store-navbar-actions {
            margin-top: 12px;
        }
    }
</style>


<nav
    class="
        navbar
        navbar-expand-lg
        navbar-dark
        store-navbar
    "
>
    <div class="container">


        {{-- ========================================================= --}}
        {{-- LOGO --}}
        {{-- ========================================================= --}}

        <a
            class="navbar-brand"
            href="{{ route('home') }}"
        >
            🌿 GreenLife Flora
        </a>


        {{-- ========================================================= --}}
        {{-- MOBILE BUTTON --}}
        {{-- ========================================================= --}}

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#storeNavbar"
            aria-controls="storeNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span
                class="navbar-toggler-icon"
            ></span>
        </button>


        <div
            class="
                collapse
                navbar-collapse
            "
            id="storeNavbar"
        >

            <div
                class="
                    store-navbar-actions
                    ms-auto
                "
            >


                {{-- ================================================= --}}
                {{-- CHƯA ĐĂNG NHẬP --}}
                {{-- ================================================= --}}

                @guest

                    <a
                        href="{{ route('login') }}"
                        class="
                            btn
                            btn-outline-light
                            btn-sm
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
                        "
                    >
                        📝 Đăng Ký
                    </a>

                @endguest



                {{-- ================================================= --}}
                {{-- ĐÃ ĐĂNG NHẬP --}}
                {{-- ================================================= --}}

                @auth


                    {{-- ================================================= --}}
                    {{-- USER --}}
                    {{-- ================================================= --}}

                    @if(
                        auth()->user()->role
                        ===
                        'user'
                    )


                        {{-- ============================= --}}
                        {{-- GIỎ HÀNG --}}
                        {{-- ============================= --}}

                        <a
                            href="{{ route('cart.index') }}"
                            class="
                                btn
                                btn-light
                                btn-sm
                                store-nav-btn
                            "
                        >
                            🛒 Giỏ Hàng


                            @if(
                                $navCartCount > 0
                            )

                                <span
                                    id="cart-count-badge"
                                    class="store-count-badge"
                                >
                                    {{
                                        $navCartCount
                                    }}
                                </span>

                            @else

                                <span
                                    id="cart-count-badge"
                                    class="store-count-badge"
                                    style="display: none;"
                                >
                                    0
                                </span>

                            @endif

                        </a>



                        {{-- ============================= --}}
                        {{-- YÊU THÍCH --}}
                        {{-- ============================= --}}

                        <a
                            href="{{
                                route(
                                    'wishlist.index'
                                )
                            }}"
                            class="
                                btn
                                btn-sm
                                store-nav-btn

                                {{
                                    request()->routeIs(
                                        'wishlist.*'
                                    )
                                        ? 'btn-warning'
                                        : 'btn-light'
                                }}
                            "
                        >
                            ❤️ Yêu Thích


                            @if(
                                $navWishlistCount > 0
                            )

                                <span
                                    class="
                                        store-count-badge
                                    "
                                >
                                    {{
                                        $navWishlistCount
                                    }}
                                </span>

                            @endif

                        </a>



                        {{-- ============================= --}}
                        {{-- ĐƠN HÀNG --}}
                        {{-- ============================= --}}

                        <a
                            href="{{
                                route(
                                    'my-orders.index'
                                )
                            }}"
                            class="
                                btn
                                btn-info
                                btn-sm
                            "
                        >
                            📦 Đơn Hàng Của Tôi
                        </a>



                        {{-- ================================================= --}}
                        {{-- MENU TÀI KHOẢN --}}
                        {{-- ================================================= --}}

                        <div class="dropdown">

                            <button
                                class="
                                    btn
                                    btn-sm
                                    dropdown-toggle
                                    store-account-button
                                "
                                type="button"
                                id="accountDropdown"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >
                                👤 {{
                                    auth()
                                        ->user()
                                        ->name
                                }}
                            </button>


                            <ul
                                class="
                                    dropdown-menu
                                    dropdown-menu-end
                                    shadow
                                "
                                aria-labelledby="
                                    accountDropdown
                                "
                            >


                                {{-- ========================= --}}
                                {{-- THÔNG TIN TÀI KHOẢN --}}
                                {{-- ========================= --}}

                                <li>

                                    <a
                                        class="
                                            dropdown-item
                                        "
                                        href="{{
                                            route(
                                                'account.profile'
                                            )
                                        }}"
                                    >
                                        👤 Thông tin tài khoản
                                    </a>

                                </li>



                                {{-- ========================= --}}
                                {{-- ĐỔI MẬT KHẨU --}}
                                {{-- ========================= --}}

                                <li>

                                    <a
                                        class="
                                            dropdown-item
                                        "
                                        href="{{
                                            route(
                                                'account.password'
                                            )
                                        }}"
                                    >
                                        🔑 Đổi mật khẩu
                                    </a>

                                </li>



                                {{-- ========================= --}}
                                {{-- SẢN PHẨM ĐÃ XEM --}}
                                {{-- ========================= --}}

                                <li>

                                    <a
                                        class="
                                            dropdown-item
                                        "
                                        href="{{
                                            route(
                                                'product-history.index'
                                            )
                                        }}"
                                    >
                                        👀 Sản phẩm đã xem
                                    </a>

                                </li>


                                {{--
                                =================================================
                                KHÔNG ĐỂ "SẢN PHẨM YÊU THÍCH" Ở ĐÂY NỮA

                                Vì nút Yêu Thích đã nằm trực tiếp
                                trên navbar phía trên.
                                =================================================
                                --}}


                                <li>
                                    <hr
                                        class="
                                            dropdown-divider
                                        "
                                    >
                                </li>



                                {{-- ========================= --}}
                                {{-- ĐĂNG XUẤT --}}
                                {{-- ========================= --}}

                                <li>

                                    <form
                                        action="{{
                                            route(
                                                'logout'
                                            )
                                        }}"
                                        method="POST"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="
                                                logout-button
                                            "
                                        >
                                            🚪 Đăng xuất
                                        </button>

                                    </form>

                                </li>

                            </ul>

                        </div>

                    @endif



                    {{-- ================================================= --}}
                    {{-- ADMIN NẾU VÀO TRANG BÁN HÀNG --}}
                    {{-- ================================================= --}}

                    @if(
                        auth()->user()->role
                        ===
                        'admin'
                    )

                        <a
                            href="{{
                                route(
                                    'admin.dashboard'
                                )
                            }}"
                            class="
                                btn
                                btn-warning
                                btn-sm
                            "
                        >
                            ⚙️ Trang quản trị
                        </a>


                        <form
                            action="{{
                                route(
                                    'logout'
                                )
                            }}"
                            method="POST"
                        >
                            @csrf

                            <button
                                type="submit"
                                class="
                                    btn
                                    btn-outline-light
                                    btn-sm
                                "
                            >
                                Đăng xuất
                            </button>

                        </form>

                    @endif


                @endauth

            </div>

        </div>

    </div>
</nav>