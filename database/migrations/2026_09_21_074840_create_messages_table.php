<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('messages', function (Blueprint $table) {

        $table->id();

        // Người gửi
        $table->foreignId('sender_id')
            ->constrained('users')
            ->cascadeOnDelete();

        // Người nhận
        $table->foreignId('receiver_id')
            ->constrained('users')
            ->cascadeOnDelete();

        // Nội dung tin nhắn
        $table->text('content');

        // Đã đọc hay chưa
        $table->boolean('is_read')
            ->default(false);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
