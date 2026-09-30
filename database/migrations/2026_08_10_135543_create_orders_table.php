<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_name');     // Tên người nhận
            $table->string('customer_email');    // Email
            $table->string('customer_phone');    // Số điện thoại
            $table->string('shipping_address');  // Địa chỉ giao
            $table->decimal('total_price', 12, 2); // Tổng tiền đơn hàng
            $table->string('status')->default('pending'); // Trạng thái: pending, processing, completed, cancelled
            $table->text('note')->nullable();    // Ghi chú giao hàng
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};