@extends('layouts.admin')


@section(
    'title',
    'Quản lý người dùng - GreenLife Admin'
)


@section(
    'page-title',
    'Quản lý tài khoản'
)


@section(
    'page-subtitle',
    'Quản lý tài khoản User và Admin'
)



@push('styles')

<style>

    .user-page-header {

        display: flex;

        align-items: center;

        justify-content:
            space-between;

        flex-wrap: wrap;

        gap: 15px;

        margin-bottom: 22px;
    }


    .user-page-title {

        margin: 0 0 4px;

        font-size: 27px;

        font-weight: 500;
    }


    .user-page-description {

        margin: 0;

        color: #8997a4;
    }


    .user-card {

        background: white;

        border:
            1px solid #e1e5e8;

        box-shadow:
            0 1px 4px
            rgba(0,0,0,.05);
    }


    .user-table {

        min-width: 900px;

        margin-bottom: 0;
    }


    .user-table thead th {

        background: #eef2f5;

        color: #3e4953;

        font-size: 12px;

        white-space: nowrap;
    }


    .user-table td {

        vertical-align: middle;
    }


    .user-actions {

        min-width: 240px;

        white-space: nowrap;
    }

</style>

@endpush



@section('content')


<div class="user-page-header">


    <div>

        <h1 class="user-page-title">

            Quản lý người dùng

        </h1>


        <p class="user-page-description">

            Quản lý tài khoản khách hàng
            và quản trị viên.

        </p>

    </div>



    <a
        href="{{ route('admin.users.create') }}"
        class="btn btn-success"
    >

        ➕ Thêm người dùng

    </a>

</div>



<div class="user-card">


    <div class="table-responsive">

        <table
            class="
                table
                table-hover
                align-middle
                user-table
            "
        >


            <thead>

                <tr>

                    <th>
                        ID
                    </th>

                    <th>
                        Tên
                    </th>

                    <th>
                        Email
                    </th>

                    <th>
                        Vai trò
                    </th>

                    <th>
                        Trạng thái email
                    </th>

                    <th class="text-center">
                        Hành động
                    </th>

                </tr>

            </thead>



            <tbody>


            @forelse(
                $users
                as $user
            )


                <tr>


                    <td>

                        #{{ $user->id }}

                    </td>



                    <td>

                        <strong>

                            {{ $user->name }}

                        </strong>


                        @if(
                            auth()->id()
                            ===
                            $user->id
                        )

                            <span
                                class="
                                    badge
                                    bg-secondary
                                "
                            >

                                Bạn

                            </span>

                        @endif

                    </td>



                    <td>

                        {{ $user->email }}

                    </td>



                    <td>

                        @if(
                            $user->role
                            ===
                            'admin'
                        )

                            <span
                                class="
                                    badge
                                    bg-danger
                                "
                            >

                                Admin

                            </span>

                        @else

                            <span
                                class="
                                    badge
                                    bg-success
                                "
                            >

                                User

                            </span>

                        @endif

                    </td>



                    <td>

                        @if(
                            $user
                                ->email_verified_at
                        )

                            <span
                                class="text-success"
                            >

                                ✓ Đã xác thực

                            </span>

                        @else

                            <span
                                class="text-warning"
                            >

                                Chưa xác thực

                            </span>

                        @endif

                    </td>



                    <td
                        class="
                            text-center
                            user-actions
                        "
                    >


                        <a
                            href="{{
                                route(
                                    'admin.users.show',
                                    $user
                                )
                            }}"
                            class="
                                btn
                                btn-info
                                btn-sm
                                text-white
                            "
                        >

                            👁 Xem

                        </a>



                        <a
                            href="{{
                                route(
                                    'admin.users.edit',
                                    $user
                                )
                            }}"
                            class="
                                btn
                                btn-primary
                                btn-sm
                            "
                        >

                            ✏️ Sửa

                        </a>



                        @if(
                            auth()->id()
                            !==
                            $user->id
                        )

                            <form
                                action="{{
                                    route(
                                        'admin.users.destroy',
                                        $user
                                    )
                                }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="
                                    return confirm(
                                        'Bạn chắc chắn muốn xóa người dùng này?'
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

                        @endif

                    </td>

                </tr>


            @empty


                <tr>

                    <td
                        colspan="6"
                        class="
                            text-center
                            text-muted
                            py-5
                        "
                    >

                        Không có người dùng nào.

                    </td>

                </tr>


            @endforelse


            </tbody>

        </table>

    </div>

</div>


@endsection