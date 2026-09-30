<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Hiển thị trang đăng nhập
    |--------------------------------------------------------------------------
    */
    public function showLogin()
    {
        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | Xử lý đăng nhập
    |--------------------------------------------------------------------------
    */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
            ],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {

            return back()
                ->withErrors([
                    'email' => 'Email hoặc mật khẩu không chính xác.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();
/*
|--------------------------------------------------------------------------
| Kiểm tra xác thực email
|--------------------------------------------------------------------------
*/
if ($user->role === 'user' && !$user->hasVerifiedEmail()) {

    return redirect()
        ->route('verification.notice')
        ->with(
            'success',
            'Tài khoản chưa xác thực email. Vui lòng kiểm tra hộp thư.'
        );
}
        /*
|--------------------------------------------------------------------------
| ĐIỀU HƯỚNG SAU ĐĂNG NHẬP
|--------------------------------------------------------------------------
*/

if (Auth::user()->role === 'admin') {

    return redirect()
        ->route('admin.dashboard')
        ->with(
            'success',
            'Đăng nhập Admin thành công.'
        );
}


/*
|--------------------------------------------------------------------------
| USER
|--------------------------------------------------------------------------
*/

return redirect()
    ->route('home')
    ->with(
        'success',
        'Đăng nhập thành công.'
    );
    }

    /*
    |--------------------------------------------------------------------------
    | Hiển thị trang đăng ký
    |--------------------------------------------------------------------------
    */
    public function showRegister()
    {
        return view('auth.register');
    }

    /*
    |--------------------------------------------------------------------------
    | Xử lý đăng ký
    |--------------------------------------------------------------------------
    */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:6',
                'confirmed',
            ],

        ], [
            'name.required' => 'Vui lòng nhập họ tên.',

            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email này đã được sử dụng.',

            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $user = User::create([
    'name' => $validated['name'],
    'email' => $validated['email'],
    'password' => Hash::make($validated['password']),

    // Đăng ký trên website luôn luôn là USER
    'role' => 'user',
]);

// Gửi email xác thực
$user->sendEmailVerificationNotification();
Auth::login($user);

return redirect()
    ->route('verification.notice')
    ->with(
        'success',
        'Đăng ký thành công. Vui lòng kiểm tra Gmail để xác thực tài khoản.'
    );
    }

    /*
    |--------------------------------------------------------------------------
    | Đăng xuất
    |--------------------------------------------------------------------------
    */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('home')
            ->with('success', 'Đăng xuất thành công.');
    }
}