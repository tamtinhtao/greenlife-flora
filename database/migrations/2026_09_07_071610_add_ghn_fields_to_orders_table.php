<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            // Trạng thái giao hàng GHN
            $table->string('shipping_status')
                ->default('not_shipped')
                ->after('status');

            // Mã vận đơn GHN
            $table->string('ghn_order_code')
                ->nullable()
                ->index()
                ->after('shipping_status');

            // Phí vận chuyển GHN
            $table->integer('ghn_total_fee')
                ->default(0)
                ->after('ghn_order_code');

            // Quận/Huyện của người nhận
            $table->integer('to_district_id')
                ->nullable()
                ->after('ghn_total_fee');

            // Phường/Xã của người nhận
            $table->string('to_ward_code')
                ->nullable()
                ->after('to_district_id');
        });
    }


    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {

            $table->dropIndex(['ghn_order_code']);

            $table->dropColumn([
                'shipping_status',
                'ghn_order_code',
                'ghn_total_fee',
                'to_district_id',
                'to_ward_code',
            ]);
        });
    }
};