<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupon_usages', function (Blueprint $table) {

            $table->id();

            $table->foreignId('coupon_id')
                ->constrained('coupons')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('order_id')
                ->nullable()
                ->constrained('orders')
                ->nullOnDelete();

            $table->decimal(
                'discount_amount',
                12,
                2
            )->default(0);

            $table->timestamp(
                'used_at'
            )->nullable();

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | MỖI USER CHỈ DÙNG 1 VOUCHER 1 LẦN
            |--------------------------------------------------------------------------
            |
            | Ví dụ:
            | user 5 + GREEN10 chỉ được xuất hiện một lần.
            |
            */

            $table->unique([
                'coupon_id',
                'user_id',
            ]);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists(
            'coupon_usages'
        );
    }
};