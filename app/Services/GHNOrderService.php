<?php

namespace App\Services;

use App\Models\Order;

class GHNOrderService
{
    public function __construct(
        private GHNService $ghn
    )
    {
    }


    public function create(
        Order $order,
        bool $isPaid = false
    ): array
    {

        $items = [];

        $weight = 0;


        /*
        |--------------------------------------------------------------------------
        | DUYỆT CÁC SẢN PHẨM TRONG ĐƠN
        |--------------------------------------------------------------------------
        */

        foreach ($order->items as $item) {

            /*
            | Nếu Product chưa có cột weight
            | thì mặc định 200 gram / sản phẩm
            */

            $itemWeight =
                (int) (
                    $item->product->weight
                    ?? config(
                        'services.ghn.default_weight',
                        200
                    )
                );


            /*
            | Tổng trọng lượng
            */

            $weight +=
                $itemWeight
                * (int) $item->quantity;


            /*
            | Chuyển sản phẩm sang format GHN
            */

            $items[] = [

                'name' =>
                    $item->product->name
                    ?? 'Sản phẩm',

                'quantity' =>
                    (int) $item->quantity,

                'price' =>
                    (int) $item->price,

                'weight' =>
                    $itemWeight,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | TẠO PAYLOAD GỬI GHN
        |--------------------------------------------------------------------------
        */

        return $this->ghn->createOrder([

            'payment_type_id' => 1,


            'note' =>
                'Đơn hàng #' . $order->id,


            'required_note' =>
                'KHONGCHOXEMHANG',


            /*
            |--------------------------------------------------------------------------
            | PROJECT CỦA BẠN DÙNG customer_*
            |--------------------------------------------------------------------------
            */

            'to_name' =>
                $order->customer_name,

            'to_phone' =>
                $order->customer_phone,

            'to_address' =>
                $order->shipping_address,


            /*
            |--------------------------------------------------------------------------
            | ĐỊA CHỈ GHN
            |--------------------------------------------------------------------------
            */

            'to_ward_code' =>
                (string)
                $order->to_ward_code,

            'to_district_id' =>
                (int)
                $order->to_district_id,


            /*
            |--------------------------------------------------------------------------
            | COD
            |--------------------------------------------------------------------------
            */

            'cod_amount' =>
                $isPaid
                    ? 0
                    : (int)
                        $order->total_price,


            /*
            |--------------------------------------------------------------------------
            | KÍCH THƯỚC GÓI HÀNG
            |--------------------------------------------------------------------------
            */

            'weight' =>
                $weight > 0
                    ? $weight
                    : 300,

            'length' => 15,

            'width' => 15,

            'height' => 10,


            'service_type_id' => 2,


            /*
            |--------------------------------------------------------------------------
            | SẢN PHẨM
            |--------------------------------------------------------------------------
            */

            'items' => $items,
        ]);
    }
}