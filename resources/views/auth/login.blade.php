<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Đăng nhập - GreenLife Flora</title>

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
            background:
                linear-gradient(
                    135deg,
                    #e8f5e9,
                    #f1f8e9
                );
        }

        .auth-container {
            width: 100%;
            max-width: 430px;
            background: #ffffff;
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
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input[type="email"],
        input[type="password"] {
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

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            color: #555;
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

        .register-link {
            margin-top: 25px;
            text-align: center;
        }

        .register-link a {
            color: #2e7d32;
            font-weight: bold;
            text-decoration: none;
        }

        .error {
            margin-bottom: 20px;
            padding: 12px;
            background: #ffebee;
            color: #c62828;
            border-radius: 8px;
        }

        .success {
            margin-bottom: 20px;
            padding: 12px;
            background: #e8f5e9;
            color: #2e7d32;
            border-radius: 8px;
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
        🌿
    </div>

    <h1>GreenLife Flora</h1>

    <div class="description">
        Đăng nhập vào tài khoản của bạn
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

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
        action="{{ route('login.post') }}"
        method="POST"
    >

        @csrf

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
                placeholder="Nhập mật khẩu"
                required
            >

        </div>

        <div class="remember">

            <input
                type="checkbox"
                id="remember"
                name="remember"
            >

            <label
                for="remember"
                style="margin: 0; font-weight: normal;"
            >
                Ghi nhớ đăng nhập
            </label>

        </div>

        <button type="submit">
            Đăng nhập
        </button>

    </form>

    <div class="register-link">

        Chưa có tài khoản?

        <a href="{{ route('register') }}">
            Đăng ký ngay
        </a>

    </div>

    <a
        class="home-link"
        href="{{ route('home') }}"
    >
        ← Quay lại trang chủ
    </a>

</div>

</body>
</html>