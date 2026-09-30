<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {

            $table->id();

            // Giao dịch này thuộc đơn hàng nào
            $table->foreignId('order_id')
                ->constrained()
                ->cascadeOnDelete();

            // Cổng thanh toán:
            // momo, vnpay, paypal, stripe...
            $table->string('gateway');

            // Mã đơn do cổng thanh toán tạo
            $table->string('gateway_order_id')
                ->nullable()
                ->index();

            // Mã giao dịch do cổng thanh toán trả về
            $table->string('transaction_id')
                ->nullable()
                ->index();

            // Số tiền thanh toán
            $table->decimal('amount', 15, 2);

            // pending, completed, failed, cancelled...
            $table->string('status')
                ->default('pending');

            // Mã kết quả từ cổng thanh toán
            $table->integer('result_code')
                ->nullable();

            // Thông báo từ cổng thanh toán
            $table->string('message')
                ->nullable();

            // Dữ liệu gửi sang cổng thanh toán
            $table->json('request_payload')
                ->nullable();

            // Dữ liệu cổng thanh toán trả về
            $table->json('response_payload')
                ->nullable();

            // Thời gian thanh toán thành công
            $table->timestamp('paid_at')
                ->nullable();

            $table->timestamps();


            // Một gateway_order_id chỉ được dùng
            // một lần trong cùng gateway
            $table->unique([
                'gateway',
                'gateway_order_id'
            ]);


            // Tối ưu truy vấn theo đơn hàng + trạng thái
            $table->index([
                'order_id',
                'status'
            ]);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};