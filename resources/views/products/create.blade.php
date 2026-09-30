@extends('layouts.admin')


@section(
    'title',
    'Thêm sản phẩm - GreenLife Admin'
)


@section(
    'page-title',
    'Thêm sản phẩm'
)


@section(
    'page-subtitle',
    'Thêm cây cảnh hoặc hoa mới vào cửa hàng'
)



@push('styles')

<style>

    .product-form-wrapper {
        max-width: 1050px;
    }


    .product-form-header {
        display: flex;

        justify-content:
            space-between;

        align-items: center;

        gap: 15px;

        margin-bottom: 22px;
    }


    .product-form-title {
        margin: 0 0 4px;

        font-size: 27px;

        font-weight: 500;
    }


    .product-form-description {
        margin: 0;

        color: #8997a4;
    }


    .product-form-card {

        background: white;

        border:
            1px solid #e1e5e8;

        box-shadow:
            0 1px 4px
            rgba(0,0,0,.05);
    }


    .product-form-card-header {

        padding: 15px 20px;

        border-bottom:
            1px solid #e5e8eb;

        font-size: 15px;

        font-weight: 600;
    }


    .product-form-card-body {
        padding: 24px;
    }


    .form-label {
        font-weight: 600;

        font-size: 13px;
    }

</style>

@endpush



@section('content')


<div class="product-form-wrapper">


    <div class="product-form-header">


        <div>

            <h1 class="product-form-title">

                Thêm cây cảnh / hoa mới

            </h1>


            <p class="product-form-description">

                Nhập thông tin sản phẩm mới
                vào hệ thống.

            </p>

        </div>



        <a
            href="{{ route('admin.products.index') }}"
            class="btn btn-outline-secondary"
        >

            ← Danh sách sản phẩm

        </a>

    </div>



    <div class="product-form-card">


        <div class="product-form-card-header">

            🌿 Thông tin sản phẩm

        </div>



        <div class="product-form-card-body">


            <form
                action="{{
                    route(
                        'admin.products.store'
                    )
                }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf



                <div class="row">


                    {{-- NAME --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Tên cây / hoa
                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="{{ old('name') }}"
                            required
                            placeholder="Ví dụ: Cây Kim Tiền"
                        >

                    </div>



                    {{-- CATEGORY --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Danh mục
                            <span class="text-danger">*</span>

                        </label>


                        <select
                            name="category_id"
                            class="form-select"
                            required
                        >

                            <option value="">

                                -- Chọn danh mục --

                            </option>


                            @foreach(
                                $categories
                                as $cat
                            )

                                <option
                                    value="{{ $cat->id }}"

                                    @selected(
                                        old(
                                            'category_id'
                                        )
                                        ==
                                        $cat->id
                                    )
                                >

                                    {{ $cat->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>



                    {{-- PRICE --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Giá bán (VNĐ)
                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="number"
                            name="price"
                            class="form-control"
                            value="{{ old('price') }}"
                            min="0"
                            required
                            placeholder="150000"
                        >

                    </div>



                    {{-- STOCK --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Số lượng tồn kho
                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="number"
                            name="stock"
                            class="form-control"
                            value="{{
                                old(
                                    'stock',
                                    10
                                )
                            }}"
                            min="0"
                            required
                        >

                    </div>



                    {{-- IMAGE --}}
                    <div class="col-md-4 mb-3">

                        <label class="form-label">

                            Hình ảnh cây

                        </label>


                        <input
                            type="file"
                            name="image"
                            class="form-control"
                            accept="image/*"
                        >

                    </div>



                    {{-- SUNLIGHT --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Nhu cầu ánh sáng

                        </label>


                        <input
                            type="text"
                            name="sunlight"
                            class="form-control"
                            value="{{
                                old(
                                    'sunlight'
                                )
                            }}"
                            placeholder="Ví dụ: Nắng nhẹ, mát mẻ"
                        >

                    </div>



                    {{-- WATER --}}
                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Tần suất tưới nước

                        </label>


                        <input
                            type="text"
                            name="water"
                            class="form-control"
                            value="{{
                                old(
                                    'water'
                                )
                            }}"
                            placeholder="Ví dụ: 2-3 lần/tuần"
                        >

                    </div>



                    {{-- CARE --}}
                    <div class="col-12 mb-3">

                        <label class="form-label">

                            Hướng dẫn chăm sóc

                        </label>


                        <textarea
                            name="care_guide"
                            class="form-control"
                            rows="3"
                            placeholder="Ghi chú cách chăm sóc, bón phân, tưới nước..."
                        >{{ old('care_guide') }}</textarea>

                    </div>



                    {{-- DESCRIPTION --}}
                    <div class="col-12 mb-4">

                        <label class="form-label">

                            Mô tả sản phẩm

                        </label>


                        <textarea
                            name="description"
                            class="form-control"
                            rows="4"
                            placeholder="Nhập mô tả sản phẩm..."
                        >{{ old('description') }}</textarea>

                    </div>

                </div>



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

                        💾 Lưu sản phẩm

                    </button>


                    <a
                        href="{{
                            route(
                                'admin.products.index'
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

</div>


@endsection