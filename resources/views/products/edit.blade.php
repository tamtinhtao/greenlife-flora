@extends('layouts.admin')


@section(
    'title',
    'Chỉnh sửa sản phẩm - GreenLife Admin'
)


@section(
    'page-title',
    'Chỉnh sửa sản phẩm'
)


@section(
    'page-subtitle',
    'Cập nhật thông tin cây cảnh hoặc hoa'
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


    .current-product-image {

        width: 100px;
        height: 100px;

        object-fit: cover;

        border-radius: 6px;

        border:
            1px solid #dee2e6;
    }

</style>

@endpush



@section('content')


<div class="product-form-wrapper">


    <div class="product-form-header">


        <div>

            <h1 class="product-form-title">

                Chỉnh sửa sản phẩm

            </h1>


            <p class="product-form-description">

                Đang chỉnh sửa:

                <strong>
                    {{ $product->name }}
                </strong>

            </p>

        </div>


        <a
            href="{{
                route(
                    'admin.products.index'
                )
            }}"
            class="btn btn-outline-secondary"
        >

            ← Danh sách sản phẩm

        </a>

    </div>



    <div class="product-form-card">


        <div class="product-form-card-header">

            ✏️ Thông tin sản phẩm

        </div>



        <div class="product-form-card-body">


            <form
                action="{{
                    route(
                        'admin.products.update',
                        $product->id
                    )
                }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf
                @method('PUT')



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
                            value="{{
                                old(
                                    'name',
                                    $product->name
                                )
                            }}"
                            required
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
                                            'category_id',
                                            $product
                                                ->category_id
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
                            value="{{
                                old(
                                    'price',
                                    $product->price
                                )
                            }}"
                            min="0"
                            required
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
                                    $product->stock
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


                        @if($product->image)

                            <div class="mt-3">

                                <small
                                    class="
                                        text-muted
                                        d-block
                                        mb-2
                                    "
                                >

                                    Ảnh hiện tại

                                </small>


                                <img
                                    src="{{
                                        asset(
                                            $product->image
                                        )
                                    }}"
                                    alt="{{ $product->name }}"
                                    class="current-product-image"
                                >

                            </div>

                        @endif

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
                                    'sunlight',
                                    $product->sunlight
                                )
                            }}"
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
                                    'water',
                                    $product->water
                                )
                            }}"
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
                        >{{ old(
                            'care_guide',
                            $product->care_guide
                        ) }}</textarea>

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
                        >{{ old(
                            'description',
                            $product->description
                        ) }}</textarea>

                    </div>

                </div>



                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-warning"
                    >

                        💾 Cập nhật thông tin

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