@extends('layouts.admin')


@section(
    'title',
    'Voucher - GreenLife Admin'
)


@section(
    'page-title',
    'Quản lý Voucher'
)


@section(
    'page-subtitle',
    'Khuyến mãi và mã giảm giá'
)


@section('content')


<div
    class="
        d-flex
        justify-content-between
        align-items-center
        mb-4
    "
>

    <div>

        <h2 class="fw-bold mb-1">
            🎟 Quản lý Voucher
        </h2>

        <p class="text-muted mb-0">
            Tạo và quản lý các chương trình giảm giá.
        </p>

    </div>


    <a
        href="{{
            route(
                'admin.coupons.create'
            )
        }}"
        class="btn btn-success"
    >
        + Tạo Voucher
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


@if(session('error'))

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



<div class="card shadow-sm border-0">

    <div class="card-body p-0">

        <div class="table-responsive">

            <table
                class="
                    table
                    table-hover
                    align-middle
                    mb-0
                "
            >

                <thead class="table-light">

                <tr>

                    <th class="ps-4">
                        Mã
                    </th>

                    <th>
                        Chương trình
                    </th>

                    <th>
                        Giá trị
                    </th>

                    <th>
                        Đơn tối thiểu
                    </th>

                    <th>
                        Đã dùng
                    </th>

                    <th>
                        Thời hạn
                    </th>

                    <th>
                        Trạng thái
                    </th>

                    <th
                        class="
                            text-end
                            pe-4
                        "
                    >
                        Thao tác
                    </th>

                </tr>

                </thead>


                <tbody>

                @forelse(
                    $coupons
                    as $coupon
                )

                    <tr>

                        {{-- CODE --}}
                        <td class="ps-4">

                            <span
                                class="
                                    badge
                                    bg-dark
                                    fs-6
                                "
                            >
                                {{ $coupon->code }}
                            </span>

                        </td>


                        {{-- NAME --}}
                        <td>

                            <strong>
                                {{ $coupon->name }}
                            </strong>

                        </td>


                        {{-- VALUE --}}
                        <td>

                            @if(
                                $coupon->type
                                === 'percent'
                            )

                                <strong
                                    class="text-success"
                                >
                                    {{ (float) $coupon->value }}%
                                </strong>

                                @if(
                                    $coupon
                                        ->max_discount_amount
                                )

                                    <div
                                        class="
                                            small
                                            text-muted
                                        "
                                    >
                                        Tối đa
                                        {{
                                            number_format(
                                                $coupon
                                                    ->max_discount_amount
                                            )
                                        }}đ
                                    </div>

                                @endif

                            @else

                                <strong
                                    class="text-success"
                                >
                                    {{
                                        number_format(
                                            $coupon->value
                                        )
                                    }}đ
                                </strong>

                            @endif

                        </td>


                        {{-- MIN ORDER --}}
                        <td>

                            {{
                                number_format(
                                    $coupon
                                        ->min_order_amount
                                )
                            }}đ

                        </td>


                        {{-- USAGE --}}
                        <td>

                            {{ $coupon->used_count }}

                            /

                            {{
                                $coupon->usage_limit
                                ?? '∞'
                            }}

                        </td>


                        {{-- DATE --}}
                        <td>

                            <div class="small">

                                @if(
                                    $coupon->starts_at
                                )

                                    Từ:
                                    {{
                                        $coupon
                                            ->starts_at
                                            ->format(
                                                'd/m/Y H:i'
                                            )
                                    }}

                                    <br>

                                @endif


                                @if(
                                    $coupon->expires_at
                                )

                                    Đến:
                                    {{
                                        $coupon
                                            ->expires_at
                                            ->format(
                                                'd/m/Y H:i'
                                            )
                                    }}

                                @else

                                    Không giới hạn

                                @endif

                            </div>

                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if(
                                $coupon->status
                                === 'active'
                            )

                                <span
                                    class="
                                        badge
                                        bg-success
                                    "
                                >
                                    Hoạt động
                                </span>

                            @else

                                <span
                                    class="
                                        badge
                                        bg-secondary
                                    "
                                >
                                    Tạm dừng
                                </span>

                            @endif

                        </td>


                        {{-- ACTION --}}
                        <td
                            class="
                                text-end
                                pe-4
                            "
                        >

                            <div
                                class="
                                    d-flex
                                    gap-2
                                    justify-content-end
                                "
                            >

                                <a
                                    href="{{
                                        route(
                                            'admin.coupons.edit',
                                            $coupon
                                        )
                                    }}"
                                    class="
                                        btn
                                        btn-sm
                                        btn-warning
                                    "
                                >
                                    ✏️ Sửa
                                </a>


                                <form
                                    action="{{
                                        route(
                                            'admin.coupons.destroy',
                                            $coupon
                                        )
                                    }}"
                                    method="POST"
                                    onsubmit="
                                        return confirm(
                                            'Bạn có chắc muốn xóa voucher này?'
                                        );
                                    "
                                >

                                    @csrf
                                    @method('DELETE')


                                    <button
                                        type="submit"
                                        class="
                                            btn
                                            btn-sm
                                            btn-danger
                                        "
                                    >
                                        🗑 Xóa
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>


                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="
                                text-center
                                text-muted
                                py-5
                            "
                        >

                            Chưa có voucher nào.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>



@if(
    $coupons->hasPages()
)

    <div class="mt-4">

        {{
            $coupons
                ->links()
        }}

    </div>

@endif


@endsection