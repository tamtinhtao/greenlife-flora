<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {

            $table->id();

            $table->string('code')
                ->unique();

            $table->string('name');

            /*
            |--------------------------------------------------------------------------
            | percent = giảm theo %
            | fixed   = giảm số tiền cố định
            |--------------------------------------------------------------------------
            */
            $table->enum(
                'type',
                [
                    'percent',
                    'fixed'
                ]
            );

            $table->decimal(
                'value',
                12,
                2
            );

            /*
            |--------------------------------------------------------------------------
            | Giá trị đơn tối thiểu
            |--------------------------------------------------------------------------
            */
            $table->decimal(
                'min_order_amount',
                12,
                2
            )->default(0);


            /*
            |--------------------------------------------------------------------------
            | Mức giảm tối đa
            | Chủ yếu dùng với voucher %
            |--------------------------------------------------------------------------
            */
            $table->decimal(
                'max_discount_amount',
                12,
                2
            )->nullable();


            /*
            |--------------------------------------------------------------------------
            | Tổng số lượt được sử dụng
            | NULL = không giới hạn
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger(
                'usage_limit'
            )->nullable();


            /*
            |--------------------------------------------------------------------------
            | Số lượt đã sử dụng
            |--------------------------------------------------------------------------
            */
            $table->unsignedInteger(
                'used_count'
            )->default(0);


            /*
            |--------------------------------------------------------------------------
            | Thời gian hiệu lực
            |--------------------------------------------------------------------------
            */
            $table->dateTime(
                'starts_at'
            )->nullable();

            $table->dateTime(
                'expires_at'
            )->nullable();


            /*
            |--------------------------------------------------------------------------
            | Trạng thái
            |--------------------------------------------------------------------------
            */
            $table->enum(
                'status',
                [
                    'active',
                    'inactive'
                ]
            )->default('active');


            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};