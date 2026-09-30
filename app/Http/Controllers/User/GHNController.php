<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\GHNService;
use Illuminate\Http\Request;

class GHNController extends Controller
{

    // ================================
    // TỈNH / THÀNH
    // ================================
    public function getProvinces(
        GHNService $ghn
    )
    {
        return response()->json(
            $ghn->getProvinces()
        );
    }


    // ================================
    // QUẬN / HUYỆN
    // ================================
    public function getDistricts(
        int $provinceId,
        GHNService $ghn
    )
    {
        return response()->json(
            $ghn->getDistricts(
                $provinceId
            )
        );
    }


    // ================================
    // PHƯỜNG / XÃ
    // ================================
    public function getWards(
        int $districtId,
        GHNService $ghn
    )
    {
        return response()->json(
            $ghn->getWards(
                $districtId
            )
        );
    }


    // ================================
    // TÍNH PHÍ SHIP
    // ================================
    public function getShippingFee(
        Request $request,
        GHNService $ghn
    )
    {

        $request->validate([

            'to_district_id' =>
                'required|integer',

            'to_ward_code' =>
                'required|string',

        ]);


        /*
        |--------------------------------------------------------------------------
        | LẤY GIỎ HÀNG
        |--------------------------------------------------------------------------
        */

        $cart =
            session('cart', []);


        /*
        |--------------------------------------------------------------------------
        | TÍNH TRỌNG LƯỢNG
        |--------------------------------------------------------------------------
        */

        $weight =
            collect($cart)->sum(

                fn ($item) =>

                    (int) (
                        $item['weight']
                        ?? config(
                            'services.ghn.default_weight',
                            200
                        )
                    )

                    * (int)
                        $item['quantity']
            );


        /*
        |--------------------------------------------------------------------------
        | GỌI GHN TÍNH PHÍ
        |--------------------------------------------------------------------------
        */

        return response()->json(

            $ghn->calculateFee(

                array_merge(

                    [

                        'from_district_id' =>
                            (int)
                            config(
                                'services.ghn.from_district_id'
                            ),

                        'to_district_id' =>
                            (int)
                            $request->to_district_id,

                        'to_ward_code' =>
                            (string)
                            $request->to_ward_code,

                    ],

                    $ghn->packageParameters(
                        $weight
                    )
                )
            )
        );
    }
}