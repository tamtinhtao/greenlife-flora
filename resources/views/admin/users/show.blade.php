<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <title>Thông tin người dùng</title>

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
        👤 Thông Tin Người Dùng
    </h2>


    <div class="card shadow-sm">

        <div class="card-body p-4">

            <p>
                <strong>ID:</strong>
                #{{ $user->id }}
            </p>

            <p>
                <strong>Tên:</strong>
                {{ $user->name }}
            </p>

            <p>
                <strong>Email:</strong>
                {{ $user->email }}
            </p>

            <p>

                <strong>Vai trò:</strong>

                @if($user->role === 'admin')

                    <span class="badge bg-danger">
                        Admin
                    </span>

                @else

                    <span class="badge bg-success">
                        User
                    </span>

                @endif

            </p>


            <p>

                <strong>Xác thực Email:</strong>

                @if($user->email_verified_at)

                    <span class="text-success">
                        ✓ Đã xác thực
                    </span>

                @else

                    <span class="text-warning">
                        Chưa xác thực
                    </span>

                @endif

            </p>


            <p>
                <strong>Ngày tạo:</strong>

                {{
                    $user->created_at
                        ?->format('H:i d/m/Y')
                }}
            </p>

        </div>

    </div>


    <div class="mt-3">

        <a
            href="{{ route(
                'admin.users.edit',
                $user
            ) }}"
            class="btn btn-primary"
        >
            ✏️ Chỉnh sửa
        </a>

        <a
            href="{{ route('admin.users.index') }}"
            class="btn btn-secondary"
        >
            ← Quay lại
        </a>

    </div>

</div>


</body>
</html>