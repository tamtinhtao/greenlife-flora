<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Đăng ký - GreenLife Flora</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
            background:
                linear-gradient(
                    135deg,
                    #e8f5e9,
                    #f1f8e9
                );
        }

        .auth-container {
            width: 100%;
            max-width: 460px;
            background: white;
            padding: 40px;
            border-radius: 18px;
            box-shadow:
                0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .logo {
            text-align: center;
            font-size: 32px;
            margin-bottom: 10px;
        }

        h1 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 8px;
        }

        .description {
            text-align: center;
            color: #777;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input {
            width: 100%;
            padding: 13px 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            outline: none;
            font-size: 15px;
        }

        input:focus {
            border-color: #43a047;
            box-shadow:
                0 0 0 3px rgba(67, 160, 71, .12);
        }

        button {
            width: 100%;
            border: none;
            padding: 14px;
            border-radius: 10px;
            background: #2e7d32;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #1b5e20;
        }

        .login-link {
            margin-top: 25px;
            text-align: center;
        }

        .login-link a {
            color: #2e7d32;
            font-weight: bold;
            text-decoration: none;
        }

        .error {
            background: #ffebee;
            color: #c62828;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .home-link {
            display: block;
            text-align: center;
            margin-top: 18px;
            text-decoration: none;
            color: #777;
        }
    </style>
</head>

<body>

<div class="auth-container">

    <div class="logo">
        🌱
    </div>

    <h1>Tạo tài khoản</h1>

    <div class="description">
        Tham gia GreenLife Flora
    </div>

    @if($errors->any())

        <div class="error">

            @foreach($errors->all() as $error)

                <div>
                    {{ $error }}
                </div>

            @endforeach

        </div>

    @endif

    <form
        action="{{ route('register.post') }}"
        method="POST"
    >

        @csrf

        <div class="form-group">

            <label for="name">
                Họ và tên
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Nhập họ và tên"
                required
            >

        </div>

        <div class="form-group">

            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="example@gmail.com"
                required
            >

        </div>

        <div class="form-group">

            <label for="password">
                Mật khẩu
            </label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Ít nhất 6 ký tự"
                required
            >

        </div>

        <div class="form-group">

            <label for="password_confirmation">
                Xác nhận mật khẩu
            </label>

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Nhập lại mật khẩu"
                required
            >

        </div>

        <button type="submit">
            Đăng ký
        </button>

    </form>

    <div class="login-link">

        Đã có tài khoản?

        <a href="{{ route('login') }}">
            Đăng nhập
        </a>

    </div>

    <a
        href="{{ route('home') }}"
        class="home-link"
    >
        ← Quay lại trang chủ
    </a>

</div>

</body>
</html>