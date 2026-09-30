@extends('layouts.admin')


@section(
    'title',
    'Quản lý danh mục - GreenLife Admin'
)


@section(
    'page-title',
    'Quản trị danh mục'
)


@section(
    'page-subtitle',
    'Quản lý các nhóm cây cảnh và hoa'
)



@push('styles')

<style>

    .category-page-header {

        display: flex;

        justify-content:
            space-between;

        align-items: center;

        gap: 15px;

        flex-wrap: wrap;

        margin-bottom: 22px;
    }


    .category-page-title {

        margin: 0 0 4px;

        font-size: 27px;

        font-weight: 500;
    }


    .category-page-description {

        margin: 0;

        color: #8997a4;
    }


    .category-card {

        background: white;

        border:
            1px solid #e1e5e8;

        box-shadow:
            0 1px 4px
            rgba(0,0,0,.05);
    }


    .category-table {

        margin-bottom: 0;

        min-width: 750px;
    }


    .category-table thead th {

        background: #eef2f5;

        color: #3e4953;

        font-size: 12px;

        vertical-align: middle;

        white-space: nowrap;
    }


    .category-table td {

        vertical-align: middle;
    }


    .category-name {

        font-weight: 600;
    }


    .category-description {

        color: #6c757d;
    }


    .category-actions {

        width: 180px;

        white-space: nowrap;
    }

</style>

@endpush



@section('content')


<div class="category-page-header">


    <div>

        <h1 class="category-page-title">

            Quản lý danh mục

        </h1>


        <p class="category-page-description">

            Tạo, chỉnh sửa và xóa
            các danh mục sản phẩm.

        </p>

    </div>



    <a
        href="{{
            route(
                'admin.categories.create'
            )
        }}"
        class="btn btn-success"
    >

        ➕ Thêm Danh Mục

    </a>

</div>



<div class="category-card">


    <div class="table-responsive">

        <table
            class="
                table
                table-hover
                align-middle
                category-table
            "
        >


            <thead>

                <tr>

                    <th style="width: 80px;">

                        ID

                    </th>


                    <th>

                        Tên danh mục

                    </th>


                    <th>

                        Mô tả

                    </th>


                    <th class="category-actions">

                        Hành động

                    </th>

                </tr>

            </thead>



            <tbody>


            @forelse(
                $categories
                as $category
            )

                <tr>


                    <td>

                        #{{ $category->id }}

                    </td>



                    <td>

                        <div class="category-name">

                            {{ $category->name }}

                        </div>

                    </td>



                    <td>

                        <div class="category-description">

                            {{
                                $category
                                    ->description
                                ?: 'Chưa có mô tả'
                            }}

                        </div>

                    </td>



                    <td class="category-actions">


                        <a
                            href="{{
                                route(
                                    'admin.categories.edit',
                                    $category->id
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
                                    'admin.categories.destroy',
                                    $category->id
                                )
                            }}"
                            method="POST"
                            class="d-inline"
                            onsubmit="
                                return confirm(
                                    'Bạn có chắc muốn xóa danh mục {{ $category->name }}?'
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
                        colspan="4"
                        class="
                            text-center
                            text-muted
                            py-5
                        "
                    >

                        Chưa có danh mục nào.

                    </td>

                </tr>


            @endforelse


            </tbody>

        </table>

    </div>

</div>


@endsection