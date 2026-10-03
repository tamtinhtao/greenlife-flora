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

    <title>Cửa Hàng Hoa & Cây Cảnh GreenLife Flora</title>

    <link
        href="/bootstrap/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        .hero-banner {
            background: #e8f5e9;
            padding: 35px 0;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .product-card {
            transition: transform 0.2s;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
            cursor: pointer;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-img {
            height: 200px;
            object-fit: cover;
        }

        .nav-pills .nav-link.active {
            background-color: #198754;
        }

        .nav-pills .nav-link {
            color: #198754;
            font-weight: 500;
        }

        .user-name {
            color: white;
            font-size: 14px;
            font-weight: 500;
        }

        /* ================================
           MENU ADMIN
        ================================ */

        .admin-menu-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;

            min-height: 38px;
            padding: 7px 13px;

            color: white;
            text-decoration: none;

            font-size: 14px;
            font-weight: 500;

            border: 1px solid rgba(255, 255, 255, 0.55);
            border-radius: 7px;

            background: rgba(255, 255, 255, 0.08);

            transition: all 0.2s ease;
        }

        .admin-menu-link:hover {
            color: #198754;
            background: white;
            border-color: white;
        }

        .admin-menu-link.active {
            color: #198754;
            background: white;
            border-color: white;
            font-weight: 600;
        }

        /* =====================================================
           CHAT USER
        ===================================================== */

        #user-chat-box {
            position: fixed;
            right: 25px;
            bottom: 25px;
            z-index: 9999;
        }

        #user-chat-toggle {
            width: 58px;
            height: 58px;
            border-radius: 50%;
            font-size: 22px;
        }

        #user-chat-popup {
            width: 350px;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        #user-chat-messages {
            height: 330px;
            overflow-y: auto;
            background: #f8f9fa;
            padding: 15px;
        }

        .chat-row {
            display: flex;
            margin-bottom: 10px;
        }

        .chat-row.me {
            justify-content: flex-end;
        }

        .chat-row.admin {
            justify-content: flex-start;
        }

        .chat-bubble {
            max-width: 78%;
            padding: 8px 12px;
            border-radius: 14px;
            word-break: break-word;
        }

        .chat-row.me .chat-bubble {
            background: #198754;
            color: white;
            border-bottom-right-radius: 4px;
        }

        .chat-row.admin .chat-bubble {
            background: white;
            color: #212529;
            border: 1px solid #dee2e6;
            border-bottom-left-radius: 4px;
        }

        .chat-time {
            display: block;
            font-size: 10px;
            margin-top: 3px;
            opacity: 0.7;
        }

        /* =====================================================
           CHAT ADMIN
        ===================================================== */

        #admin-chat-box {
            position: fixed;
            right: 25px;
            bottom: 25px;
            z-index: 9999;
        }

        #admin-chat-toggle {
            border-radius: 25px;
            padding: 10px 18px;
        }

        #admin-chat-popup {
            width: 620px;
            height: 470px;
            border: none;
            border-radius: 12px;
            overflow: hidden;
        }

        .admin-chat-body {
            display: flex;
            height: calc(100% - 48px);
        }

        #admin-chat-users {
            width: 200px;
            overflow-y: auto;
            border-right: 1px solid #dee2e6;
            background: #f8f9fa;
        }

        .admin-user-item {
            padding: 12px;
            cursor: pointer;
            border-bottom: 1px solid #e9ecef;
        }

        .admin-user-item:hover {
            background: #e9ecef;
        }

        .admin-user-item.active {
            background: #198754;
            color: white;
        }

        .admin-conversation {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        #admin-chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 12px;
            background: white;
        }

        .admin-msg-row {
            display: flex;
            margin-bottom: 10px;
        }

        .admin-msg-row.me {
            justify-content: flex-end;
        }

        .admin-msg-row.customer {
            justify-content: flex-start;
        }

        .admin-msg-bubble {
            max-width: 75%;
            padding: 8px 12px;
            border-radius: 14px;
        }

        .admin-msg-row.me .admin-msg-bubble {
            background: #198754;
            color: white;
        }

        .admin-msg-row.customer .admin-msg-bubble {
            background: #f1f3f5;
            color: #212529;
        }

        .unread-badge {
            background: #dc3545;
            color: white;
            border-radius: 20px;
            padding: 2px 7px;
            font-size: 11px;
        }
    </style>
</head>

<body>


{{-- ===================================================== --}}
{{-- NAVBAR --}}
{{-- ===================================================== --}}

@include('partials.store-navbar')



{{-- ===================================================== --}}
{{-- NỘI DUNG --}}
{{-- ===================================================== --}}

<div class="container">


    {{-- ================================================= --}}
    {{-- THÔNG BÁO THÀNH CÔNG --}}
    {{-- ================================================= --}}

    @if(session('success'))

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif



    {{-- ================================================= --}}
    {{-- THÔNG BÁO LỖI --}}
    {{-- ================================================= --}}

    @if(session('error'))

        <div
            class="alert alert-danger alert-dismissible fade show"
            role="alert"
        >

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    @endif



    {{-- ================================================= --}}
    {{-- HERO BANNER --}}
    {{-- ================================================= --}}

    <div class="hero-banner text-center">

        <h1 class="fw-bold text-success">
            Mang Thiên Nhiên Vào Không Gian Sống 🪴
        </h1>

        <p class="text-muted">
            Chuyên cung cấp Cây phong thủy,
            Cây để bàn,
            Sen đá & Hoa tươi chất lượng cao
        </p>



        {{-- ============================================= --}}
        {{-- TÌM KIẾM --}}
        {{-- ============================================= --}}

        <div class="row justify-content-center mt-3">

            <div class="col-md-6">

                <form
                    action="{{ route('home') }}"
                    method="GET"
                    class="d-flex gap-2"
                >

                    @if(request('category_id'))

                        <input
                            type="hidden"
                            name="category_id"
                            value="{{ request('category_id') }}"
                        >

                    @endif


                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Nhập tên cây cần tìm..."
                        value="{{ request('search') }}"
                    >


                    <button
                        type="submit"
                        class="btn btn-success px-4"
                    >
                        🔍 Tìm
                    </button>


                    @if(request('search') || request('category_id'))

                        <a
                            href="{{ route('home') }}"
                            class="btn btn-outline-secondary"
                        >
                            Xóa lọc
                        </a>

                    @endif

                </form>

            </div>

        </div>

    </div>



    {{-- ================================================= --}}
    {{-- DANH MỤC --}}
    {{-- ================================================= --}}

    <h4 class="text-success fw-bold mb-3">
        🌱 Danh Mục Sản Phẩm
    </h4>


    <ul class="nav nav-pills mb-4 border-bottom pb-3">


        {{-- TẤT CẢ --}}

        <li class="nav-item">

            <a
                class="nav-link {{ !request('category_id') ? 'active' : '' }}"
                href="{{ route(
                    'home',
                    array_merge(
                        request()->query(),
                        ['category_id' => null]
                    )
                ) }}"
            >
                Tất Cả Cây
            </a>

        </li>



        {{-- DANH MỤC --}}

        @foreach($categories as $cat)

            <li class="nav-item">

                <a
                    class="nav-link {{ request('category_id') == $cat->id ? 'active' : '' }}"
                    href="{{ route(
                        'home',
                        array_merge(
                            request()->query(),
                            ['category_id' => $cat->id]
                        )
                    ) }}"
                >
                    {{ $cat->name }}
                </a>

            </li>

        @endforeach

    </ul>



    {{-- ================================================= --}}
    {{-- DANH SÁCH SẢN PHẨM --}}
    {{-- ================================================= --}}

    <div class="row">

        @forelse($products as $item)

            <div class="col-md-3 mb-4">

                <div
                    class="card h-100 product-card"
                    role="link"
                    tabindex="0"
                    data-detail-url="{{ route('products.show_detail', $item->slug) }}"
                >


                    {{-- ============================================= --}}
                    {{-- ẢNH --}}
                    {{-- ============================================= --}}

                    @if($item->image)

                        <img
                            src="{{ asset($item->image) }}"
                            class="card-img-top product-img"
                            alt="{{ $item->name }}"
                        >

                    @else

                        <img
                            src="https://via.placeholder.com/300x200?text=Cay+Canh"
                            class="card-img-top product-img"
                            alt="Cây cảnh"
                        >

                    @endif



                    <div class="card-body d-flex flex-column">


                        {{-- ========================================= --}}
                        {{-- DANH MỤC --}}
                        {{-- ========================================= --}}

                        <span
                            class="badge bg-success-subtle text-success mb-2 w-auto align-self-start"
                        >
                            {{ $item->category->name ?? 'Cây cảnh' }}
                        </span>



                        {{-- ========================================= --}}
                        {{-- TÊN SẢN PHẨM --}}
                        {{-- ========================================= --}}

                        <h5 class="card-title fw-bold text-dark">
                            {{ $item->name }}
                        </h5>



                        {{-- ========================================= --}}
                        {{-- GIÁ --}}
                        {{-- ========================================= --}}

                        <p class="card-text text-danger fw-bold fs-5 mb-3">
                            {{ number_format($item->price) }} đ
                        </p>



                        {{-- ========================================= --}}
                        {{-- NÚT CHỨC NĂNG --}}
                        {{-- ========================================= --}}

                        <div
                            class="d-flex gap-2 mt-auto"
                            onclick="event.stopPropagation();"
                        >


                            {{-- ===================================== --}}
                            {{-- ĐÃ ĐĂNG NHẬP --}}
                            {{-- ===================================== --}}

                            @auth


                                {{-- USER --}}

                                @if(auth()->user()->role === 'user')

                                @php

    $isWishlisted =
        auth()
            ->user()
            ->wishlistProducts
            ->contains(
                'id',
                $item->id
            );

@endphp


<form
    action="{{
        $isWishlisted
            ? route(
                'wishlist.destroy',
                $item->id
            )
            : route(
                'wishlist.store',
                $item->id
            )
    }}"
    method="POST"
>

    @csrf


    @if($isWishlisted)

        @method('DELETE')

    @endif


    <button
        type="submit"
        class="
            btn
            {{ $isWishlisted
                ? 'btn-danger'
                : 'btn-outline-danger'
            }}
            btn-sm
        "
        title="{{
            $isWishlisted
                ? 'Bỏ yêu thích'
                : 'Thêm vào yêu thích'
        }}"
    >

        {{ $isWishlisted ? '♥' : '♡' }}

    </button>

</form>
                                    {{-- THÊM VÀO GIỎ --}}

                                    <button
                                        type="button"
                                        class="btn btn-outline-success btn-sm flex-fill add-to-cart-btn"
                                        data-url="{{ route('cart.add', $item->id) }}"
                                    >
                                        🛒 Thêm vào giỏ
                                    </button>


                                    {{-- MUA NGAY --}}

                                    <a
                                        href="{{ route('cart.buyNow', $item->id) }}"
                                        class="btn btn-success btn-sm flex-fill"
                                    >
                                        ⚡ Mua
                                    </a>

                                @endif



                                {{-- ADMIN --}}

                                @if(auth()->user()->role === 'admin')

                                    <a
                                        href="{{ route(
                                            'admin.products.edit',
                                            $item->id
                                        ) }}"
                                        class="btn btn-warning btn-sm w-100"
                                    >
                                        ✏️ Sửa
                                    </a>

                                @endif


                            @else


                                {{-- ================================= --}}
                                {{-- CHƯA ĐĂNG NHẬP --}}
                                {{-- ================================= --}}

                                <a
                                    href="{{ route('login') }}"
                                    class="btn btn-success btn-sm w-100"
                                >
                                    🛒 Mua
                                </a>

                            @endauth

                        </div>

                    </div>

                </div>

            </div>


        @empty


            {{-- ================================================= --}}
            {{-- KHÔNG CÓ SẢN PHẨM --}}
            {{-- ================================================= --}}

            <div class="col-12 text-center py-5">

                <p class="text-muted fs-5">
                    Tạm thời chưa tìm thấy cây cảnh nào phù hợp.
                </p>

                <a
                    href="{{ route('home') }}"
                    class="btn btn-outline-success"
                >
                    Xem tất cả cây cảnh
                </a>

            </div>

        @endforelse

    </div>

</div>



{{-- ===================================================== --}}
{{-- CHAT HỖ TRỢ USER --}}
{{-- ===================================================== --}}

@auth

    @if(auth()->user()->role === 'user')

        <div id="user-chat-box">


            {{-- NÚT CHAT --}}

            <button
                type="button"
                id="user-chat-toggle"
                class="btn btn-success shadow"
            >
                💬
            </button>



            {{-- POPUP --}}

            <div
                id="user-chat-popup"
                class="card shadow-lg"
                style="display: none;"
            >


                {{-- HEADER --}}

                <div
                    class="card-header bg-success text-white
                           d-flex justify-content-between align-items-center"
                >

                    <div>

                        <strong>
                            💬 Hỗ trợ khách hàng
                        </strong>

                        <div style="font-size: 11px;">
                            GreenLife Flora
                        </div>

                    </div>


                    <button
                        type="button"
                        id="user-chat-close"
                        class="btn btn-sm btn-light"
                    >
                        ✕
                    </button>

                </div>



                {{-- TIN NHẮN --}}

                <div id="user-chat-messages">

                    <div class="text-center text-muted py-4">

                        <small>
                            Đang tải tin nhắn...
                        </small>

                    </div>

                </div>



                {{-- NHẬP TIN --}}

                <div class="card-footer bg-white">

                    <div class="input-group">

                        <input
                            type="text"
                            id="user-chat-input"
                            class="form-control"
                            placeholder="Nhập tin nhắn..."
                            maxlength="2000"
                            autocomplete="off"
                        >


                        <button
                            type="button"
                            id="user-chat-send"
                            class="btn btn-success"
                        >
                            Gửi
                        </button>

                    </div>

                </div>

            </div>

        </div>

    @endif

@endauth



{{-- ===================================================== --}}
{{-- CHAT HỖ TRỢ ADMIN --}}
{{-- ===================================================== --}}

@auth

    @if(auth()->user()->role === 'admin')

        <div id="admin-chat-box">


            <button
                type="button"
                id="admin-chat-toggle"
                class="btn btn-dark shadow"
            >
                💬 Chat khách hàng
            </button>



            <div
                id="admin-chat-popup"
                class="card shadow-lg"
                style="display: none;"
            >


                {{-- HEADER --}}

                <div
                    class="card-header bg-dark text-white
                           d-flex justify-content-between align-items-center"
                >

                    <div>

                        <strong>
                            💬 Hỗ trợ khách hàng
                        </strong>

                    </div>


                    <button
                        type="button"
                        id="admin-chat-close"
                        class="btn btn-sm btn-light"
                    >
                        ✕
                    </button>

                </div>



                {{-- NỘI DUNG --}}

                <div class="admin-chat-body">


                    {{-- DANH SÁCH USER --}}

                    <div id="admin-chat-users">

                        <div
                            class="text-center text-muted p-3"
                        >
                            Đang tải...
                        </div>

                    </div>



                    {{-- CHAT --}}

                    <div class="admin-conversation">


                        <div
                            id="admin-chat-title"
                            class="border-bottom p-2 fw-bold"
                        >
                            Chọn khách hàng
                        </div>



                        <div id="admin-chat-messages">

                            <div
                                class="text-center text-muted mt-5"
                            >
                                Chọn một khách hàng để xem tin nhắn
                            </div>

                        </div>



                        <div class="border-top p-2">

                            <div class="input-group">

                                <input
                                    type="text"
                                    id="admin-chat-input"
                                    class="form-control"
                                    placeholder="Nhập câu trả lời..."
                                    maxlength="2000"
                                    disabled
                                >


                                <button
                                    type="button"
                                    id="admin-chat-send"
                                    class="btn btn-success"
                                    disabled
                                >
                                    Gửi
                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @endif

@endauth



<script>

    document
        .querySelectorAll('.product-card')
        .forEach(function (card) {

            card.addEventListener(
                'click',
                function () {

                    window.location.href =
                        card.dataset.detailUrl;

                }
            );


            card.addEventListener(
                'keydown',
                function (event) {

                    if (event.key === 'Enter') {

                        window.location.href =
                            card.dataset.detailUrl;

                    }

                }
            );

        });

</script>



<script
    src="/bootstrap/js/bootstrap.bundle.min.js"
></script>



<script>

    const csrfToken =
        document.querySelector(
            'meta[name="csrf-token"]'
        ).content;


    document
        .querySelectorAll('.add-to-cart-btn')
        .forEach(function (button) {

            button.addEventListener(
                'click',
                async function (event) {


                    // Không cho click lan lên card sản phẩm

                    event.stopPropagation();


                    const url =
                        button.dataset.url;


                    const oldText =
                        button.innerHTML;



                    // Tạm khóa nút

                    button.disabled = true;

                    button.innerHTML =
                        'Đang thêm...';



                    try {


                        const response =
                            await fetch(
                                url,
                                {
                                    method: 'POST',

                                    headers: {

                                        'X-CSRF-TOKEN':
                                            csrfToken,

                                        'Accept':
                                            'application/json',

                                        'Content-Type':
                                            'application/json'

                                    }

                                }
                            );


                        const data =
                            await response.json();



                        if (!response.ok) {

                            throw new Error(
                                data.message
                                ||
                                'Có lỗi xảy ra'
                            );

                        }



                        /*
                        |------------------------------------------
                        | CẬP NHẬT BADGE GIỎ HÀNG
                        |------------------------------------------
                        */

                        const badge =
                            document.getElementById(
                                'cart-count-badge'
                            );


                        if (badge) {

                            badge.textContent =
                                data.cart_count;

                            badge.style.display =
                                'inline-block';

                        }



                        /*
                        |------------------------------------------
                        | THÔNG BÁO TRÊN NÚT
                        |------------------------------------------
                        */

                        button.innerHTML =
                            '✅ Đã thêm';


                        setTimeout(
                            function () {

                                button.innerHTML =
                                    oldText;

                                button.disabled =
                                    false;

                            },
                            800
                        );


                    } catch (error) {


                        console.error(error);


                        button.innerHTML =
                            '❌ Lỗi';


                        setTimeout(
                            function () {

                                button.innerHTML =
                                    oldText;

                                button.disabled =
                                    false;

                            },
                            1000
                        );

                    }

                }
            );

        });

</script>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        const toggleButton =
            document.getElementById(
                'user-chat-toggle'
            );



        /*
        |--------------------------------------------------------------------------
        | Nếu không phải User thì không có popup
        |--------------------------------------------------------------------------
        */

        if (!toggleButton) {

            return;

        }



        const popup =
            document.getElementById(
                'user-chat-popup'
            );


        const closeButton =
            document.getElementById(
                'user-chat-close'
            );


        const messagesBox =
            document.getElementById(
                'user-chat-messages'
            );


        const input =
            document.getElementById(
                'user-chat-input'
            );


        const sendButton =
            document.getElementById(
                'user-chat-send'
            );



        const currentUserId =
            {{ auth()->check() ? auth()->id() : 'null' }};



        /*
        |--------------------------------------------------------------------------
        | CHỐNG CHÈN HTML VÀO KHUNG CHAT
        |--------------------------------------------------------------------------
        */

        function escapeHtml(text)
        {

            const div =
                document.createElement('div');


            div.textContent =
                text ?? '';


            return div.innerHTML;

        }



        /*
        |--------------------------------------------------------------------------
        | FORMAT THỜI GIAN
        |--------------------------------------------------------------------------
        */

        function formatTime(dateString)
        {

            if (!dateString) {

                return '';

            }


            const date =
                new Date(dateString);


            return date.toLocaleString(
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



        /*
        |--------------------------------------------------------------------------
        | MỞ CHAT
        |--------------------------------------------------------------------------
        */

        toggleButton.addEventListener(
            'click',
            function () {

                popup.style.display =
                    'block';


                toggleButton.style.display =
                    'none';


                loadMessages();


                input.focus();

            }
        );



        /*
        |--------------------------------------------------------------------------
        | ĐÓNG CHAT
        |--------------------------------------------------------------------------
        */

        closeButton.addEventListener(
            'click',
            function () {

                popup.style.display =
                    'none';


                toggleButton.style.display =
                    'block';

            }
        );



        /*
        |--------------------------------------------------------------------------
        | LOAD LỊCH SỬ
        |--------------------------------------------------------------------------
        */

        async function loadMessages()
        {

            try {


                const response =
                    await fetch(
                        "{{ route('user.chat.messages') }}",
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



                /*
                |--------------------------------------------------------------------------
                | CHƯA CÓ TIN
                |--------------------------------------------------------------------------
                */

                if (messages.length === 0) {

                    messagesBox.innerHTML = `

                        <div
                            class="text-center text-muted py-5"
                        >

                            <div style="font-size: 32px;">
                                💬
                            </div>

                            <small>
                                Bạn chưa có cuộc trò chuyện.<br>
                                Hãy gửi tin nhắn cho Admin.
                            </small>

                        </div>

                    `;


                    return;

                }



                let html = '';



                messages.forEach(
                    function (message) {


                        const isMe =
                            Number(message.sender_id)
                            ===
                            Number(currentUserId);


                        const rowClass =
                            isMe
                                ? 'me'
                                : 'admin';


                        const sender =
                            isMe
                                ? 'Bạn'
                                : 'Admin';



                        html += `

                            <div
                                class="chat-row ${rowClass}"
                            >

                                <div class="chat-bubble">

                                    <div>
                                        ${escapeHtml(message.content)}
                                    </div>

                                    <span class="chat-time">

                                        ${sender}
                                        •
                                        ${formatTime(message.created_at)}

                                    </span>

                                </div>

                            </div>

                        `;

                    }
                );



                messagesBox.innerHTML =
                    html;



                /*
                |--------------------------------------------------------------------------
                | CUỘN XUỐNG TIN CUỐI
                |--------------------------------------------------------------------------
                */

                messagesBox.scrollTop =
                    messagesBox.scrollHeight;


            } catch (error) {


                console.error(
                    'Lỗi tải chat:',
                    error
                );


                messagesBox.innerHTML = `

                    <div
                        class="alert alert-danger m-2"
                    >
                        Không tải được tin nhắn.
                    </div>

                `;

            }

        }



        /*
        |--------------------------------------------------------------------------
        | GỬI TIN NHẮN
        |--------------------------------------------------------------------------
        */

        async function sendMessage()
        {

            const message =
                input.value.trim();


            if (message === '') {

                return;

            }


            input.disabled =
                true;


            sendButton.disabled =
                true;


            sendButton.textContent =
                'Đang gửi...';



            try {


                const response =
                    await fetch(
                        "{{ route('user.chat.send') }}",
                        {

                            method:
                                'POST',

                            headers: {

                                'X-CSRF-TOKEN':

                                    document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        .content,

                                'Accept':
                                    'application/json',

                                'Content-Type':
                                    'application/json'

                            },


                            body:
                                JSON.stringify({

                                    message:
                                        message

                                })

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
                        'Không thể gửi tin nhắn.'

                    );

                }



                input.value =
                    '';


                await loadMessages();


                input.focus();



            } catch (error) {


                console.error(
                    'Lỗi gửi chat:',
                    error
                );


                alert(
                    error.message
                );


            } finally {


                input.disabled =
                    false;


                sendButton.disabled =
                    false;


                sendButton.textContent =
                    'Gửi';

            }

        }



        /*
        |--------------------------------------------------------------------------
        | CLICK GỬI
        |--------------------------------------------------------------------------
        */

        sendButton.addEventListener(
            'click',
            sendMessage
        );



        /*
        |--------------------------------------------------------------------------
        | ENTER ĐỂ GỬI
        |--------------------------------------------------------------------------
        */

        input.addEventListener(
            'keydown',
            function (event) {

                if (
                    event.key === 'Enter'
                ) {

                    event.preventDefault();

                    sendMessage();

                }

            }
        );



        /*
        |--------------------------------------------------------------------------
        | TỰ REFRESH 3 GIÂY
        |--------------------------------------------------------------------------
        */

        setInterval(
            function () {

                if (
                    popup.style.display
                    === 'block'
                ) {

                    loadMessages();

                }

            },
            3000
        );

    }

);

</script>



<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        const toggle =
            document.getElementById(
                'admin-chat-toggle'
            );


        if (!toggle) {

            return;

        }



        const popup =
            document.getElementById(
                'admin-chat-popup'
            );


        const close =
            document.getElementById(
                'admin-chat-close'
            );


        const userList =
            document.getElementById(
                'admin-chat-users'
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


        const title =
            document.getElementById(
                'admin-chat-title'
            );



        const adminId =
            {{ auth()->check() ? auth()->id() : 'null' }};



        let currentUserId =
            null;



        /*
        |--------------------------------------------------------------------------
        | ESCAPE HTML
        |--------------------------------------------------------------------------
        */

        function escapeHtml(text)
        {

            const div =
                document.createElement('div');


            div.textContent =
                text ?? '';


            return div.innerHTML;

        }



        /*
        |--------------------------------------------------------------------------
        | MỞ POPUP
        |--------------------------------------------------------------------------
        */

        toggle.addEventListener(
            'click',
            function () {

                popup.style.display =
                    'block';


                toggle.style.display =
                    'none';


                loadUsers();

            }
        );



        /*
        |--------------------------------------------------------------------------
        | ĐÓNG
        |--------------------------------------------------------------------------
        */

        close.addEventListener(
            'click',
            function () {

                popup.style.display =
                    'none';


                toggle.style.display =
                    'block';

            }
        );



        /*
        |--------------------------------------------------------------------------
        | LOAD USER
        |--------------------------------------------------------------------------
        */

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


                const users =
                    await response.json();



                if (users.length === 0) {

                    userList.innerHTML = `

                        <div
                            class="text-center text-muted p-3"
                        >
                            Chưa có hội thoại
                        </div>

                    `;


                    return;

                }



                let html = '';



                users.forEach(
                    function (user) {


                        const active =
                            Number(currentUserId)
                            ===
                            Number(user.id)
                                ? 'active'
                                : '';


                        const unread =
                            Number(user.unread_count) > 0
                                ?
                                `<span class="unread-badge">
                                    ${user.unread_count}
                                </span>`
                                :
                                '';



                        html += `

                            <div
                                class="admin-user-item ${active}"
                                data-user-id="${user.id}"
                                data-user-name="${escapeHtml(user.name)}"
                            >

                                <div
                                    class="d-flex justify-content-between align-items-center"
                                >

                                    <span>
                                        👤 ${escapeHtml(user.name)}
                                    </span>

                                    ${unread}

                                </div>


                                <small>
                                    ${escapeHtml(user.email ?? '')}
                                </small>

                            </div>

                        `;

                    }
                );



                userList.innerHTML =
                    html;



                /*
                |--------------------------------------------------------------------------
                | EVENT CHỌN USER
                |--------------------------------------------------------------------------
                */

                document
                    .querySelectorAll(
                        '.admin-user-item'
                    )
                    .forEach(
                        function (element) {

                            element.addEventListener(
                                'click',
                                function () {


                                    currentUserId =
                                        this.dataset.userId;



                                    title.textContent =
                                        '💬 '
                                        +
                                        this.dataset.userName;



                                    input.disabled =
                                        false;


                                    sendButton.disabled =
                                        false;



                                    loadMessages();


                                    loadUsers();

                                }
                            );

                        }
                    );


            } catch (error) {


                console.error(
                    'Lỗi tải user chat:',
                    error
                );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | LOAD TIN NHẮN
        |--------------------------------------------------------------------------
        */

        async function loadMessages()
        {

            if (!currentUserId) {

                return;

            }



            try {


                const url =
                    "{{ route(
                        'admin.chat.messages',
                        ['userId' => '__USER__']
                    ) }}"
                    .replace(
                        '__USER__',
                        currentUserId
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


                const messages =
                    await response.json();



                let html = '';



                messages.forEach(
                    function (message) {


                        const isMe =
                            Number(message.sender_id)
                            ===
                            Number(adminId);



                        html += `

                            <div
                                class="admin-msg-row
                                    ${isMe ? 'me' : 'customer'}"
                            >

                                <div class="admin-msg-bubble">

                                    ${escapeHtml(message.content)}

                                </div>

                            </div>

                        `;

                    }
                );



                messagesBox.innerHTML =
                    html;


                messagesBox.scrollTop =
                    messagesBox.scrollHeight;



            } catch (error) {


                console.error(
                    'Lỗi tải tin nhắn Admin:',
                    error
                );

            }

        }



        /*
        |--------------------------------------------------------------------------
        | GỬI
        |--------------------------------------------------------------------------
        */

        async function sendMessage()
        {

            const message =
                input.value.trim();



            if (
                message === ''
                ||
                !currentUserId
            ) {

                return;

            }



            input.disabled =
                true;


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

                                'X-CSRF-TOKEN':

                                    document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        .content,

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json'

                            },


                            body:
                                JSON.stringify({

                                    user_id:
                                        currentUserId,

                                    message:
                                        message

                                })

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


                await loadMessages();



            } catch (error) {


                alert(
                    error.message
                );


            } finally {


                input.disabled =
                    false;


                sendButton.disabled =
                    false;


                input.focus();

            }

        }



        sendButton.addEventListener(
            'click',
            sendMessage
        );



        input.addEventListener(
            'keydown',
            function (event) {

                if (event.key === 'Enter') {

                    event.preventDefault();

                    sendMessage();

                }

            }
        );



        /*
        |--------------------------------------------------------------------------
        | AUTO REFRESH 3 GIÂY
        |--------------------------------------------------------------------------
        */

        setInterval(
            function () {

                if (
                    popup.style.display
                    === 'block'
                ) {

                    loadUsers();


                    if (currentUserId) {

                        loadMessages();

                    }

                }

            },
            3000
        );

    }

);

</script>


</body>

</html>