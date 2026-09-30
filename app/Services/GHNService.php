<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GHNService
{
    protected string $baseUrl;
    protected string $token;
    protected int $shopId;

    public function __construct()
    {
        $this->baseUrl = config('services.ghn.base_url');
        $this->token = config('services.ghn.token') ?? '';
        $this->shopId = (int) config('services.ghn.shop_id', 0);
    }


    protected function client()
    {
        return Http::baseUrl($this->baseUrl)

            ->withOptions([
                'verify' => filter_var(
                    config('services.ghn.verify_ssl', true),
                    FILTER_VALIDATE_BOOLEAN
                ),
            ])

            ->acceptJson()

            ->timeout(15)

            ->withHeaders([
                'Token' => $this->token,
                'ShopId' => $this->shopId,
                'Content-Type' => 'application/json',
            ]);
    }


    // ================================
    // LẤY TỈNH / THÀNH
    // ================================
    public function getProvinces(): array
    {
        return $this->get('/master-data/province');
    }


    // ================================
    // LẤY QUẬN / HUYỆN
    // ================================
    public function getDistricts(int $provinceId): array
    {
        return $this->get(
            '/master-data/district',
            [
                'province_id' => $provinceId,
            ]
        );
    }


    // ================================
    // LẤY PHƯỜNG / XÃ
    // ================================
    public function getWards(int $districtId): array
    {
        return $this->get(
            '/master-data/ward',
            [
                'district_id' => $districtId,
            ]
        );
    }


    // ================================
    // TÍNH PHÍ GIAO HÀNG
    // ================================
    public function calculateFee(array $params): array
    {
        return $this->post(
            '/v2/shipping-order/fee',
            array_merge(
                [
                    'shop_id' => $this->shopId,
                ],
                $params
            )
        );
    }


    // ================================
    // TẠO VẬN ĐƠN
    // ================================
    public function createOrder(array $orderData): array
    {
        return $this->post(
            '/v2/shipping-order/create',
            array_merge(
                [
                    'shop_id' => $this->shopId,
                ],
                $orderData
            )
        );
    }


    // ================================
    // HỦY VẬN ĐƠN
    // ================================
    public function cancelOrder(array $orderCodes): array
    {
        return $this->post(
            '/v2/switch-status/cancel',
            [
                'order_codes' => $orderCodes,
                'shop_id' => $this->shopId,
            ]
        );
    }
    /*
|--------------------------------------------------------------------------
| LẤY THÔNG TIN / TRẠNG THÁI VẬN ĐƠN GHN
|--------------------------------------------------------------------------
|
| Dùng mã vận đơn GHN để lấy thông tin hiện tại:
| ready_to_pick, picking, delivering, delivered...
|
*/

public function getOrderInfo(string $orderCode): array
{
    return $this->get(
        '/v2/shipping-order/detail',
        [
            'order_code' => $orderCode,
        ]
    );
}


    /*
    |--------------------------------------------------------------------------
    | THÔNG SỐ KIỆN HÀNG
    |--------------------------------------------------------------------------
    |
    | Trong tài liệu thầy, GHNController gọi:
    |
    | $ghn->packageParameters($weight)
    |
    | nhưng phần GHNService thầy gửi chưa có method này.
    | Nếu không thêm thì Laravel sẽ báo:
    | Call to undefined method GHNService::packageParameters()
    |
    */

    public function packageParameters(int $weight): array
    {
        return [
            'weight' => $weight > 0 ? $weight : 300,

            'length' => 15,

            'width' => 15,

            'height' => 10,

            'service_type_id' => 2,
        ];
    }
    /*
|--------------------------------------------------------------------------
| TRỌNG LƯỢNG MẶC ĐỊNH CỦA 1 SẢN PHẨM
|--------------------------------------------------------------------------
*/

public function productWeight(): int
{
    return (int) config(
        'services.ghn.default_weight',
        200
    );
}


/*
|--------------------------------------------------------------------------
| THÔNG SỐ KIỆN HÀNG GỬI CHO GHN
|--------------------------------------------------------------------------
*/

    // ================================
    // GET REQUEST
    // ================================
    protected function get(string $uri, array $query = []): array
{
    try {

        $response = $this->client()->get($uri, $query);

        if (!$response->successful()) {

            $body = $response->json();

            Log::warning('GHN GET request failed', [
                'uri' => $uri,
                'status' => $response->status(),
                'body' => $body,
            ]);

            return [
                'code' => $response->status(),
                'message' => $body['message']
                    ?? 'GHN API request failed.',
                'data' => $body['data']
                    ?? null,
                'code_message' => $body['code_message']
                    ?? null,
            ];
        }

        return $response->json()
            ?? [
                'code' => -1,
                'message' => 'GHN returned an empty response.',
            ];

    } catch (ConnectionException $exception) {

        Log::error('Unable to connect to GHN', [
            'uri' => $uri,
            'error' => $exception->getMessage(),
        ]);

        return [
            'code' => -1,
            'message' => 'Unable to connect to GHN.',
        ];
    }
}


protected function post(string $uri, array $payload): array
{
    try {

        $response = $this->client()->post($uri, $payload);

        if (!$response->successful()) {

            $body = $response->json();

            Log::warning('GHN POST request failed', [
                'uri' => $uri,
                'status' => $response->status(),
                'body' => $body,
            ]);

            return [
                'code' => $response->status(),
                'message' => $body['message']
                    ?? 'GHN API request failed.',
                'data' => $body['data']
                    ?? null,
                'code_message' => $body['code_message']
                    ?? null,
            ];
        }

        return $response->json()
            ?? [
                'code' => -1,
                'message' => 'GHN returned an empty response.',
            ];

    } catch (ConnectionException $exception) {

        Log::error('Unable to connect to GHN', [
            'uri' => $uri,
            'error' => $exception->getMessage(),
        ]);

        return [
            'code' => -1,
            'message' => 'Unable to connect to GHN.',
        ];
    }
}


}