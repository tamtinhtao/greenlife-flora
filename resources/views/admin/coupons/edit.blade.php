@extends('layouts.admin')


@section(
    'title',
    'Sửa Voucher - GreenLife Admin'
)


@section(
    'page-title',
    'Sửa Voucher'
)


@section(
    'page-subtitle',
    'Cập nhật chương trình khuyến mãi'
)


@section('content')


<div class="mb-4">

    <h2 class="fw-bold mb-1">
        ✏️ Chỉnh Sửa Voucher
    </h2>

    <p class="text-muted mb-0">

        Cập nhật thông tin mã

        <strong class="text-success">
            {{ $coupon->code }}
        </strong>

    </p>

</div>



<div class="card shadow-sm border-0">

    <div class="card-body p-4">


        <form
            action="{{
                route(
                    'admin.coupons.update',
                    $coupon
                )
            }}"
            method="POST"
        >

            @csrf
            @method('PUT')



            <div class="row g-3">


                {{-- ================================================= --}}
                {{-- MÃ VOUCHER --}}
                {{-- ================================================= --}}

                <div class="col-md-6">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Mã Voucher
                        <span class="text-danger">*</span>
                    </label>


                    <input
                        type="text"
                        name="code"
                        value="{{
                            old(
                                'code',
                                $coupon->code
                            )
                        }}"
                        class="
                            form-control
                            text-uppercase
                            @error('code')
                                is-invalid
                            @enderror
                        "
                        placeholder="VD: GREEN10"
                        required
                    >


                    @error('code')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>



                {{-- ================================================= --}}
                {{-- TÊN CHƯƠNG TRÌNH --}}
                {{-- ================================================= --}}

                <div class="col-md-6">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Tên chương trình
                        <span class="text-danger">*</span>
                    </label>


                    <input
                        type="text"
                        name="name"
                        value="{{
                            old(
                                'name',
                                $coupon->name
                            )
                        }}"
                        class="
                            form-control
                            @error('name')
                                is-invalid
                            @enderror
                        "
                        placeholder="VD: Giảm 10% đơn hàng"
                        required
                    >


                    @error('name')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>



                {{-- ================================================= --}}
                {{-- LOẠI GIẢM --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Loại giảm
                        <span class="text-danger">*</span>
                    </label>


                    <select
                        name="type"
                        id="coupon-type"
                        class="
                            form-select
                            @error('type')
                                is-invalid
                            @enderror
                        "
                        required
                    >

                        <option
                            value="percent"
                            @selected(
                                old(
                                    'type',
                                    $coupon->type
                                )
                                === 'percent'
                            )
                        >
                            Giảm theo %
                        </option>


                        <option
                            value="fixed"
                            @selected(
                                old(
                                    'type',
                                    $coupon->type
                                )
                                === 'fixed'
                            )
                        >
                            Giảm số tiền cố định
                        </option>

                    </select>


                    @error('type')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>



                {{-- ================================================= --}}
                {{-- GIÁ TRỊ --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Giá trị giảm
                        <span class="text-danger">*</span>
                    </label>


                    <input
                        type="number"
                        step="0.01"
                        min="0.01"
                        name="value"
                        value="{{
                            old(
                                'value',
                                $coupon->value
                            )
                        }}"
                        class="
                            form-control
                            @error('value')
                                is-invalid
                            @enderror
                        "
                        required
                    >


                    <div class="form-text">

                        Nếu loại là %
                        thì nhập ví dụ:

                        <strong>10</strong>

                        = giảm 10%.

                    </div>


                    @error('value')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>



                {{-- ================================================= --}}
                {{-- GIẢM TỐI ĐA --}}
                {{-- ================================================= --}}

                <div class="col-md-4">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Giảm tối đa
                    </label>


                    <input
                        type="number"
                        min="0"
                        step="1000"
                        name="max_discount_amount"
                        value="{{
                            old(
                                'max_discount_amount',
                                $coupon
                                    ->max_discount_amount
                            )
                        }}"
                        class="
                            form-control
                            @error(
                                'max_discount_amount'
                            )
                                is-invalid
                            @enderror
                        "
                        placeholder="VD: 100000"
                    >


                    <div class="form-text">

                        Thường dùng với
                        voucher giảm theo %.

                    </div>


                    @error('max_discount_amount')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>



                {{-- ================================================= --}}
                {{-- ĐƠN TỐI THIỂU --}}
                {{-- ================================================= --}}

                <div class="col-md-6">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Giá trị đơn hàng tối thiểu
                    </label>


                    <div class="input-group">

                        <input
                            type="number"
                            min="0"
                            step="1000"
                            name="min_order_amount"
                            value="{{
                                old(
                                    'min_order_amount',
                                    $coupon
                                        ->min_order_amount
                                )
                            }}"
                            class="
                                form-control
                                @error(
                                    'min_order_amount'
                                )
                                    is-invalid
                                @enderror
                            "
                        >

                        <span class="input-group-text">
                            VNĐ
                        </span>

                    </div>


                    @error('min_order_amount')

                        <div class="text-danger small mt-1">
                            {{ $message }}
                        </div>

                    @enderror

                </div>



                {{-- ================================================= --}}
                {{-- GIỚI HẠN LƯỢT --}}
                {{-- ================================================= --}}

                <div class="col-md-6">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Tổng số lượt sử dụng
                    </label>


                    <input
                        type="number"
                        min="1"
                        name="usage_limit"
                        value="{{
                            old(
                                'usage_limit',
                                $coupon->usage_limit
                            )
                        }}"
                        class="
                            form-control
                            @error('usage_limit')
                                is-invalid
                            @enderror
                        "
                        placeholder="
                            Để trống nếu không giới hạn
                        "
                    >


                    <div class="form-text">

                        Đã sử dụng:

                        <strong>
                            {{ $coupon->used_count }}
                        </strong>

                        lượt.

                    </div>


                    @error('usage_limit')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>



                {{-- ================================================= --}}
                {{-- BẮT ĐẦU --}}
                {{-- ================================================= --}}

                <div class="col-md-6">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Thời gian bắt đầu
                    </label>


                    <input
                        type="datetime-local"
                        name="starts_at"
                        value="{{
                            old(
                                'starts_at',
                                $coupon->starts_at
                                    ?->format(
                                        'Y-m-d\TH:i'
                                    )
                            )
                        }}"
                        class="
                            form-control
                            @error('starts_at')
                                is-invalid
                            @enderror
                        "
                    >


                    @error('starts_at')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>



                {{-- ================================================= --}}
                {{-- HẾT HẠN --}}
                {{-- ================================================= --}}

                <div class="col-md-6">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Thời gian hết hạn
                    </label>


                    <input
                        type="datetime-local"
                        name="expires_at"
                        value="{{
                            old(
                                'expires_at',
                                $coupon->expires_at
                                    ?->format(
                                        'Y-m-d\TH:i'
                                    )
                            )
                        }}"
                        class="
                            form-control
                            @error('expires_at')
                                is-invalid
                            @enderror
                        "
                    >


                    @error('expires_at')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>



                {{-- ================================================= --}}
                {{-- TRẠNG THÁI --}}
                {{-- ================================================= --}}

                <div class="col-md-6">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Trạng thái
                    </label>


                    <select
                        name="status"
                        class="
                            form-select
                            @error('status')
                                is-invalid
                            @enderror
                        "
                    >

                        <option
                            value="active"
                            @selected(
                                old(
                                    'status',
                                    $coupon->status
                                )
                                === 'active'
                            )
                        >
                            Hoạt động
                        </option>


                        <option
                            value="inactive"
                            @selected(
                                old(
                                    'status',
                                    $coupon->status
                                )
                                === 'inactive'
                            )
                        >
                            Tạm dừng
                        </option>

                    </select>


                    @error('status')

                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>

                    @enderror

                </div>



                {{-- ================================================= --}}
                {{-- THÔNG TIN HIỆN TẠI --}}
                {{-- ================================================= --}}

                <div class="col-md-6">

                    <label class="form-label fw-semibold">
                        Thông tin sử dụng
                    </label>


                    <div
                        class="
                            border
                            rounded
                            bg-light
                            p-3
                        "
                    >

                        Đã dùng:

                        <strong>
                            {{ $coupon->used_count }}
                        </strong>

                        @if($coupon->usage_limit)

                            /

                            {{
                                $coupon
                                    ->usage_limit
                            }}

                        @else

                            / Không giới hạn

                        @endif

                    </div>

                </div>


            </div>



            <hr class="my-4">



            {{-- ================================================= --}}
            {{-- ACTION --}}
            {{-- ================================================= --}}

            <div
                class="
                    d-flex
                    justify-content-between
                    align-items-center
                "
            >

                <a
                    href="{{
                        route(
                            'admin.coupons.index'
                        )
                    }}"
                    class="
                        btn
                        btn-outline-secondary
                    "
                >
                    ← Quay lại
                </a>


                <button
                    type="submit"
                    class="
                        btn
                        btn-success
                        px-4
                    "
                >
                    💾 Lưu Thay Đổi
                </button>

            </div>

        </form>

    </div>

</div>


@endsection