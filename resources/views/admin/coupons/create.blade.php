@extends('layouts.admin')


@section(
    'title',
    'Tạo Voucher - GreenLife Admin'
)


@section(
    'page-title',
    'Tạo Voucher'
)


@section(
    'page-subtitle',
    'Tạo chương trình khuyến mãi mới'
)


@section('content')


<div class="card shadow-sm border-0">

    <div class="card-body p-4">


        <form
            action="{{
                route(
                    'admin.coupons.store'
                )
            }}"
            method="POST"
        >

            @csrf


            <div class="row g-3">


                {{-- CODE --}}
                <div class="col-md-6">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Mã Voucher *
                    </label>

                    <input
                        type="text"
                        name="code"
                        value="{{ old('code') }}"
                        class="
                            form-control
                            text-uppercase
                        "
                        placeholder="VD: GREEN10"
                        required
                    >

                    @error('code')

                        <div
                            class="
                                text-danger
                                small
                                mt-1
                            "
                        >
                            {{ $message }}
                        </div>

                    @enderror

                </div>



                {{-- NAME --}}
                <div class="col-md-6">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Tên chương trình *
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control"
                        placeholder="VD: Giảm 10% đơn hàng"
                        required
                    >

                </div>



                {{-- TYPE --}}
                <div class="col-md-4">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Loại giảm *
                    </label>

                    <select
                        name="type"
                        class="form-select"
                        required
                    >

                        <option value="percent">
                            Giảm theo %
                        </option>

                        <option
                            value="fixed"
                            @selected(
                                old('type')
                                === 'fixed'
                            )
                        >
                            Giảm số tiền cố định
                        </option>

                    </select>

                </div>



                {{-- VALUE --}}
                <div class="col-md-4">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Giá trị giảm *
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        name="value"
                        value="{{ old('value') }}"
                        class="form-control"
                        placeholder="VD: 10"
                        required
                    >

                </div>



                {{-- MAX DISCOUNT --}}
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
                        name="max_discount_amount"
                        value="{{
                            old(
                                'max_discount_amount'
                            )
                        }}"
                        class="form-control"
                        placeholder="VD: 100000"
                    >

                </div>



                {{-- MIN ORDER --}}
                <div class="col-md-6">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Đơn hàng tối thiểu
                    </label>

                    <input
                        type="number"
                        min="0"
                        name="min_order_amount"
                        value="{{
                            old(
                                'min_order_amount',
                                0
                            )
                        }}"
                        class="form-control"
                    >

                </div>



                {{-- USAGE LIMIT --}}
                <div class="col-md-6">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Số lượt sử dụng
                    </label>

                    <input
                        type="number"
                        min="1"
                        name="usage_limit"
                        value="{{
                            old(
                                'usage_limit'
                            )
                        }}"
                        class="form-control"
                        placeholder="Để trống = không giới hạn"
                    >

                </div>



                {{-- START --}}
                <div class="col-md-6">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Bắt đầu
                    </label>

                    <input
                        type="datetime-local"
                        name="starts_at"
                        value="{{
                            old(
                                'starts_at'
                            )
                        }}"
                        class="form-control"
                    >

                </div>



                {{-- EXPIRE --}}
                <div class="col-md-6">

                    <label
                        class="
                            form-label
                            fw-semibold
                        "
                    >
                        Hết hạn
                    </label>

                    <input
                        type="datetime-local"
                        name="expires_at"
                        value="{{
                            old(
                                'expires_at'
                            )
                        }}"
                        class="form-control"
                    >

                </div>



                {{-- STATUS --}}
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
                        class="form-select"
                    >

                        <option value="active">
                            Hoạt động
                        </option>

                        <option value="inactive">
                            Tạm dừng
                        </option>

                    </select>

                </div>

            </div>



            <hr class="my-4">


            <div
                class="
                    d-flex
                    gap-2
                "
            >

                <button
                    type="submit"
                    class="btn btn-success"
                >
                    💾 Lưu Voucher
                </button>


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
                    Hủy
                </a>

            </div>

        </form>

    </div>

</div>


@endsection