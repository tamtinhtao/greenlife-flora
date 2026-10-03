<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DANH SÁCH VOUCHER
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $coupons = Coupon::latest()
            ->paginate(10);

        return view(
            'admin.coupons.index',
            compact('coupons')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM THÊM
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        return view(
            'admin.coupons.create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LƯU VOUCHER
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'code' =>
                    'required|string|max:50|unique:coupons,code',

                'name' =>
                    'required|string|max:150',

                'type' =>
                    'required|in:percent,fixed',

                'value' =>
                    'required|numeric|min:0.01',

                'min_order_amount' =>
                    'nullable|numeric|min:0',

                'max_discount_amount' =>
                    'nullable|numeric|min:0',

                'usage_limit' =>
                    'nullable|integer|min:1',

                'starts_at' =>
                    'nullable|date',

                'expires_at' =>
                    'nullable|date|after_or_equal:starts_at',

                'status' =>
                    'required|in:active,inactive',
            ],
            [
                'code.required' =>
                    'Vui lòng nhập mã voucher.',

                'code.unique' =>
                    'Mã voucher này đã tồn tại.',

                'name.required' =>
                    'Vui lòng nhập tên chương trình.',

                'type.required' =>
                    'Vui lòng chọn loại giảm giá.',

                'value.required' =>
                    'Vui lòng nhập giá trị giảm.',

                'expires_at.after_or_equal' =>
                    'Ngày hết hạn phải sau ngày bắt đầu.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | % KHÔNG ĐƯỢC > 100
        |--------------------------------------------------------------------------
        */

        if (
            $data['type'] === 'percent'
            &&
            $data['value'] > 100
        ) {

            return back()
                ->withErrors([
                    'value' =>
                        'Voucher phần trăm không được vượt quá 100%.'
                ])
                ->withInput();
        }


        /*
        |--------------------------------------------------------------------------
        | CHUẨN HÓA CODE
        |--------------------------------------------------------------------------
        */

        $data['code'] =
            strtoupper(
                trim($data['code'])
            );


        $data['min_order_amount'] =
            $data['min_order_amount']
            ?? 0;


        Coupon::create($data);


        return redirect()
            ->route(
                'admin.coupons.index'
            )
            ->with(
                'success',
                'Tạo voucher thành công.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FORM SỬA
    |--------------------------------------------------------------------------
    */
    public function edit(Coupon $coupon)
    {
        return view(
            'admin.coupons.edit',
            compact('coupon')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CẬP NHẬT
    |--------------------------------------------------------------------------
    */
    public function update(
        Request $request,
        Coupon $coupon
    ) {

        $data = $request->validate(
            [
                'code' =>
                    'required|string|max:50|unique:coupons,code,'
                    . $coupon->id,

                'name' =>
                    'required|string|max:150',

                'type' =>
                    'required|in:percent,fixed',

                'value' =>
                    'required|numeric|min:0.01',

                'min_order_amount' =>
                    'nullable|numeric|min:0',

                'max_discount_amount' =>
                    'nullable|numeric|min:0',

                'usage_limit' =>
                    'nullable|integer|min:1',

                'starts_at' =>
                    'nullable|date',

                'expires_at' =>
                    'nullable|date|after_or_equal:starts_at',

                'status' =>
                    'required|in:active,inactive',
            ]
        );


        if (
            $data['type'] === 'percent'
            &&
            $data['value'] > 100
        ) {

            return back()
                ->withErrors([
                    'value' =>
                        'Voucher phần trăm không được vượt quá 100%.'
                ])
                ->withInput();
        }


        $data['code'] =
            strtoupper(
                trim($data['code'])
            );


        $data['min_order_amount'] =
            $data['min_order_amount']
            ?? 0;


        $coupon->update($data);


        return redirect()
            ->route(
                'admin.coupons.index'
            )
            ->with(
                'success',
                'Cập nhật voucher thành công.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | XÓA
    |--------------------------------------------------------------------------
    */
    public function destroy(Coupon $coupon)
    {
        /*
        |--------------------------------------------------------------------------
        | Voucher đã được dùng thì không xóa
        |--------------------------------------------------------------------------
        */

        if ($coupon->used_count > 0) {

            return back()->with(
                'error',
                'Voucher đã được sử dụng nên không thể xóa. Bạn có thể chuyển sang trạng thái ngừng hoạt động.'
            );
        }


        $coupon->delete();


        return redirect()
            ->route(
                'admin.coupons.index'
            )
            ->with(
                'success',
                'Đã xóa voucher.'
            );
    }
}