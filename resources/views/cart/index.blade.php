<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>Giỏ Hàng - GreenLife Flora</title>

    <link
        href="{{ asset('bootstrap/css/bootstrap.min.css') }}"
        rel="stylesheet"
    >

</head>


<body class="container mt-4">

@include('partials.store-navbar')
    {{-- ========================================================= --}}
    {{-- TIÊU ĐỀ --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2>
            🛒 Giỏ Hàng Của Bạn
        </h2>

        <a
            href="{{ route('home') }}"
            class="btn btn-outline-secondary"
        >
            ⬅️ Tiếp tục chọn cây
        </a>

    </div>



    {{-- ========================================================= --}}
    {{-- THÔNG BÁO --}}
    {{-- ========================================================= --}}

    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            @foreach($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

        </div>

    @endif



    {{-- ========================================================= --}}
    {{-- GIỎ HÀNG CÓ SẢN PHẨM --}}
    {{-- ========================================================= --}}

    @if(count($cart) > 0)


        <div id="cart-content">


            {{-- ================================================= --}}
            {{-- FORM CHỌN SẢN PHẨM THANH TOÁN --}}
            {{-- ================================================= --}}

            <form
                action="{{ route('cart.checkoutSelected') }}"
                method="POST"
                id="checkout-selected-form"
            >

                @csrf


                <div class="table-responsive">

                    <table class="table table-bordered align-middle">

                        <thead class="table-success">

                            <tr>

                                {{-- CHỌN --}}
                                <th
                                    class="text-center"
                                    style="width: 70px;"
                                >

                                    <input
                                        type="checkbox"
                                        id="select-all"
                                        class="form-check-input"
                                        title="Chọn tất cả"
                                    >

                                    <div
                                        class="small mt-1"
                                    >
                                        Chọn
                                    </div>

                                </th>


                                <th>Ảnh</th>

                                <th>Tên Cây</th>

                                <th>Giá</th>

                                <th>Số lượng</th>

                                <th>Thành tiền</th>

                                <th>Thao tác</th>

                            </tr>

                        </thead>


                        <tbody>

                            @php

                                $total = 0;

                            @endphp


                            @foreach($cart as $id => $item)

                                @php

                                    $subtotal =
                                        $item['price']
                                        * $item['quantity'];

                                    $total += $subtotal;

                                @endphp


                                <tr id="cart-row-{{ $id }}">


                                    {{-- ================================= --}}
                                    {{-- CHECKBOX CHỌN MUA --}}
                                    {{-- ================================= --}}

                                    <td class="text-center">

                                        <input
                                            type="checkbox"
                                            name="selected_products[]"
                                            value="{{ $id }}"
                                            class="form-check-input product-checkbox"
                                            data-id="{{ $id }}"
                                            data-subtotal="{{ $subtotal }}"
                                        >

                                    </td>



                                    {{-- ================================= --}}
                                    {{-- ẢNH --}}
                                    {{-- ================================= --}}

                                    <td>

                                        <img
                                            src="{{ asset($item['image']) }}"
                                            width="60"
                                            class="rounded"
                                        >

                                    </td>



                                    {{-- ================================= --}}
                                    {{-- TÊN --}}
                                    {{-- ================================= --}}

                                    <td>

                                        <strong>
                                            {{ $item['name'] }}
                                        </strong>

                                    </td>



                                    {{-- ================================= --}}
                                    {{-- GIÁ --}}
                                    {{-- ================================= --}}

                                    <td>

                                        {{ number_format($item['price']) }} đ

                                    </td>



                                    {{-- ================================= --}}
                                    {{-- SỐ LƯỢNG --}}
                                    {{-- ================================= --}}

                                    <td>

                                        <div
                                            class="d-flex align-items-center gap-2"
                                        >


                                            {{-- GIẢM --}}
                                            <button
                                                type="button"
                                                class="btn btn-outline-secondary btn-sm quantity-btn"
                                                data-id="{{ $id }}"
                                                data-action="decrease"
                                                data-url="{{ route('cart.updateQuantity') }}"
                                                style="width: 32px;"
                                            >
                                                −
                                            </button>



                                            {{-- SỐ LƯỢNG HIỆN TẠI --}}
                                            <span
                                                id="quantity-{{ $id }}"
                                                class="fw-bold text-center"
                                                style="min-width: 25px;"
                                            >
                                                {{ $item['quantity'] }}
                                            </span>



                                            {{-- TĂNG --}}
                                            <button
                                                type="button"
                                                class="btn btn-outline-success btn-sm quantity-btn"
                                                data-id="{{ $id }}"
                                                data-action="increase"
                                                data-url="{{ route('cart.updateQuantity') }}"
                                                style="width: 32px;"
                                            >
                                                +
                                            </button>


                                        </div>

                                    </td>



                                    {{-- ================================= --}}
                                    {{-- THÀNH TIỀN --}}
                                    {{-- ================================= --}}

                                    <td
                                        id="subtotal-{{ $id }}"
                                    >

                                        {{ number_format($subtotal) }} đ

                                    </td>



                                    {{-- ================================= --}}
                                    {{-- XÓA --}}
                                    {{-- ================================= --}}

                                    <td>

                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm remove-cart-btn"
                                            data-id="{{ $id }}"
                                            data-url="{{ route('cart.remove') }}"
                                        >
                                            Xóa
                                        </button>

                                    </td>


                                </tr>


                            @endforeach

                        </tbody>

                    </table>

                </div>



                {{-- ================================================= --}}
                {{-- TỔNG TIỀN --}}
                {{-- ================================================= --}}

                <div
                    class="mt-3 p-3 bg-light rounded"
                    id="cart-summary"
                >


                    <div
                        class="d-flex justify-content-between align-items-center flex-wrap gap-3"
                    >


                        <div>


                            {{-- TOÀN BỘ GIỎ --}}
                            <div class="mb-2">

                                Tổng toàn bộ giỏ:

                                <strong
                                    id="cart-total"
                                >
                                    {{ number_format($total) }} VNĐ
                                </strong>

                            </div>



                            {{-- SẢN PHẨM ĐÃ CHỌN --}}
                            <h4 class="mb-0">

                                Tổng sản phẩm đã chọn:

                                <span
                                    id="selected-total"
                                    class="text-danger fw-bold"
                                >
                                    0 VNĐ
                                </span>

                            </h4>


                            <small
                                id="selected-count"
                                class="text-muted"
                            >
                                Chưa chọn sản phẩm nào
                            </small>

                        </div>



                        {{-- ========================================= --}}
                        {{-- NÚT THANH TOÁN --}}
                        {{-- ========================================= --}}

                        <button
                            type="submit"
                            id="checkout-selected-btn"
                            class="btn btn-success btn-lg"
                            disabled
                        >
                            Thanh Toán Sản Phẩm Đã Chọn ➔
                        </button>


                    </div>

                </div>


            </form>


        </div>



        {{-- ========================================================= --}}
        {{-- HIỆN KHI AJAX XÓA HẾT SẢN PHẨM --}}
        {{-- ========================================================= --}}

        <div
            id="empty-cart"
            class="alert alert-info text-center py-4"
            style="display: none;"
        >

            Giỏ hàng của bạn chưa có chậu cây nào.

            <a href="{{ route('home') }}">
                Xem danh sách cây ngay!
            </a>

        </div>


    @else


        {{-- ========================================================= --}}
        {{-- GIỎ HÀNG TRỐNG --}}
        {{-- ========================================================= --}}

        <div class="alert alert-info text-center py-4">

            Giỏ hàng của bạn chưa có chậu cây nào.

            <a href="{{ route('home') }}">
                Xem danh sách cây ngay!
            </a>

        </div>


    @endif



    {{-- ========================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================================= --}}

    <script>

        const csrfToken =
            document
                .querySelector(
                    'meta[name="csrf-token"]'
                )
                .content;



        /*
        |--------------------------------------------------------------------------
        | FORMAT TIỀN
        |--------------------------------------------------------------------------
        */

        function formatMoney(value)
        {
            return new Intl.NumberFormat(
                'vi-VN'
            ).format(value);
        }



        /*
        |--------------------------------------------------------------------------
        | LẤY CÁC ELEMENT CHỌN SẢN PHẨM
        |--------------------------------------------------------------------------
        */

        const selectAll =
            document.getElementById(
                'select-all'
            );


        const checkoutButton =
            document.getElementById(
                'checkout-selected-btn'
            );


        const selectedTotalElement =
            document.getElementById(
                'selected-total'
            );


        const selectedCountElement =
            document.getElementById(
                'selected-count'
            );



        /*
        |--------------------------------------------------------------------------
        | CẬP NHẬT TỔNG TIỀN SẢN PHẨM ĐÃ CHỌN
        |--------------------------------------------------------------------------
        */

        function updateSelectedSummary()
        {
            let selectedTotal = 0;

            let selectedCount = 0;


            const checkboxes =
                document.querySelectorAll(
                    '.product-checkbox'
                );


            checkboxes.forEach(
                function (checkbox) {

                    if (checkbox.checked) {

                        selectedTotal +=
                            Number(
                                checkbox.dataset.subtotal
                                || 0
                            );

                        selectedCount++;
                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | CẬP NHẬT TIỀN
            |--------------------------------------------------------------------------
            */

            if (selectedTotalElement) {

                selectedTotalElement.textContent =
                    formatMoney(
                        selectedTotal
                    )
                    + ' VNĐ';
            }


            /*
            |--------------------------------------------------------------------------
            | CẬP NHẬT SỐ SẢN PHẨM ĐÃ CHỌN
            |--------------------------------------------------------------------------
            */

            if (selectedCountElement) {

                if (selectedCount === 0) {

                    selectedCountElement.textContent =
                        'Chưa chọn sản phẩm nào';

                } else {

                    selectedCountElement.textContent =
                        'Đã chọn '
                        + selectedCount
                        + ' sản phẩm';
                }
            }


            /*
            |--------------------------------------------------------------------------
            | BẬT / TẮT NÚT THANH TOÁN
            |--------------------------------------------------------------------------
            */

            if (checkoutButton) {

                checkoutButton.disabled =
                    selectedCount === 0;
            }


            /*
            |--------------------------------------------------------------------------
            | CẬP NHẬT CHECKBOX CHỌN TẤT CẢ
            |--------------------------------------------------------------------------
            */

            if (selectAll) {

                const remainingCheckboxes =
                    document.querySelectorAll(
                        '.product-checkbox'
                    );


                const checkedCheckboxes =
                    document.querySelectorAll(
                        '.product-checkbox:checked'
                    );


                selectAll.checked =
                    remainingCheckboxes.length > 0
                    &&
                    remainingCheckboxes.length
                    === checkedCheckboxes.length;


                selectAll.indeterminate =
                    checkedCheckboxes.length > 0
                    &&
                    checkedCheckboxes.length
                    < remainingCheckboxes.length;
            }
        }



        /*
        |--------------------------------------------------------------------------
        | CHỌN TỪNG SẢN PHẨM
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.product-checkbox'
            )
            .forEach(
                function (checkbox) {

                    checkbox.addEventListener(
                        'change',
                        function () {

                            updateSelectedSummary();

                        }
                    );

                }
            );



        /*
        |--------------------------------------------------------------------------
        | CHỌN TẤT CẢ
        |--------------------------------------------------------------------------
        */

        if (selectAll) {

            selectAll.addEventListener(
                'change',
                function () {

                    const checked =
                        this.checked;


                    document
                        .querySelectorAll(
                            '.product-checkbox'
                        )
                        .forEach(
                            function (checkbox) {

                                checkbox.checked =
                                    checked;

                            }
                        );


                    updateSelectedSummary();

                }
            );
        }



        /*
        |--------------------------------------------------------------------------
        | TĂNG / GIẢM SỐ LƯỢNG AJAX
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.quantity-btn'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        async function () {

                            const id =
                                button.dataset.id;

                            const action =
                                button.dataset.action;

                            const url =
                                button.dataset.url;


                            button.disabled =
                                true;


                            try {

                                const response =
                                    await fetch(
                                        url,
                                        {

                                            method:
                                                'POST',

                                            headers: {

                                                'X-CSRF-TOKEN':
                                                    csrfToken,

                                                'Accept':
                                                    'application/json',

                                                'Content-Type':
                                                    'application/json'
                                            },

                                            body:
                                                JSON.stringify(
                                                    {

                                                        id:
                                                            id,

                                                        action:
                                                            action
                                                    }
                                                )
                                        }
                                    );


                                const data =
                                    await response.json();


                                if (!response.ok) {

                                    throw new Error(
                                        'Không thể cập nhật số lượng'
                                    );
                                }



                                /*
                                |--------------------------------------------------------------------------
                                | CẬP NHẬT SỐ LƯỢNG
                                |--------------------------------------------------------------------------
                                */

                                document
                                    .getElementById(
                                        'quantity-' + id
                                    )
                                    .textContent =
                                        data.quantity;



                                /*
                                |--------------------------------------------------------------------------
                                | CẬP NHẬT THÀNH TIỀN
                                |--------------------------------------------------------------------------
                                */

                                document
                                    .getElementById(
                                        'subtotal-' + id
                                    )
                                    .textContent =
                                        formatMoney(
                                            data.subtotal
                                        )
                                        + ' đ';



                                /*
                                |--------------------------------------------------------------------------
                                | CẬP NHẬT TỔNG TOÀN BỘ GIỎ
                                |--------------------------------------------------------------------------
                                */

                                const cartTotal =
                                    document.getElementById(
                                        'cart-total'
                                    );


                                if (cartTotal) {

                                    cartTotal.textContent =
                                        formatMoney(
                                            data.total
                                        )
                                        + ' VNĐ';
                                }



                                /*
                                |--------------------------------------------------------------------------
                                | CẬP NHẬT SUBTOTAL TRONG CHECKBOX
                                |
                                | Nếu sản phẩm đang được tích,
                                | tổng sản phẩm đã chọn cũng thay đổi ngay.
                                |--------------------------------------------------------------------------
                                */

                                const checkbox =
                                    document.querySelector(
                                        '.product-checkbox[data-id="'
                                        + id
                                        + '"]'
                                    );


                                if (checkbox) {

                                    checkbox.dataset.subtotal =
                                        data.subtotal;
                                }


                                updateSelectedSummary();


                            } catch (error) {

                                console.error(
                                    error
                                );


                                alert(
                                    'Có lỗi khi cập nhật số lượng.'
                                );


                            } finally {

                                button.disabled =
                                    false;

                            }

                        }
                    );

                }
            );



        /*
        |--------------------------------------------------------------------------
        | XÓA SẢN PHẨM AJAX
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.remove-cart-btn'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        async function () {

                            const id =
                                button.dataset.id;

                            const url =
                                button.dataset.url;


                            /*
                            |--------------------------------------------------------------------------
                            | HỎI TRƯỚC KHI XÓA
                            |--------------------------------------------------------------------------
                            */

                            if (
                                !confirm(
                                    'Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng không?'
                                )
                            ) {

                                return;
                            }


                            button.disabled =
                                true;


                            try {

                                const response =
                                    await fetch(
                                        url,
                                        {

                                            method:
                                                'POST',

                                            headers: {

                                                'X-CSRF-TOKEN':
                                                    csrfToken,

                                                'Accept':
                                                    'application/json',

                                                'Content-Type':
                                                    'application/json'
                                            },

                                            body:
                                                JSON.stringify(
                                                    {
                                                        id: id
                                                    }
                                                )
                                        }
                                    );


                                const data =
                                    await response.json();


                                if (!response.ok) {

                                    throw new Error(
                                        'Không thể xóa sản phẩm'
                                    );
                                }



                                /*
                                |--------------------------------------------------------------------------
                                | XÓA DÒNG SẢN PHẨM
                                |--------------------------------------------------------------------------
                                */

                                const row =
                                    document.getElementById(
                                        'cart-row-' + id
                                    );


                                if (row) {

                                    row.remove();
                                }



                                /*
                                |--------------------------------------------------------------------------
                                | CẬP NHẬT TỔNG TOÀN BỘ GIỎ
                                |--------------------------------------------------------------------------
                                */

                                const cartTotal =
                                    document.getElementById(
                                        'cart-total'
                                    );


                                if (cartTotal) {

                                    cartTotal.textContent =
                                        formatMoney(
                                            data.total
                                        )
                                        + ' VNĐ';
                                }



                                /*
                                |--------------------------------------------------------------------------
                                | CẬP NHẬT TỔNG ĐÃ CHỌN
                                |--------------------------------------------------------------------------
                                */

                                updateSelectedSummary();



                                /*
                                |--------------------------------------------------------------------------
                                | NẾU GIỎ HẾT SẢN PHẨM
                                |--------------------------------------------------------------------------
                                */

                                const remainingRows =
                                    document.querySelectorAll(
                                        'tbody tr'
                                    );


                                if (
                                    remainingRows.length
                                    === 0
                                ) {

                                    const cartContent =
                                        document.getElementById(
                                            'cart-content'
                                        );


                                    const emptyCart =
                                        document.getElementById(
                                            'empty-cart'
                                        );


                                    if (cartContent) {

                                        cartContent.style.display =
                                            'none';
                                    }


                                    if (emptyCart) {

                                        emptyCart.style.display =
                                            'block';
                                    }
                                }


                            } catch (error) {

                                console.error(
                                    error
                                );


                                alert(
                                    'Có lỗi khi xóa sản phẩm.'
                                );


                                button.disabled =
                                    false;
                            }

                        }
                    );

                }
            );



        /*
        |--------------------------------------------------------------------------
        | CHẶN THANH TOÁN NẾU CHƯA CHỌN SẢN PHẨM
        |--------------------------------------------------------------------------
        */

        const checkoutForm =
            document.getElementById(
                'checkout-selected-form'
            );


        if (checkoutForm) {

            checkoutForm.addEventListener(
                'submit',
                function (event) {

                    const selected =
                        document.querySelectorAll(
                            '.product-checkbox:checked'
                        );


                    if (
                        selected.length === 0
                    ) {

                        event.preventDefault();


                        alert(
                            'Vui lòng chọn ít nhất một sản phẩm để thanh toán.'
                        );
                    }

                }
            );
        }



        /*
        |--------------------------------------------------------------------------
        | KHỞI TẠO
        |--------------------------------------------------------------------------
        */

        updateSelectedSummary();

    </script>


</body>

</html>