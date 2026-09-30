@extends('layouts.admin')


@section(
    'title',
    'Quản lý sản phẩm - GreenLife Admin'
)


@section(
    'page-title',
    'Quản trị sản phẩm'
)


@section(
    'page-subtitle',
    'Quản lý danh sách cây cảnh và hoa'
)



@push('styles')

<style>

    .product-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;

        gap: 15px;

        flex-wrap: wrap;

        margin-bottom: 22px;
    }


    .product-page-title {
        margin: 0 0 4px;

        font-size: 27px;
        font-weight: 500;
    }


    .product-page-description {
        margin: 0;

        color: #8997a4;
    }


    .product-card {
        background: white;

        border:
            1px solid #e1e5e8;

        box-shadow:
            0 1px 4px
            rgba(0,0,0,.05);
    }


    .product-table {
        margin-bottom: 0;

        min-width: 950px;
    }


    .product-table thead th {
        background: #eef2f5;

        color: #3e4953;

        font-size: 12px;

        white-space: nowrap;

        vertical-align: middle;
    }


    .product-table td {
        vertical-align: middle;
    }


    .product-image {
        width: 65px;
        height: 65px;

        object-fit: cover;

        border-radius: 6px;

        border:
            1px solid #dee2e6;
    }


    .product-name {
        font-weight: 600;
    }


    .product-price {
        color: #dc3545;

        font-weight: 700;

        white-space: nowrap;
    }


    .product-stock {
        display: inline-block;

        min-width: 42px;

        padding: 4px 7px;

        border-radius: 4px;

        text-align: center;

        background: #eef5f9;

        color: #31708f;

        font-weight: 600;
    }


    .product-care {
        min-width: 180px;

        font-size: 12px;

        line-height: 1.7;
    }


    .product-actions {
        min-width: 140px;

        white-space: nowrap;
    }

</style>

@endpush



@section('content')


<div class="product-page-header">


    <div>

        <h1 class="product-page-title">

            Quản lý sản phẩm

        </h1>


        <p class="product-page-description">

            Quản lý cây cảnh, hoa, giá bán,
            tồn kho và thông tin chăm sóc.

        </p>

    </div>



    <a
        href="{{ route('admin.products.create') }}"
        class="btn btn-success"
    >

        ➕ Thêm Cây Cảnh Mới

    </a>

</div>



<div class="product-card">

    <div class="table-responsive">

        <table
            class="
                table
                table-hover
                align-middle
                product-table
            "
        >

            <thead>

                <tr>

                    <th>
                        Ảnh
                    </th>

                    <th>
                        Tên cây
                    </th>

                    <th>
                        Danh mục
                    </th>

                    <th>
                        Giá bán
                    </th>

                    <th>
                        Tồn kho
                    </th>

                    <th>
                        Chăm sóc
                    </th>

                    <th>
                        Hành động
                    </th>

                </tr>

            </thead>


            <tbody>


            @forelse(
                $products
                as $item
            )

                <tr>


                    {{-- IMAGE --}}
                    <td>

                        @if($item->image)

                            <img
                                src="{{ asset($item->image) }}"
                                alt="{{ $item->name }}"
                                class="product-image"
                            >

                        @else

                            <div
                                class="
                                    text-muted
                                    small
                                "
                            >
                                Chưa có ảnh
                            </div>

                        @endif

                    </td>



                    {{-- NAME --}}
                    <td>

                        <div class="product-name">

                            {{ $item->name }}

                        </div>


                        @if($item->description)

                            <small class="text-muted">

                                {{
                                    \Illuminate\Support\Str::limit(
                                        $item->description,
                                        55
                                    )
                                }}

                            </small>

                        @endif

                    </td>



                    {{-- CATEGORY --}}
                    <td>

                        <span
                            class="
                                badge
                                bg-info
                                text-dark
                            "
                        >

                            {{
                                $item
                                    ->category
                                    ->name
                                ?? 'N/A'
                            }}

                        </span>

                    </td>



                    {{-- PRICE --}}
                    <td>

                        <span class="product-price">

                            {{
                                number_format(
                                    $item->price,
                                    0,
                                    ',',
                                    '.'
                                )
                            }} đ

                        </span>

                    </td>



                    {{-- STOCK --}}
                    <td>

                        <span class="product-stock">

                            {{ $item->stock }}

                        </span>

                    </td>



                    {{-- CARE --}}
                    <td>

                        <div class="product-care">

                            <div>
                                ☀️
                                {{
                                    $item->sunlight
                                    ?: 'Chưa cập nhật'
                                }}
                            </div>

                            <div>
                                💧
                                {{
                                    $item->water
                                    ?: 'Chưa cập nhật'
                                }}
                            </div>

                        </div>

                    </td>



                    {{-- ACTION --}}
                    <td class="product-actions">


                        <a
                            href="{{
                                route(
                                    'admin.products.edit',
                                    $item->id
                                )
                            }}"
                            class="
                                btn
                                btn-warning
                                btn-sm
                            "
                        >

                            ✏️ Sửa

                        </a>



                        <form
                            action="{{
                                route(
                                    'admin.products.destroy',
                                    $item->id
                                )
                            }}"
                            method="POST"
                            class="d-inline"
                            onsubmit="
                                return confirm(
                                    'Bạn có chắc muốn xóa sản phẩm {{ $item->name }}?'
                                );
                            "
                        >

                            @csrf
                            @method('DELETE')


                            <button
                                type="submit"
                                class="
                                    btn
                                    btn-danger
                                    btn-sm
                                "
                            >

                                🗑 Xóa

                            </button>

                        </form>

                    </td>

                </tr>


            @empty


                <tr>

                    <td
                        colspan="7"
                        class="
                            text-center
                            text-muted
                            py-5
                        "
                    >

                        Chưa có sản phẩm nào.

                    </td>

                </tr>


            @endforelse


            </tbody>

        </table>

    </div>

</div>


@endsection