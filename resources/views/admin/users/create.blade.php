@extends('layouts.admin')


@section(
    'title',
    'Thêm người dùng - GreenLife Admin'
)


@section(
    'page-title',
    'Thêm người dùng'
)


@section(
    'page-subtitle',
    'Tạo tài khoản User hoặc Admin mới'
)



@push('styles')

<style>

    .user-form-wrapper {

        max-width: 800px;
    }


    .user-form-header {

        display: flex;

        align-items: center;

        justify-content:
            space-between;

        gap: 15px;

        margin-bottom: 22px;
    }


    .user-form-title {

        margin: 0 0 4px;

        font-size: 27px;

        font-weight: 500;
    }


    .user-form-description {

        margin: 0;

        color: #8997a4;
    }


    .user-form-card {

        background: white;

        border:
            1px solid #e1e5e8;

        box-shadow:
            0 1px 4px
            rgba(0,0,0,.05);
    }


    .user-form-card-header {

        padding: 15px 20px;

        border-bottom:
            1px solid #e5e8eb;

        font-weight: 600;
    }


    .user-form-card-body {

        padding: 24px;
    }


    .form-label {

        font-size: 13px;

        font-weight: 600;
    }

</style>

@endpush



@section('content')


<div class="user-form-wrapper">


    <div class="user-form-header">


        <div>

            <h1 class="user-form-title">

                Thêm người dùng

            </h1>


            <p class="user-form-description">

                Tạo tài khoản mới trên hệ thống.

            </p>

        </div>


        <a
            href="{{ route('admin.users.index') }}"
            class="btn btn-outline-secondary"
        >

            ← Danh sách người dùng

        </a>

    </div>



    <div class="user-form-card">


        <div class="user-form-card-header">

            👤 Thông tin tài khoản

        </div>


        <div class="user-form-card-body">


            <form
                action="{{
                    route(
                        'admin.users.store'
                    )
                }}"
                method="POST"
            >

                @csrf



                <div class="mb-3">

                    <label class="form-label">

                        Tên
                        <span class="text-danger">*</span>

                    </label>


                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control"
                        required
                    >

                </div>



                <div class="mb-3">

                    <label class="form-label">

                        Email
                        <span class="text-danger">*</span>

                    </label>


                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control"
                        required
                    >

                </div>



                <div class="row">


                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Mật khẩu
                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required
                        >

                    </div>



                    <div class="col-md-6 mb-3">

                        <label class="form-label">

                            Xác nhận mật khẩu
                            <span class="text-danger">*</span>

                        </label>


                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            required
                        >

                    </div>

                </div>



                <div class="mb-4">

                    <label class="form-label">

                        Vai trò
                        <span class="text-danger">*</span>

                    </label>


                    <select
                        name="role"
                        class="form-select"
                        required
                    >

                        <option
                            value="user"
                            @selected(
                                old(
                                    'role',
                                    'user'
                                )
                                ===
                                'user'
                            )
                        >

                            Người dùng

                        </option>


                        <option
                            value="admin"
                            @selected(
                                old('role')
                                ===
                                'admin'
                            )
                        >

                            Quản trị viên

                        </option>

                    </select>

                </div>



                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-success"
                    >

                        💾 Lưu người dùng

                    </button>


                    <a
                        href="{{
                            route(
                                'admin.users.index'
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