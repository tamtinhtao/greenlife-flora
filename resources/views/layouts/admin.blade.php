<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'GreenLife Admin')
    </title>


    <link
        href="{{ asset('bootstrap/css/bootstrap.min.css') }}"
        rel="stylesheet"
    >


    <style>

        :root {

            --sidebar-width:
                290px;

            --sidebar-bg:
                #1f3042;

            --sidebar-dark:
                #192a3a;

            --topbar-bg:
                #3898c4;

            --page-bg:
                #f4f6f9;

            --line:
                #e5e9ed;
        }


        * {
            box-sizing: border-box;
        }


        html,
        body {

            margin: 0;

            min-height: 100%;

            background:
                var(--page-bg);

            color: #2f3439;

            font-family:
                Arial,
                Helvetica,
                sans-serif;
        }


        body {
            overflow-x: hidden;
        }



        /* =====================================================
           SIDEBAR
        ===================================================== */

        .admin-sidebar {

            position: fixed;

            top: 0;
            left: 0;
            bottom: 0;

            width:
                var(--sidebar-width);

            display: flex;

            flex-direction: column;

            background:
                var(--sidebar-bg);

            color: white;

            z-index: 1040;

            box-shadow:
                2px 0 8px
                rgba(0, 0, 0, .05);
        }



        /* =====================================================
           BRAND
        ===================================================== */

        .admin-brand {

            min-height: 100px;

            display: flex;

            align-items: center;

            gap: 15px;

            padding:
                18px 25px;

            background:
                #263c52;

            color: white;

            text-decoration: none;

            border-bottom:
                1px solid
                rgba(
                    255,
                    255,
                    255,
                    .06
                );
        }


        .admin-brand:hover {
            color: white;
        }


        .admin-brand-icon {

            width: 54px;
            height: 54px;

            flex: 0 0 54px;

            border-radius: 8px;

            display: flex;

            align-items: center;

            justify-content: center;

            background:
                #198754;

            font-size: 21px;

            font-weight: 800;
        }


        .admin-brand-name {

            font-size: 22px;

            font-weight: 700;

            line-height: 1.1;
        }


        .admin-brand-subtitle {

            margin-top: 5px;

            color: #a9c8e4;

            font-size: 12px;

            text-transform:
                uppercase;
        }



        /* =====================================================
           ADMIN PROFILE
        ===================================================== */

        .admin-profile {

            min-height: 108px;

            display: flex;

            align-items: center;

            gap: 15px;

            padding:
                20px 25px;

            border-bottom:
                1px solid
                rgba(
                    255,
                    255,
                    255,
                    .06
                );
        }


        .admin-avatar {

            width: 52px;
            height: 52px;

            flex: 0 0 52px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            background: white;

            color:
                #1f3042;

            font-size: 20px;

            font-weight: 700;
        }


        .admin-profile-name {

            font-size: 15px;

            font-weight: 700;
        }


        .admin-profile-status {

            margin-top: 5px;

            color: #a9bac8;

            font-size: 12px;
        }


        .admin-online-dot {

            display: inline-block;

            width: 8px;
            height: 8px;

            margin-right: 5px;

            border-radius: 50%;

            background:
                #20c997;
        }



        /* =====================================================
           MENU
        ===================================================== */

        .admin-menu {

            flex: 1;

            overflow-y: auto;

            padding-bottom: 20px;
        }


        .admin-menu-title {

            padding:
                16px 25px 10px;

            color:
                #8196ab;

            background:
                rgba(
                    0,
                    0,
                    0,
                    .10
                );

            font-size: 10px;

            font-weight: 700;

            text-transform:
                uppercase;

            letter-spacing:
                .6px;
        }


        .admin-menu-link {

            min-height: 55px;

            display: flex;

            align-items: center;

            padding:
                0 24px;

            border-left:
                4px solid
                transparent;

            color:
                #e0e7ec;

            text-decoration: none;

            transition:
                .15s;
        }


        .admin-menu-link:hover {

            color: white;

            background:
                #192a3a;

            border-left-color:
                #3a9dcc;
        }


        .admin-menu-link.active {

            color: white;

            background:
                #172635;

            border-left-color:
                #3a9dcc;
        }


        .admin-menu-icon {

            width: 36px;

            display: inline-block;

            font-size: 16px;
        }



        /* =====================================================
           MAIN
        ===================================================== */

        .admin-main {

            min-height: 100vh;

            margin-left:
                var(--sidebar-width);
        }



        /* =====================================================
           TOP BAR
        ===================================================== */

        .admin-topbar {

            height: 100px;

            padding:
                0 34px;

            display: flex;

            align-items: center;

            justify-content:
                space-between;

            background:
                var(--topbar-bg);

            color: white;
        }


        .admin-topbar-left {

            display: flex;

            align-items: center;

            gap: 25px;
        }


        .admin-hamburger {

            font-size: 28px;

            line-height: 1;
        }


        .admin-topbar-title {

            font-size: 21px;

            font-weight: 700;
        }


        .admin-topbar-subtitle {

            margin-top: 5px;

            font-size: 12px;

            color:
                rgba(
                    255,
                    255,
                    255,
                    .92
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Chỉ giữ 1 nút đăng xuất trên topbar.
        | Không lặp tên Admin lần thứ hai.
        |--------------------------------------------------------------------------
        */

        .admin-logout-btn {

            padding:
                9px 18px;

            border:
                1px solid
                rgba(
                    255,
                    255,
                    255,
                    .5
                );

            border-radius: 5px;

            background:
                transparent;

            color: white;
        }


        .admin-logout-btn:hover {

            background:
                #dc3545;

            border-color:
                #dc3545;
        }



        /* =====================================================
           CONTENT
        ===================================================== */

        .admin-content {

            padding:
                28px 32px 45px;
        }


        .admin-page-title {

            margin:
                0 0 4px;

            font-size: 27px;

            font-weight: 500;
        }


        .admin-page-description {

            margin-bottom: 23px;

            color:
                #8997a4;
        }



        /* =====================================================
           COMMON
        ===================================================== */

        .admin-card {

            background: white;

            border:
                1px solid
                #e1e5e8;

            box-shadow:
                0 1px 4px
                rgba(
                    0,
                    0,
                    0,
                    .05
                );
        }


        .table thead th {

            background:
                #eef2f5;

            white-space: nowrap;
        }



        /* =====================================================
           DASHBOARD
        ===================================================== */

        .dashboard-stats {

            display: grid;

            grid-template-columns:
                repeat(
                    4,
                    minmax(0, 1fr)
                );

            gap: 24px;

            margin-bottom: 30px;
        }


        .dashboard-stat {

            position: relative;

            min-height: 174px;

            overflow: hidden;

            color: white;
        }


        .dashboard-stat-body {

            padding:
                28px 24px 52px;
        }


        .dashboard-stat-number {

            font-size: 40px;

            line-height: 1;

            font-weight: 700;
        }


        .dashboard-stat-label {

            margin-top: 20px;

            font-size: 14px;

            text-transform:
                uppercase;
        }


        .dashboard-stat-icon {

            position: absolute;

            right: 25px;
            top: 30px;

            font-size: 58px;

            opacity: .14;
        }


        .dashboard-stat-footer {

            position: absolute;

            left: 0;
            right: 0;
            bottom: 0;

            min-height: 44px;

            display: flex;

            align-items: center;

            justify-content: center;

            color: white;

            text-decoration: none;

            background:
                rgba(
                    0,
                    0,
                    0,
                    .12
                );
        }


        .dashboard-stat-footer:hover {

            color: white;

            background:
                rgba(
                    0,
                    0,
                    0,
                    .19
                );
        }


        .dashboard-orders {
            background: #13b8da;
        }


        .dashboard-products {
            background: #08ad61;
        }


        .dashboard-categories {
            background: #f4a014;
        }


        .dashboard-users {
            background: #e74b3c;
        }


        .dashboard-bottom {

            display: grid;

            grid-template-columns:
                1.35fr 1fr;

            gap: 25px;
        }


        .dashboard-panel {

            background: white;

            border-top:
                3px solid
                #3a96c2;

            box-shadow:
                0 1px 4px
                rgba(
                    0,
                    0,
                    0,
                    .07
                );
        }


        .dashboard-panel.orange {

            border-top-color:
                #f2a013;
        }


        .dashboard-panel-header {

            min-height: 67px;

            padding:
                0 18px;

            display: flex;

            align-items: center;

            justify-content:
                space-between;

            border-bottom:
                1px solid #e5e5e5;

            font-weight: 700;

            font-size: 15px;
        }


        .dashboard-system-row {

            min-height: 58px;

            padding:
                0 17px;

            display: flex;

            align-items: center;

            justify-content:
                space-between;

            border-bottom:
                1px solid #ececec;
        }


        .dashboard-system-value {
            font-weight: 700;
        }


        .dashboard-status {

            display: inline-block;

            padding:
                5px 8px;

            border-radius: 4px;

            color: white;

            font-size: 11px;
        }


        .dashboard-status.warning {
            background: #f4a014;
        }


        .dashboard-status.info {
            background: #3892bf;
        }


        .dashboard-status.success {
            background: #08ad61;
        }


        .dashboard-status.danger {
            background: #e74b3c;
        }





        /* =====================================================
   CHAT TRONG SIDEBAR
===================================================== */

.admin-chat-menu-badge {

    display: none;

    min-width: 21px;
    height: 21px;

    padding: 0 6px;

    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #dc3545;

    color: white;

    font-size: 10px;
    font-weight: 700;
}



        /* =====================================================
           CHAT POPUP
        ===================================================== */

        .admin-chat-popup {

            position: fixed;

            right: 24px;
            bottom: 82px;

            width: 720px;
            height: 510px;

            display: none;

            grid-template-columns:
                230px 1fr;

            overflow: hidden;

            background: white;

            border:
                1px solid
                #dce2e6;

            border-radius: 9px;

            box-shadow:
                0 10px 35px
                rgba(
                    0,
                    0,
                    0,
                    .22
                );

            z-index: 1201;
        }


        .admin-chat-popup.show {
            display: grid;
        }



        /* USERS */

        .admin-chat-users {

            display: flex;

            flex-direction: column;

            min-width: 0;

            background:
                #f5f7f9;

            border-right:
                1px solid
                #e2e7eb;
        }


        .admin-chat-users-title {

            min-height: 58px;

            display: flex;

            align-items: center;

            padding:
                0 15px;

            border-bottom:
                1px solid
                #e1e5e8;

            font-weight: 700;
        }


        .admin-chat-user-list {

            flex: 1;

            overflow-y: auto;
        }


        .admin-chat-user {

            width: 100%;

            border: 0;

            border-bottom:
                1px solid
                #e5e9ed;

            padding:
                12px 13px;

            background:
                transparent;

            text-align: left;

            cursor: pointer;
        }


        .admin-chat-user:hover {

            background:
                #eaf2f7;
        }


        .admin-chat-user.active {

            background:
                #dceef8;
        }


        .admin-chat-user-name {

            display: flex;

            align-items: center;

            justify-content:
                space-between;

            gap: 5px;

            font-weight: 700;

            font-size: 13px;
        }


        .admin-chat-user-email {

            margin-top: 3px;

            overflow: hidden;

            color:
                #7b8893;

            font-size: 11px;

            text-overflow:
                ellipsis;

            white-space: nowrap;
        }


        .admin-chat-user-badge {

            min-width: 20px;

            height: 20px;

            padding:
                0 5px;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            border-radius: 10px;

            background:
                #dc3545;

            color: white;

            font-size: 10px;
        }



        /* CONVERSATION */

        .admin-chat-conversation {

            min-width: 0;

            display: flex;

            flex-direction: column;
        }


        .admin-chat-header {

            min-height: 58px;

            display: flex;

            align-items: center;

            justify-content:
                space-between;

            padding:
                0 15px;

            border-bottom:
                1px solid
                #e1e5e8;
        }


        .admin-chat-header-name {

            font-weight: 700;
        }


        .admin-chat-close {

            border: 0;

            background:
                transparent;

            font-size: 22px;

            color:
                #6c757d;
        }


        .admin-chat-messages {

            flex: 1;

            overflow-y: auto;

            padding: 15px;

            background:
                #f8fafb;
        }


        .admin-chat-empty {

            height: 100%;

            display: flex;

            align-items: center;

            justify-content: center;

            color:
                #8a96a0;

            text-align: center;
        }


        .admin-chat-message {

            max-width: 78%;

            margin-bottom: 10px;

            padding:
                9px 11px;

            border-radius: 10px;

            word-break:
                break-word;

            font-size: 13px;
        }


        .admin-chat-message.mine {

            margin-left: auto;

            background:
                #3898c4;

            color: white;

            border-bottom-right-radius:
                3px;
        }


        .admin-chat-message.theirs {

            margin-right: auto;

            background: white;

            color: #333;

            border:
                1px solid
                #e0e5e8;

            border-bottom-left-radius:
                3px;
        }


        .admin-chat-message-time {

            margin-top: 4px;

            opacity: .7;

            font-size: 10px;
        }


        .admin-chat-compose {

            display: flex;

            gap: 8px;

            padding: 12px;

            border-top:
                1px solid
                #e1e5e8;

            background: white;
        }


        .admin-chat-input {

            flex: 1;
        }



        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (
            max-width: 1100px
        ) {

            .dashboard-stats {

                grid-template-columns:
                    repeat(
                        2,
                        minmax(0, 1fr)
                    );
            }


            .dashboard-bottom {

                grid-template-columns:
                    1fr;
            }
        }


        @media (
            max-width: 850px
        ) {

            :root {

                --sidebar-width:
                    220px;
            }


            .admin-brand-name {
                font-size: 17px;
            }


            .admin-topbar {

                padding:
                    0 18px;
            }


            .admin-chat-popup {

                width:
                    calc(
                        100vw - 250px
                    );

                right: 15px;
            }
        }
        /* =====================================================
   LOGOUT SIDEBAR
===================================================== */

.admin-sidebar-bottom {

    padding: 14px 18px;

    border-top:
        1px solid
        rgba(
            255,
            255,
            255,
            .08
        );

    background: #1b2b3b;
}


.admin-sidebar-logout {

    width: 100%;

    min-height: 43px;

    display: flex;

    align-items: center;
    justify-content: center;

    gap: 8px;

    border:
        1px solid #536575;

    border-radius: 5px;

    background: transparent;

    color: #e6edf2;

    font-size: 13px;

    transition: .15s;
}


.admin-sidebar-logout:hover {

    background: #dc3545;

    border-color: #dc3545;

    color: white;
}

    </style>


    @stack('styles')

</head>



<body>


{{-- =========================================================
     SIDEBAR
========================================================= --}}

<aside class="admin-sidebar">


    <a
        href="{{
            route(
                'admin.dashboard'
            )
        }}"
        class="admin-brand"
    >

        <div class="admin-brand-icon">

            GF

        </div>


        <div>

            <div class="admin-brand-name">

                GreenLife Flora

            </div>

            <div class="admin-brand-subtitle">

                Admin Panel

            </div>

        </div>

    </a>



    {{-- CHỈ HIỂN THỊ TÊN ADMIN Ở ĐÂY --}}
    <div class="admin-profile">


        <div class="admin-avatar">

            {{
                strtoupper(
                    substr(
                        auth()->user()->name
                        ?? 'A',
                        0,
                        1
                    )
                )
            }}

        </div>


        <div>

            <div class="admin-profile-name">

                {{ auth()->user()->name }}

            </div>


            <div class="admin-profile-status">

                <span
                    class="admin-online-dot"
                ></span>

                Online · Quản trị viên

            </div>

        </div>

    </div>



    <nav class="admin-menu">


    {{-- ===================================================== --}}
    {{-- QUẢN LÝ HỆ THỐNG --}}
    {{-- ===================================================== --}}

    <div class="admin-menu-title">
        Quản lý hệ thống
    </div>


    {{-- DASHBOARD --}}
    <a
        href="{{ route('admin.dashboard') }}"
        class="
            admin-menu-link
            {{
                request()->routeIs('admin.dashboard')
                    ? 'active'
                    : ''
            }}
        "
    >
        <span class="admin-menu-icon">
            ▦
        </span>

        Dashboard
    </a>


    {{-- DANH MỤC --}}
    <a
        href="{{ route('admin.categories.index') }}"
        class="
            admin-menu-link
            {{
                request()->routeIs('admin.categories.*')
                    ? 'active'
                    : ''
            }}
        "
    >
        <span class="admin-menu-icon">
            ☷
        </span>

        Quản trị danh mục
    </a>


    {{-- SẢN PHẨM --}}
    <a
        href="{{ route('admin.products.index') }}"
        class="
            admin-menu-link
            {{
                request()->routeIs('admin.products.*')
                    ? 'active'
                    : ''
            }}
        "
    >
        <span class="admin-menu-icon">
            ▣
        </span>

        Quản trị sản phẩm
    </a>


    {{-- ===================================================== --}}
    {{-- VOUCHER --}}
    {{-- ===================================================== --}}

    <a
        href="{{ route('admin.coupons.index') }}"
        class="
            admin-menu-link
            {{
                request()->routeIs('admin.coupons.*')
                    ? 'active'
                    : ''
            }}
        "
    >
        <span class="admin-menu-icon">
            🎟
        </span>

        Voucher - Khuyến mãi
    </a>


    {{-- ĐƠN HÀNG --}}
    <a
        href="{{ route('admin.orders.index') }}"
        class="
            admin-menu-link
            {{
                request()->routeIs('admin.orders.*')
                    ? 'active'
                    : ''
            }}
        "
    >
        <span class="admin-menu-icon">
            ▤
        </span>

        Quản lý đơn hàng
    </a>


    {{-- TÀI KHOẢN --}}
    <a
        href="{{ route('admin.users.index') }}"
        class="
            admin-menu-link
            {{
                request()->routeIs('admin.users.*')
                    ? 'active'
                    : ''
            }}
        "
    >
        <span class="admin-menu-icon">
            ♟
        </span>

        Quản lý tài khoản
    </a>


    {{-- CHAT KHÁCH HÀNG --}}
    <a
        href="{{ route('admin.chat.index') }}"
        class="
            admin-menu-link
            {{
                request()->routeIs('admin.chat.*')
                    ? 'active'
                    : ''
            }}
        "
    >
        <span class="admin-menu-icon">
            💬
        </span>

        Chat khách hàng
    </a>



    {{-- ===================================================== --}}
    {{-- TÀI CHÍNH & BÁO CÁO --}}
    {{-- ===================================================== --}}

    <div class="admin-menu-title">
        Tài chính & Báo cáo
    </div>


    {{-- TỔNG QUAN TÀI CHÍNH --}}
    <a
        href="{{ route('admin.finance.index') }}"
        class="
            admin-menu-link
            {{
                request()->routeIs('admin.finance.index')
                    ? 'active'
                    : ''
            }}
        "
    >
        <span class="admin-menu-icon">
            ₫
        </span>

        Tổng quan tài chính
    </a>


    {{-- GIAO DỊCH --}}
    <a
        href="{{ route('admin.finance.transactions') }}"
        class="
            admin-menu-link
            {{
                request()->routeIs(
                    'admin.finance.transactions'
                )
                    ? 'active'
                    : ''
            }}
        "
    >
        <span class="admin-menu-icon">
            💳
        </span>

        Giao dịch thanh toán
    </a>


    {{-- BÁO CÁO --}}
    <a
        href="{{ route('admin.reports.index') }}"
        class="
            admin-menu-link
            {{
                request()->routeIs('admin.reports.*')
                    ? 'active'
                    : ''
            }}
        "
    >
        <span class="admin-menu-icon">
            ▥
        </span>

        Báo cáo doanh thu
    </a>


</nav>


{{-- =====================================================
     ĐĂNG XUẤT
===================================================== --}}

<div class="admin-sidebar-bottom">

    <form
        action="{{ route('logout') }}"
        method="POST"
    >

        @csrf

        <button
            type="submit"
            class="admin-sidebar-logout"
        >

            <span>
                🚪
            </span>

            Đăng xuất

        </button>

    </form>

</div>

</aside>





{{-- =========================================================
     MAIN
========================================================= --}}

<div class="admin-main">


    <header class="admin-topbar">


        <div class="admin-topbar-left">


            <div class="admin-hamburger">

                ☰

            </div>


            <div>

                <div class="admin-topbar-title">

                    @yield(
                        'page-title',
                        'Dashboard'
                    )

                </div>


                <div class="admin-topbar-subtitle">

                    @yield(
                        'page-subtitle',
                        'Control panel'
                    )

                </div>

            </div>

        </div>



        

    </header>



    <main class="admin-content">


        @if(
            session('success')
        )

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



        @if(
            session('error')
        )

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



        @if(
            session('bulk_errors')
            &&
            count(
                session(
                    'bulk_errors'
                )
            )
        )

            <div class="alert alert-warning">

                <strong>
                    Một số đơn chưa xử lý được:
                </strong>


                <ul class="mb-0 mt-2">

                    @foreach(
                        session(
                            'bulk_errors'
                        )
                        as $bulkError
                    )

                        <li>
                            {{ $bulkError }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif



        @if(
            $errors->any()
        )

            <div class="alert alert-danger">

                <ul class="mb-0">

                    @foreach(
                        $errors->all()
                        as $error
                    )

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif



        @yield('content')

    </main>

</div>







<div
    id="admin-chat-popup"
    class="admin-chat-popup"
>


    {{-- USER LIST --}}
    <div class="admin-chat-users">


        <div class="admin-chat-users-title">

            Khách hàng

        </div>


        <div
            id="admin-chat-user-list"
            class="admin-chat-user-list"
        >

            <div
                class="
                    text-muted
                    small
                    p-3
                "
            >

                Đang tải...

            </div>

        </div>

    </div>



    {{-- CONVERSATION --}}
    <div class="admin-chat-conversation">


        <div class="admin-chat-header">


            <div
                id="admin-chat-header-name"
                class="admin-chat-header-name"
            >

                Chọn khách hàng

            </div>


            <button
                type="button"
                id="admin-chat-close"
                class="admin-chat-close"
            >

                ×

            </button>

        </div>



        <div
            id="admin-chat-messages"
            class="admin-chat-messages"
        >

            <div class="admin-chat-empty">

                Chọn một khách hàng bên trái
                để xem cuộc trò chuyện.

            </div>

        </div>



        <div class="admin-chat-compose">


            <input
                type="text"
                id="admin-chat-input"
                class="
                    form-control
                    admin-chat-input
                "
                placeholder="Nhập tin nhắn..."
                disabled
            >


            <button
                type="button"
                id="admin-chat-send"
                class="btn btn-primary"
                disabled
            >

                Gửi

            </button>

        </div>

    </div>

</div>



<script
    src="{{
        asset(
            'bootstrap/js/bootstrap.bundle.min.js'
        )
    }}"
></script>



{{-- =========================================================
     CHAT SCRIPT
========================================================= --}}

<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        const toggle =
    document.getElementById(
        'admin-chat-menu'
    );


        const popup =
            document.getElementById(
                'admin-chat-popup'
            );


        const closeButton =
            document.getElementById(
                'admin-chat-close'
            );


        const userList =
            document.getElementById(
                'admin-chat-user-list'
            );


        const headerName =
            document.getElementById(
                'admin-chat-header-name'
            );


        const messagesBox =
            document.getElementById(
                'admin-chat-messages'
            );


        const input =
            document.getElementById(
                'admin-chat-input'
            );


        const sendButton =
            document.getElementById(
                'admin-chat-send'
            );


        const unreadTotal =
            document.getElementById(
                'admin-chat-unread-total'
            );


        const csrfToken =
            document.querySelector(
                'meta[name="csrf-token"]'
            ).content;


        const currentAdminId =
            {{ auth()->id() }};


        let selectedUserId =
            null;


        let selectedUserName =
            null;



        function escapeHtml(text)
        {
            const div =
                document.createElement(
                    'div'
                );

            div.textContent =
                text ?? '';

            return div.innerHTML;
        }



        function formatTime(value)
        {
            if (!value) {
                return '';
            }


            const date =
                new Date(value);


            if (
                Number.isNaN(
                    date.getTime()
                )
            ) {
                return '';
            }


            return date
                .toLocaleString(
                    'vi-VN',
                    {
                        hour:
                            '2-digit',

                        minute:
                            '2-digit',

                        day:
                            '2-digit',

                        month:
                            '2-digit'
                    }
                );
        }



        async function loadUsers()
        {
            try {

                const response =
                    await fetch(
                        "{{ route('admin.chat.users') }}",
                        {
                            headers: {
                                'Accept':
                                    'application/json'
                            }
                        }
                    );


                if (!response.ok) {
                    throw new Error(
                        'Không tải được danh sách khách hàng.'
                    );
                }


                const users =
                    await response.json();


                const totalUnread =
                    users.reduce(
                        function (
                            total,
                            user
                        ) {

                            return (
                                total
                                +
                                Number(
                                    user
                                        .unread_count
                                    || 0
                                )
                            );
                        },
                        0
                    );


                unreadTotal.textContent =
                    totalUnread;


                unreadTotal.style.display =
                    totalUnread > 0
                        ? 'inline-flex'
                        : 'none';


                if (
                    users.length
                    === 0
                ) {

                    userList.innerHTML =
                        `
                        <div class="text-muted small p-3">
                            Chưa có khách hàng nào nhắn tin.
                        </div>
                        `;

                    return;
                }


                userList.innerHTML =
                    users.map(
                        function (user) {

                            const active =
                                Number(
                                    user.id
                                )
                                ===
                                Number(
                                    selectedUserId
                                )
                                    ? 'active'
                                    : '';


                            const unread =
                                Number(
                                    user
                                        .unread_count
                                    || 0
                                );


                            return `
                                <button
                                    type="button"
                                    class="admin-chat-user ${active}"
                                    data-user-id="${user.id}"
                                    data-user-name="${escapeHtml(user.name)}"
                                >

                                    <div class="admin-chat-user-name">

                                        <span>
                                            ${escapeHtml(user.name)}
                                        </span>

                                        ${
                                            unread > 0
                                                ?
                                                `
                                                <span class="admin-chat-user-badge">
                                                    ${unread}
                                                </span>
                                                `
                                                :
                                                ''
                                        }

                                    </div>

                                    <div class="admin-chat-user-email">
                                        ${escapeHtml(user.email || '')}
                                    </div>

                                </button>
                            `;
                        }
                    ).join('');


                document
                    .querySelectorAll(
                        '.admin-chat-user'
                    )
                    .forEach(
                        function (button) {

                            button.addEventListener(
                                'click',
                                function () {

                                    selectedUserId =
                                        this.dataset
                                            .userId;


                                    selectedUserName =
                                        this.dataset
                                            .userName;


                                    headerName
                                        .textContent =
                                        selectedUserName;


                                    input.disabled =
                                        false;


                                    sendButton.disabled =
                                        false;


                                    loadUsers();

                                    loadMessages(
                                        true
                                    );


                                    input.focus();
                                }
                            );
                        }
                    );

            } catch (error) {

                console.error(error);
            }
        }



        async function loadMessages(
            scrollBottom = false
        ) {

            if (!selectedUserId) {
                return;
            }


            try {

                const url =
                    "{{ route('admin.chat.messages', ['userId' => '__USER__']) }}"
                        .replace(
                            '__USER__',
                            selectedUserId
                        );


                const response =
                    await fetch(
                        url,
                        {
                            headers: {
                                'Accept':
                                    'application/json'
                            }
                        }
                    );


                if (!response.ok) {
                    throw new Error(
                        'Không tải được tin nhắn.'
                    );
                }


                const messages =
                    await response.json();


                if (
                    messages.length
                    === 0
                ) {

                    messagesBox.innerHTML =
                        `
                        <div class="admin-chat-empty">
                            Chưa có tin nhắn.
                        </div>
                        `;

                } else {

                    messagesBox.innerHTML =
                        messages.map(
                            function (
                                message
                            ) {

                                const mine =
                                    Number(
                                        message
                                            .sender_id
                                    )
                                    ===
                                    Number(
                                        currentAdminId
                                    );


                                return `
                                    <div
                                        class="
                                            admin-chat-message
                                            ${
                                                mine
                                                    ? 'mine'
                                                    : 'theirs'
                                            }
                                        "
                                    >

                                        <div>
                                            ${escapeHtml(message.content)}
                                        </div>

                                        <div class="admin-chat-message-time">
                                            ${formatTime(message.created_at)}
                                        </div>

                                    </div>
                                `;
                            }
                        ).join('');
                }


                if (scrollBottom) {

                    messagesBox.scrollTop =
                        messagesBox
                            .scrollHeight;
                }


                loadUsers();

            } catch (error) {

                console.error(error);
            }
        }



        async function sendMessage()
        {
            if (
                !selectedUserId
                ||
                !input.value.trim()
            ) {
                return;
            }


            const content =
                input.value.trim();


            sendButton.disabled =
                true;


            try {

                const response =
                    await fetch(
                        "{{ route('admin.chat.send') }}",
                        {
                            method:
                                'POST',

                            headers: {

                                'Accept':
                                    'application/json',

                                'Content-Type':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    csrfToken
                            },

                            body:
                                JSON.stringify(
                                    {
                                        user_id:
                                            selectedUserId,

                                        message:
                                            content
                                    }
                                )
                        }
                    );


                const data =
                    await response.json();


                if (!response.ok) {

                    throw new Error(
                        data.message
                        ||
                        data.error
                        ||
                        'Không gửi được tin nhắn.'
                    );
                }


                input.value =
                    '';


                await loadMessages(
                    true
                );


            } catch (error) {

                alert(
                    error.message
                );

            } finally {

                sendButton.disabled =
                    false;

                input.focus();
            }
        }



        toggle.addEventListener(
            'click',
            function () {

                popup.classList.toggle(
                    'show'
                );


                if (
                    popup.classList
                        .contains(
                            'show'
                        )
                ) {

                    loadUsers();


                    if (
                        selectedUserId
                    ) {

                        loadMessages(
                            true
                        );
                    }
                }
            }
        );



        closeButton.addEventListener(
            'click',
            function () {

                popup.classList.remove(
                    'show'
                );
            }
        );



        sendButton.addEventListener(
            'click',
            sendMessage
        );



        input.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key
                    ===
                    'Enter'
                ) {

                    event.preventDefault();

                    sendMessage();
                }
            }
        );



        /*
        |--------------------------------------------------------------------------
        | POLLING 3 GIÂY
        |--------------------------------------------------------------------------
        */

        loadUsers();


        setInterval(
            function () {

                loadUsers();


                if (
                    selectedUserId
                ) {

                    loadMessages();
                }

            },
            3000
        );

    }
);

</script>



@stack('scripts')


</body>

</html>