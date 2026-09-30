<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | THÔNG TIN TÀI KHOẢN
    |--------------------------------------------------------------------------
    */

    public function profile()
    {
        $user = Auth::user();

        return view(
            'account.profile',
            compact('user')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CẬP NHẬT THÔNG TIN
    |--------------------------------------------------------------------------
    */

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate(
            [
                'name' =>
                    'required|string|max:255',

                'email' => [
                    'required',
                    'email',

                    Rule::unique(
                        'users',
                        'email'
                    )->ignore($user->id),
                ],
            ],
            [
                'name.required' =>
                    'Vui lòng nhập họ tên.',

                'email.required' =>
                    'Vui lòng nhập email.',

                'email.email' =>
                    'Email không đúng định dạng.',

                'email.unique' =>
                    'Email này đã được sử dụng.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA CÓ ĐỔI EMAIL KHÔNG
        |--------------------------------------------------------------------------
        */

        $emailChanged =
            $validated['email']
            !==
            $user->email;


        $user->name =
            $validated['name'];

        $user->email =
            $validated['email'];


        /*
        |--------------------------------------------------------------------------
        | ĐỔI EMAIL -> CẦN XÁC THỰC LẠI
        |--------------------------------------------------------------------------
        */

        if ($emailChanged) {

            $user->email_verified_at = null;
        }


        $user->save();


        /*
        |--------------------------------------------------------------------------
        | GỬI EMAIL XÁC THỰC LẠI
        |--------------------------------------------------------------------------
        */

        if ($emailChanged) {

            $user->sendEmailVerificationNotification();
        }


        return back()->with(
            'success',
            $emailChanged
                ? 'Cập nhật thành công. Email mới cần được xác thực lại.'
                : 'Cập nhật thông tin tài khoản thành công.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM ĐỔI MẬT KHẨU
    |--------------------------------------------------------------------------
    */

    public function showChangePassword()
    {
        return view(
            'account.change-password'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | XỬ LÝ ĐỔI MẬT KHẨU
    |--------------------------------------------------------------------------
    */

    public function changePassword(
        Request $request
    ) {
        $request->validate(
            [
                'current_password' =>
                    'required',

                'password' =>
                    'required|string|min:6|confirmed',
            ],
            [
                'current_password.required' =>
                    'Vui lòng nhập mật khẩu hiện tại.',

                'password.required' =>
                    'Vui lòng nhập mật khẩu mới.',

                'password.min' =>
                    'Mật khẩu mới phải có ít nhất 6 ký tự.',

                'password.confirmed' =>
                    'Xác nhận mật khẩu mới không khớp.',
            ]
        );


        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | KIỂM TRA MẬT KHẨU HIỆN TẠI
        |--------------------------------------------------------------------------
        */

        if (
            !Hash::check(
                $request->current_password,
                $user->password
            )
        ) {

            return back()->with(
                'error',
                'Mật khẩu hiện tại không chính xác.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | KHÔNG CHO ĐẶT LẠI MẬT KHẨU CŨ
        |--------------------------------------------------------------------------
        */

        if (
            Hash::check(
                $request->password,
                $user->password
            )
        ) {

            return back()->with(
                'error',
                'Mật khẩu mới không được trùng với mật khẩu hiện tại.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CẬP NHẬT
        |--------------------------------------------------------------------------
        */

        $user->password =
            Hash::make(
                $request->password
            );

        $user->save();


        return back()->with(
            'success',
            'Đổi mật khẩu thành công.'
        );
    }
}