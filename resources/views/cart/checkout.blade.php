<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">

    <title>Đặt Hàng & Thanh Toán</title>

    <link
    href="{{ asset('bootstrap/css/bootstrap.min.css') }}"
    rel="stylesheet"
>
</head>


<body class="container mt-4 mb-5">

    <h2>🚚 Thông Tin Đặt Hàng & Giao Cây</h2>

@if($errors->any())
    <div class="alert alert-danger">
        <strong>Vui lòng kiểm tra lại thông tin:</strong>

        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    {{-- NÚT QUAY LẠI --}}
    @if(($mode ?? null) === 'buy-now')

        <a
            href="{{ route('home') }}"
            class="btn btn-secondary mb-3"
        >
            ⬅️ Quay lại sản phẩm
        </a>

    @else

        <a
            href="{{ route('cart.index') }}"
            class="btn btn-secondary mb-3"
        >
            ⬅️ Quay lại giỏ hàng
        </a>

    @endif


    {{-- TÍNH TIỀN HÀNG BAN ĐẦU --}}
    @php
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
    @endphp


    {{-- ================================================= --}}
    {{-- FORM ĐẶT HÀNG - CHỈ CÓ 1 FORM --}}
    {{-- ================================================= --}}

    <form action="{{ route('user.payment.process') }}" method="POST" class="row">

        @csrf


        {{-- BUY NOW --}}
       @if(
    in_array(
        ($mode ?? null),
        [
            'buy-now',
            'selected'
        ]
    )
)

    <input
        type="hidden"
        name="mode"
        value="{{ $mode }}"
    >

@endif


        {{-- TỔNG TIỀN HIỆN TẠI --}}
        <input
            type="hidden"
            id="total_price_input"
            name="total_price"
            value="{{ $total }}"
        >


        {{-- PHÍ SHIP GHN --}}
        <input
            type="hidden"
            id="ghn_total_fee"
            name="ghn_total_fee"
            value="0"
        >



        {{-- ================================================= --}}
        {{-- CỘT TRÁI: THÔNG TIN NGƯỜI NHẬN --}}
        {{-- ================================================= --}}

        <div class="col-md-7">

            <div class="card p-4 shadow-sm mb-3">

                <h5 class="fw-bold text-success mb-3">
                    Thông Tin Người Nhận
                </h5>


                {{-- HỌ TÊN --}}
                <div class="mb-3">

                    <label class="form-label">
                        Họ và Tên:
                    </label>

                    <input
                        type="text"
                        name="customer_name"
                        class="form-control"
                        required
                        value="{{ old('customer_name') }}"
                        placeholder="Nguyễn Văn A"
                    >

                </div>


                {{-- SĐT --}}
                <div class="mb-3">

                    <label class="form-label">
                        Số điện thoại liên hệ:
                    </label>

                    <input
                        type="text"
                        name="customer_phone"
                        class="form-control @error('customer_phone') is-invalid @enderror"
                        required
                        value="{{ old('customer_phone') }}"
                        placeholder="0901234567"
                        pattern="(03|05|07|08|09)[0-9]{8}"
                        maxlength="10"
                        inputmode="numeric"
                        title="Số điện thoại phải gồm 10 số và bắt đầu bằng 03, 05, 07, 08 hoặc 09."
                    >

                    @error('customer_phone')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror

                </div>



                {{-- ========================================= --}}
                {{-- GHN: TỈNH / THÀNH --}}
                {{-- ========================================= --}}

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Tỉnh / Thành phố:
                    </label>

                    <select
                        id="province_select"
                        class="form-select"
                        required
                    >

                        <option value="">
                            -- Đang tải Tỉnh/Thành --
                        </option>

                    </select>

                </div>



                {{-- ========================================= --}}
                {{-- GHN: QUẬN / HUYỆN --}}
                {{-- ========================================= --}}

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Quận / Huyện:
                    </label>

                    <select
                        id="district_select"
                        name="to_district_id"
                        class="form-select"
                        required
                        disabled
                    >

                        <option value="">
                            -- Chọn Quận/Huyện --
                        </option>

                    </select>

                </div>



                {{-- ========================================= --}}
                {{-- GHN: PHƯỜNG / XÃ --}}
                {{-- ========================================= --}}

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Phường / Xã:
                    </label>

                    <select
                        id="ward_select"
                        name="to_ward_code"
                        class="form-select"
                        required
                        disabled
                    >

                        <option value="">
                            -- Chọn Phường/Xã --
                        </option>

                    </select>

                </div>



                {{-- ĐỊA CHỈ CỤ THỂ --}}
                <div class="mb-3">

                    <label class="form-label">
                        Địa chỉ nhận cây cụ thể:
                    </label>

                    <input
                        type="text"
                        name="shipping_address"
                        class="form-control"
                        required
                        placeholder="Ví dụ: Số 10, đường ABC..."
                    >
                    

                </div>



                {{-- GHI CHÚ --}}
                <div class="mb-3">

                    <label class="form-label">
                        Ghi chú:
                    </label>

                    <textarea
                        name="note"
                        class="form-control"
                        rows="2"
                        placeholder="Ví dụ: Giao hàng giờ hành chính..."
                    ></textarea>

                </div>



                {{-- ========================================= --}}
                {{-- PHƯƠNG THỨC THANH TOÁN --}}
                {{-- ========================================= --}}

                <div class="mb-3">

                    <label class="form-label fw-bold">
                        Phương thức thanh toán:
                    </label>


                    {{-- COD --}}
                    <div class="form-check mb-2">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="payment_method"
                            id="cod"
                            value="cod"
                            {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}
                        >

                        <label
                            class="form-check-label"
                            for="cod"
                        >
                            💵 Thanh toán khi nhận hàng (COD)
                        </label>

                    </div>


                    {{-- MOMO --}}
                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="radio"
                            name="payment_method"
                            id="momo"
                            value="momo"
                            {{ old('payment_method') === 'momo' ? 'checked' : '' }}
                        >

                        <label
                            class="form-check-label"
                            for="momo"
                        >
                            🟣 Thanh toán bằng MoMo
                        </label>

                    </div>


                    @error('payment_method')

                        <div class="text-danger mt-1">
                            {{ $message }}
                        </div>

                    @enderror

                </div>



        {{-- ================================================= --}}
        {{-- CỘT PHẢI: TÓM TẮT ĐƠN HÀNG --}}
        {{-- ================================================= --}}

        <div class="col-md-5">

            <div class="card p-4 shadow-sm bg-light">

                <h5 class="fw-bold text-success mb-3">
                    Tóm Tắt Đơn Hàng
                </h5>


                <ul class="list-group mb-3">

                    @foreach($cart as $item)

                        @php
                            $subtotal =
                                $item['price']
                                * $item['quantity'];
                        @endphp


                        <li
                            class="list-group-item d-flex justify-content-between align-items-center"
                        >

                            <div>

                                <strong>
                                    {{ $item['name'] }}
                                </strong>

                                <br>

                                <small class="text-muted">

                                    SL:
                                    {{ $item['quantity'] }}

                                    x

                                    {{ number_format($item['price']) }}đ

                                </small>

                            </div>


                            <span class="fw-bold">

                                {{ number_format($subtotal) }}đ

                            </span>

                        </li>

                    @endforeach

                </ul>



                {{-- TIỀN HÀNG --}}
                <div class="d-flex justify-content-between mb-2">

                    <span>
                        Tiền hàng:
                    </span>

                    <strong>
                        {{ number_format($total) }} VNĐ
                    </strong>

                </div>



                {{-- PHÍ SHIP --}}
                <div class="d-flex justify-content-between mb-2">

                    <span>
                        Phí vận chuyển GHN:
                    </span>

                    <strong
                        id="shipping_fee_text"
                        class="text-primary"
                    >
                        0 VNĐ
                    </strong>

                </div>


                <hr>


                {{-- TỔNG CUỐI --}}
                <div
                    class="d-flex justify-content-between fs-5 fw-bold text-danger"
                >

                    <span>
                        Tổng Cần Thanh Toán:
                    </span>

                    <span id="final_total_text">

                        {{ number_format($total) }} VNĐ

                    </span>

                </div>



                <button
                    type="submit"
                    class="btn btn-success btn-lg mt-4 w-100"
                >
                    ✅ XÁC NHẬN ĐẶT HÀNG
                </button>

            </div>

        </div>

    </form>



{{-- ========================================================= --}}
{{-- GHN SHIPPING SCRIPT --}}
{{-- ========================================================= --}}

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        /*
        |--------------------------------------------------------------------------
        | LẤY CÁC SELECT
        |--------------------------------------------------------------------------
        */

        const provinceSelect =
            document.getElementById(
                'province_select'
            );

        const districtSelect =
            document.getElementById(
                'district_select'
            );

        const wardSelect =
            document.getElementById(
                'ward_select'
            );


        const shippingFeeText =
            document.getElementById(
                'shipping_fee_text'
            );

        const finalTotalText =
            document.getElementById(
                'final_total_text'
            );

        const totalPriceInput =
            document.getElementById(
                'total_price_input'
            );

        const shippingFeeInput =
            document.getElementById(
                'ghn_total_fee'
            );



        /*
        |--------------------------------------------------------------------------
        | ROUTE
        |--------------------------------------------------------------------------
        */

        const districtsUrl =
            "{{ route('locations.districts', ['provinceId' => '__PROVINCE__']) }}";


        const wardsUrl =
            "{{ route('locations.wards', ['districtId' => '__DISTRICT__']) }}";



        /*
        |--------------------------------------------------------------------------
        | TIỀN HÀNG BAN ĐẦU
        |--------------------------------------------------------------------------
        */

        const subtotal =
            parseInt(
                totalPriceInput
                    ? totalPriceInput.value
                    : 0
            ) || 0;



        /*
        |--------------------------------------------------------------------------
        | 1. LOAD TỈNH / THÀNH
        |--------------------------------------------------------------------------
        */

        fetch(
            "{{ route('locations.provinces') }}"
        )

        .then(
            response => response.json()
        )

        .then(
            response => {

                if (response.data) {

                    let options =
                        '<option value="">-- Chọn Tỉnh/Thành --</option>';


                    response.data.forEach(province => {

                        const provinceName = province.ProvinceName || '';

                        // Bỏ các dữ liệu test/rác của môi trường GHN staging
                        if (
                            province.ProvinceID == 2002 ||
                            provinceName.toLowerCase().includes('test')
                        ) {
                            return;
                        }

                        options +=
                            `<option value="${province.ProvinceID}">${provinceName}</option>`;
                    });


                    provinceSelect.innerHTML =
                        options;

                } else {

                    provinceSelect.innerHTML =
                        '<option value="">-- Không tải được tỉnh/thành --</option>';

                }
            }
        )

        .catch(
            error => {

                console.error(
                    "Lỗi load tỉnh thành:",
                    error
                );


                provinceSelect.innerHTML =
                    '<option value="">-- Lỗi kết nối GHN --</option>';
            }
        );



        /*
        |--------------------------------------------------------------------------
        | 2. CHỌN TỈNH -> LOAD QUẬN / HUYỆN
        |--------------------------------------------------------------------------
        */

        provinceSelect.addEventListener(
            'change',
            function () {


                districtSelect.innerHTML =
                    '<option value="">-- Đang tải... --</option>';

                districtSelect.disabled =
                    true;


                wardSelect.innerHTML =
                    '<option value="">-- Chọn Phường/Xã --</option>';

                wardSelect.disabled =
                    true;


                updateTotals(0);


                if (!this.value) {
                    return;
                }



                fetch(
                    districtsUrl.replace(
                        '__PROVINCE__',
                        this.value
                    )
                )

                .then(
                    response =>
                        response.json()
                )

                .then(
                    response => {

                        if (response.data) {

                            let options =
                                '<option value="">-- Chọn Quận/Huyện --</option>';


                            response.data.forEach(
                                district => {

                                    options +=
                                        `<option value="${district.DistrictID}">${district.DistrictName}</option>`;
                                }
                            );


                            districtSelect.innerHTML =
                                options;


                            districtSelect.disabled =
                                false;

                        } else {

                            districtSelect.innerHTML =
                                '<option value="">-- Không tải được quận/huyện --</option>';

                        }
                    }
                )

                .catch(
                    error => {

                        console.error(
                            "Lỗi load quận huyện:",
                            error
                        );


                        districtSelect.innerHTML =
                            '<option value="">-- Lỗi kết nối GHN --</option>';

                    }
                );

            }
        );



        /*
        |--------------------------------------------------------------------------
        | 3. CHỌN QUẬN/HUYỆN -> LOAD PHƯỜNG/XÃ
        |--------------------------------------------------------------------------
        */

        districtSelect.addEventListener(
            'change',
            function () {


                wardSelect.innerHTML =
                    '<option value="">-- Đang tải... --</option>';


                wardSelect.disabled =
                    true;


                updateTotals(0);


                if (!this.value) {
                    return;
                }



                fetch(
                    wardsUrl.replace(
                        '__DISTRICT__',
                        this.value
                    )
                )

                .then(
                    response =>
                        response.json()
                )

                .then(
                    response => {

                        if (response.data) {

                            let options =
                                '<option value="">-- Chọn Phường/Xã --</option>';


                            response.data.forEach(
                                ward => {

                                    options +=
                                        `<option value="${ward.WardCode}">${ward.WardName}</option>`;
                                }
                            );


                            wardSelect.innerHTML =
                                options;


                            wardSelect.disabled =
                                false;

                        } else {

                            wardSelect.innerHTML =
                                '<option value="">-- Không tải được phường/xã --</option>';

                        }
                    }
                )

                .catch(
                    error => {

                        console.error(
                            "Lỗi load phường xã:",
                            error
                        );


                        wardSelect.innerHTML =
                            '<option value="">-- Lỗi kết nối GHN --</option>';

                    }
                );

            }
        );



        /*
        |--------------------------------------------------------------------------
        | 4. CHỌN PHƯỜNG/XÃ -> TÍNH PHÍ SHIP
        |--------------------------------------------------------------------------
        */

        wardSelect.addEventListener(
            'change',
            function () {


                if (
                    !this.value ||
                    !districtSelect.value
                ) {
                    return;
                }


                shippingFeeText.innerText =
                    'Đang tính cước...';



                fetch(
                    "{{ route('locations.fee') }}",
                    {

                        method: 'POST',


                        headers: {

                            'Content-Type':
                                'application/json',

                            'X-CSRF-TOKEN':
                                '{{ csrf_token() }}'
                        },


                        body: JSON.stringify({

                            to_district_id:
                                districtSelect.value,

                            to_ward_code:
                                this.value
                        })
                    }
                )


                .then(
                    response =>
                        response.json()
                )


                .then(response => {

                            console.log('Kết quả tính phí GHN:', response);

                            if (
                                response.code === 200 &&
                                response.data
                            ) {

                                const fee =
                                    parseInt(response.data.total) || 0;

                                updateTotals(fee);

                            } else {

                                shippingFeeText.innerText =
                                    response.message
                                        ? response.message
                                        : 'Không tính được phí';

                                finalTotalText.innerText =
                                    new Intl.NumberFormat('vi-VN')
                                        .format(subtotal)
                                        + ' VNĐ';

                                if (shippingFeeInput) {
                                    shippingFeeInput.value = 0;
                                }
                            }

                        })


                .catch(
                    error => {


                        console.error(
                            "Lỗi tính phí:",
                            error
                        );


                        shippingFeeText.innerText =
                            'Lỗi tính phí';


                        updateTotals(
                            0
                        );

                    }
                );

            }
        );



        /*
        |--------------------------------------------------------------------------
        | CẬP NHẬT TỔNG TIỀN
        |--------------------------------------------------------------------------
        */

        function updateTotals(
            fee
        ) {


            shippingFeeText.innerText =
                new Intl.NumberFormat(
                    'vi-VN'
                ).format(
                    fee
                )
                + ' VNĐ';



            const finalAmount =
                subtotal + fee;



            finalTotalText.innerText =
                new Intl.NumberFormat(
                    'vi-VN'
                ).format(
                    finalAmount
                )
                + ' VNĐ';



            if (totalPriceInput) {

                totalPriceInput.value =
                    finalAmount;
            }



            if (shippingFeeInput) {

                shippingFeeInput.value =
                    fee;
            }

        }

    }
);

</script>


</body>

</html>