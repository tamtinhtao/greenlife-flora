<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [

        // Thông tin đơn hàng hiện tại
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',

        'total_price',
        'status',
        'note',
        'payment_method',

        // =========================
        // GHN SHIPPING
        // =========================

        // Trạng thái giao hàng
        'shipping_status',

        // Mã vận đơn GHN
        'ghn_order_code',

        // Phí vận chuyển GHN
        'ghn_total_fee',

        // Mã quận/huyện người nhận
        'to_district_id',

        // Mã phường/xã người nhận
        'to_ward_code',
    ];

    // Một đơn hàng có thể có nhiều lần thanh toán
public function paymentTransactions()
{
    return $this->hasMany(PaymentTransaction::class);
}


    // Hàm này thêm để giống tài liệu của thầy
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    // Một đơn hàng có nhiều chi tiết sản phẩm
public function orderItems()
{
    return $this->hasMany(OrderItem::class);
}
/*
|--------------------------------------------------------------------------
| USER ĐẶT ĐƠN
|--------------------------------------------------------------------------
*/

public function user()
{
    return $this->belongsTo(User::class);
}
}