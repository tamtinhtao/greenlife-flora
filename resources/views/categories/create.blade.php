@extends('layouts.admin')


@section(
    'title',
    'Thêm danh mục - GreenLife Admin'
)


@section(
    'page-title',
    'Thêm danh mục'
)


@section(
    'page-subtitle',
    'Tạo danh mục sản phẩm mới'
)



@push('styles')

<style>

    .category-form-wrapper {

        max-width: 850px;
    }


    .category-form-header {

        display: flex;

        justify-content:
            space-between;

        align-items: center;

        gap: 15px;

        margin-bottom: 22px;
    }


    .category-form-title {

        margin: 0 0 4px;

        font-size: 27px;

        font-weight: 500;
    }


    .category-form-description {

        margin: 0;

        color: #8997a4;
    }


    .category-form-card {

        background: white;

        border:
            1px solid #e1e5e8;

        box-shadow:
            0 1px 4px
            rgba(0,0,0,.05);
    }


    .category-form-header-card {

        padding: 15px 20px;

        border-bottom:
            1px solid #e5e8eb;

        font-weight: 600;
    }


    .category-form-body {

        padding: 24px;
    }


    .form-label {

        font-weight: 600;

        font-size: 13px;
    }

</style>

@endpush



@section('content')


<div class="category-form-wrapper">


    <div class="category-form-header">


        <div>

            <h1 class="category-form-title">

                Thêm danh mục mới

            </h1>


            <p class="category-form-description">

                Tạo nhóm sản phẩm mới
                cho cửa hàng.

            </p>

        </div>


        <a
            href="{{
                route(
                    'admin.categories.index'
                )
            }}"
            class="btn btn-outline-secondary"
        >

            ← Danh sách danh mục

        </a>

    </div>



    <div class="category-form-card">


        <div class="category-form-header-card">

            📂 Thông tin danh mục

        </div>



        <div class="category-form-body">


            <form
                action="{{
                    route(
                        'admin.categories.store'
                    )
                }}"
                method="POST"
            >

                @csrf



                <div class="mb-3">

                    <label class="form-label">

                        Tên danh mục

                        <span class="text-danger">
                            *
                        </span>

                    </label>


                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name') }}"
                        required
                        placeholder="Ví dụ: Cây để bàn"
                    >

                </div>



                <div class="mb-4">

                    <label class="form-label">

                        Mô tả

                    </label>


                    <textarea
                        name="description"
                        class="form-control"
                        rows="5"
                        placeholder="Nhập mô tả danh mục..."
                    >{{ old('description') }}</textarea>

                </div>



                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-success"
                    >

                        💾 Lưu danh mục

                    </button>


                    <a
                        href="{{
                            route(
                                'admin.categories.index'
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