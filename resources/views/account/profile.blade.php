<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Thông tin tài khoản</title>

    <link
        href="{{ asset('bootstrap/css/bootstrap.min.css') }}"
        rel="stylesheet"
    >

</head>


<body style="background: #f5f6f8;">


<div
    class="container py-5"
    style="max-width: 750px;"
>

    <div class="mb-4">

        <h2 class="fw-bold text-success">
            👤 Thông Tin Tài Khoản
        </h2>

        <div class="text-muted">
            Quản lý thông tin cá nhân của bạn
        </div>

    </div>



    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
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
                action="{{ route('account.update') }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Họ tên
                    </label>

                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $user->name) }}"
                        required
                    >

                </div>



                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $user->email) }}"
                        required
                    >

                    <div class="form-text">
                        Nếu thay đổi email, bạn cần xác thực lại địa chỉ email mới.
                    </div>

                </div>



                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Vai trò
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        value="{{ $user->role === 'admin' ? 'Quản trị viên' : 'Người dùng' }}"
                        disabled
                    >

                </div>



                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Trạng thái Email
                    </label>

                    <div>

                        @if($user->email_verified_at)

                            <span class="badge bg-success">
                                ✓ Đã xác thực
                            </span>

                        @else

                            <span class="badge bg-warning text-dark">
                                Chưa xác thực
                            </span>

                        @endif

                    </div>

                </div>



                <div class="d-flex gap-2">

                    <button
                        type="submit"
                        class="btn btn-success"
                    >
                        💾 Lưu thay đổi
                    </button>


                    <a
                        href="{{ route('account.password') }}"
                        class="btn btn-outline-primary"
                    >
                        🔑 Đổi mật khẩu
                    </a>


                    <a
                        href="{{ route('home') }}"
                        class="btn btn-outline-secondary"
                    >
                        ← Trang chủ
                    </a>

                </div>

            </form>

        </div>

    </div>

</div>


</body>
</html>