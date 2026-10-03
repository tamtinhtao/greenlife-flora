<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;


    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'starts_at',
        'expires_at',
        'status',
    ];


    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA VOUCHER CÒN HỢP LỆ
    |--------------------------------------------------------------------------
    */
    public function isValid(float $subtotal): bool
    {
        if ($this->status !== 'active') {

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Chưa đến ngày bắt đầu
        |--------------------------------------------------------------------------
        */
        if (
            $this->starts_at
            &&
            now()->lt($this->starts_at)
        ) {

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Đã hết hạn
        |--------------------------------------------------------------------------
        */
        if (
            $this->expires_at
            &&
            now()->gt($this->expires_at)
        ) {

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Hết số lượt sử dụng
        |--------------------------------------------------------------------------
        */
        if (
            $this->usage_limit !== null
            &&
            $this->used_count >= $this->usage_limit
        ) {

            return false;
        }


        /*
        |--------------------------------------------------------------------------
        | Đơn chưa đạt giá trị tối thiểu
        |--------------------------------------------------------------------------
        */
        if (
            $subtotal
            <
            $this->min_order_amount
        ) {

            return false;
        }


        return true;
    }


    /*
    |--------------------------------------------------------------------------
    | TÍNH SỐ TIỀN ĐƯỢC GIẢM
    |--------------------------------------------------------------------------
    */
    public function calculateDiscount(
        float $subtotal
    ): float {

        if (!$this->isValid($subtotal)) {

            return 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Giảm %
        |--------------------------------------------------------------------------
        */
        if ($this->type === 'percent') {

            $discount =
                $subtotal
                *
                ($this->value / 100);


            /*
            |--------------------------------------------------------------------------
            | Có giới hạn giảm tối đa
            |--------------------------------------------------------------------------
            */
            if (
                $this->max_discount_amount !== null
            ) {

                $discount = min(
                    $discount,
                    $this->max_discount_amount
                );
            }

        } else {

            /*
            |--------------------------------------------------------------------------
            | Giảm tiền cố định
            |--------------------------------------------------------------------------
            */

            $discount = $this->value;
        }


        /*
        |--------------------------------------------------------------------------
        | Không được giảm vượt quá tiền hàng
        |--------------------------------------------------------------------------
        */
        return min(
            $discount,
            $subtotal
        );
    }
}