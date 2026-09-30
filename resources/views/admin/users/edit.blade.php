<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Chỉnh sửa người dùng</title>

    <link
        href="{{ asset('bootstrap/css/bootstrap.min.css') }}"
        rel="stylesheet"
    >

</head>


<body style="background: #f5f6f8;">


<div
    class="container py-4"
    style="max-width: 750px;"
>

    <h2 class="fw-bold text-success mb-4">
        ✏️ Chỉnh Sửa Người Dùng
    </h2>


    @if(session('error'))

        <div class="alert alert-danger">
            {{ session('error') }}
        </div>

    @endif


    @if($errors->any())

        <div class="alert alert-danger">

            <ul class="mb-0">

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <div class="card shadow-sm">

        <div class="card-body p-4">

            <form
                action="{{ route(
                    'admin.users.update',
                    $user
                ) }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                <div class="mb-3">

                    <label class="form-label">
                        Tên
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        class="form-control"
                        required
                    >

                </div>


                <div class="mb-3">

                    <label class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        class="form-control"
                        required
                    >

                </div>


                <div class="mb-4">

                    <label class="form-label">
                        Vai trò
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
                                    $user->role
                                ) === 'user'
                            )
                        >
                            Người dùng
                        </option>


                        <option
                            value="admin"
                            @selected(
                                old(
                                    'role',
                                    $user->role
                                ) === 'admin'
                            )
                        >
                            Quản trị viên
                        </option>

                    </select>

                </div>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    💾 Cập nhật
                </button>


                <a
                    href="{{ route('admin.users.index') }}"
                    class="btn btn-secondary"
                >
                    ← Quay lại
                </a>

            </form>

        </div>

    </div>

</div>


</body>
</html>