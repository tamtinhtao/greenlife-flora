<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Xác thực email - GreenLife Flora</title>

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
            font-size: 42px;
            margin-bottom: 10px;
        }

        h1 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 15px;
        }

        .description {
            text-align: center;
            color: #666;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        .email {
            font-weight: bold;
            color: #2e7d32;
        }

        .success {
            margin-bottom: 20px;
            padding: 12px;
            background: #e8f5e9;
            color: #2e7d32;
            border-radius: 8px;
            line-height: 1.5;
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
            margin-bottom: 12px;
        }

        button:hover {
            background: #1b5e20;
        }

        .logout-button {
            background: #eeeeee;
            color: #555;
        }

        .logout-button:hover {
            background: #dddddd;
        }

        .home-link {
            display: block;
            text-align: center;
            margin-top: 12px;
            text-decoration: none;
            color: #777;
        }
    </style>
</head>

<body>

<div class="auth-container">

    <div class="logo">
        ✉️
    </div>

    <h1>Xác thực email</h1>

    <div class="description">
        Chúng tôi đã gửi email xác thực đến
        <br>

        <span class="email">
            {{ auth()->user()->email }}
        </span>

        <br><br>

        Vui lòng mở Gmail và nhấn vào liên kết xác thực
        để hoàn tất đăng ký tài khoản.
    </div>

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('verification.send') }}"
    >
        @csrf

        <button type="submit">
            Gửi lại email xác thực
        </button>
    </form>

    <form
        method="POST"
        action="{{ route('logout') }}"
    >
        @csrf

        <button
            type="submit"
            class="logout-button"
        >
            Đăng xuất
        </button>
    </form>

    <a
        href="{{ route('home') }}"
        class="home-link"
    >
        ← Quay lại trang chủ
    </a>

</div>

</body>
</html>