<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Đổi mật khẩu</title>

    <link
        href="{{ asset('bootstrap/css/bootstrap.min.css') }}"
        rel="stylesheet"
    >

</head>


<body style="background: #f5f6f8;">


<div
    class="container py-5"
    style="max-width: 650px;"
>

    <h2 class="fw-bold text-success mb-4">
        🔑 Đổi Mật Khẩu
    </h2>



    @if(session('success'))

        <div class="alert alert-success">
            {{ session('success') }}
        </div>

    @endif


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
                    'account.password.update'
                ) }}"
                method="POST"
            >

                @csrf
                @method('PUT')


                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Mật khẩu hiện tại
                    </label>

                    <input
                        type="password"
                        name="current_password"
                        class="form-control"
                        required
                    >

                </div>



                <div class="mb-3">

                    <label class="form-label fw-semibold">
                        Mật khẩu mới
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        required
                    >

                </div>



                <div class="mb-4">

                    <label class="form-label fw-semibold">
                        Xác nhận mật khẩu mới
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        required
                    >

                </div>



                <button
                    type="submit"
                    class="btn btn-success"
                >
                    🔑 Đổi mật khẩu
                </button>


                <a
                    href="{{ route('account.profile') }}"
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