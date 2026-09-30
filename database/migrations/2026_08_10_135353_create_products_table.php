<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Khóa ngoại liên kết với bảng categories
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); 
            
            $table->string('name');              // Tên cây (Ví dụ: Cây Kim Tiền)
            $table->string('slug')->unique();     // Đường dẫn (cay-kim-tien)
            $table->decimal('price', 12, 2);     // Giá bán
            $table->decimal('sale_price', 12, 2)->nullable(); // Giá khuyến mãi
            $table->integer('stock')->default(0); // Số lượng tồn kho
            $table->string('image')->nullable();  // Đường dẫn ảnh cây
            
            $table->text('description')->nullable(); // Mô tả chi tiết
            
            // Đặc thù riêng cho Cây cảnh / Hoa:
            $table->text('care_guide')->nullable();   // Hướng dẫn chăm sóc
            $table->string('sunlight')->nullable();   // Nhu cầu ánh sáng (Nắng nhẹ, Mát...)
            $table->string('water')->nullable();      // Tần suất tưới (2 lần/tuần...)
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};