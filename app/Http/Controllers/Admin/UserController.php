<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DANH SÁCH NGƯỜI DÙNG
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $users = User::orderByDesc('id')->get();

        return view(
            'admin.users.index',
            compact('users')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM THÊM NGƯỜI DÙNG
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('admin.users.create');
    }


    /*
    |--------------------------------------------------------------------------
    | LƯU NGƯỜI DÙNG MỚI
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' =>
                    'required|string|max:255',

                'email' =>
                    'required|email|unique:users,email',

                'password' =>
                    'required|string|min:6|confirmed',

                'role' =>
                    'required|in:admin,user',
            ],
            [
                'name.required' =>
                    'Vui lòng nhập tên người dùng.',

                'email.required' =>
                    'Vui lòng nhập email.',

                'email.email' =>
                    'Email không đúng định dạng.',

                'email.unique' =>
                    'Email này đã tồn tại.',

                'password.required' =>
                    'Vui lòng nhập mật khẩu.',

                'password.min' =>
                    'Mật khẩu phải có ít nhất 6 ký tự.',

                'password.confirmed' =>
                    'Xác nhận mật khẩu không khớp.',

                'role.required' =>
                    'Vui lòng chọn vai trò.',
            ]
        );


        User::create([
            'name' =>
                $validated['name'],

            'email' =>
                $validated['email'],

            'password' =>
                Hash::make(
                    $validated['password']
                ),

            'role' =>
                $validated['role'],
        ]);


        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'Thêm người dùng thành công.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | XEM CHI TIẾT
    |--------------------------------------------------------------------------
    */

    public function show(User $user)
    {
        return view(
            'admin.users.show',
            compact('user')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM CHỈNH SỬA
    |--------------------------------------------------------------------------
    */

    public function edit(User $user)
    {
        return view(
            'admin.users.edit',
            compact('user')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CẬP NHẬT
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        User $user
    ) {
        $validated = $request->validate(
            [
                'name' =>
                    'required|string|max:255',

                'email' =>
                    'required|email|unique:users,email,'
                    . $user->id,

                'role' =>
                    'required|in:admin,user',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | Không cho Admin tự hạ quyền chính mình
        |--------------------------------------------------------------------------
        */

        if (
            auth()->id() === $user->id
            &&
            $validated['role'] !== 'admin'
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Bạn không thể tự đổi tài khoản Admin của mình thành User.'
                );
        }


        $user->update([
            'name' =>
                $validated['name'],

            'email' =>
                $validated['email'],

            'role' =>
                $validated['role'],
        ]);


        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'Cập nhật người dùng thành công.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | XÓA NGƯỜI DÙNG
    |--------------------------------------------------------------------------
    */

    public function destroy(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Không cho Admin tự xóa tài khoản đang đăng nhập
        |--------------------------------------------------------------------------
        */

        if (auth()->id() === $user->id) {

            return back()->with(
                'error',
                'Bạn không thể tự xóa tài khoản đang đăng nhập.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Nếu user đã có đơn hàng thì không xóa
        |--------------------------------------------------------------------------
        */

        if ($user->orders()->exists()) {

            return back()->with(
                'error',
                'Không thể xóa người dùng này vì họ đã có đơn hàng.'
            );
        }


        $user->delete();


        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'Xóa người dùng thành công.'
            );
    }
}