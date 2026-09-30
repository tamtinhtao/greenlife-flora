<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class MomoService
{
    /*
    |--------------------------------------------------------------------------
    | TẠO YÊU CẦU THANH TOÁN MOMO
    |--------------------------------------------------------------------------
    */

    public function createPayment(
        Order $order,
        PaymentTransaction $transaction
    ): array
    {
        $endpoint = config(
            'services.momo.endpoint',
            'https://test-payment.momo.vn/v2/gateway/api/create'
        );

        $partnerCode = config(
            'services.momo.partner_code',
            env('MOMO_PARTNER_CODE', '')
        );

        $accessKey = config(
            'services.momo.access_key',
            env('MOMO_ACCESS_KEY', '')
        );

        $secretKey = config(
            'services.momo.secret_key',
            env('MOMO_SECRET_KEY', '')
        );


        /*
        |--------------------------------------------------------------------------
        | THÔNG TIN ĐƠN HÀNG
        |--------------------------------------------------------------------------
        */

        $orderInfo =
            'Thanh toan don hang #' . $order->id;

        $amount =
            (string) ((int) $order->total_price);

        $orderId =
            $order->id
            . '-'
            . $transaction->id
            . '-'
            . time();


        /*
        |--------------------------------------------------------------------------
        | URL MOMO TRẢ KẾT QUẢ
        |--------------------------------------------------------------------------
        */

        $redirectUrl =
            config('services.momo.redirect_url')
            ?: route('user.payment.momo.callback');

        $ipnUrl =
            config('services.momo.ipn_url')
            ?: route('payment.momo.ipn');


        /*
        |--------------------------------------------------------------------------
        | THÔNG TIN REQUEST
        |--------------------------------------------------------------------------
        */

        $extraData =
            (string) $order->id;

        $requestId =
            (string) time();

        $requestType =
             'payWithATM';

        /*
        |--------------------------------------------------------------------------
        | TẠO CHỮ KÝ
        |--------------------------------------------------------------------------
        */

        $rawHash =
            'accessKey=' . $accessKey .
            '&amount=' . $amount .
            '&extraData=' . $extraData .
            '&ipnUrl=' . $ipnUrl .
            '&orderId=' . $orderId .
            '&orderInfo=' . $orderInfo .
            '&partnerCode=' . $partnerCode .
            '&redirectUrl=' . $redirectUrl .
            '&requestId=' . $requestId .
            '&requestType=' . $requestType;


        /*
        |--------------------------------------------------------------------------
        | PAYLOAD GỬI SANG MOMO
        |--------------------------------------------------------------------------
        */

        $data = [

            'partnerCode' =>
                $partnerCode,

            'partnerName' =>
                'GreenLife Flora',

            'storeId' =>
                'MomoStore',

            'requestId' =>
                $requestId,

            'amount' =>
                $amount,

            'orderId' =>
                $orderId,

            'orderInfo' =>
                $orderInfo,

            'redirectUrl' =>
                $redirectUrl,

            'ipnUrl' =>
                $ipnUrl,

            'lang' =>
                'vi',

            'extraData' =>
                $extraData,

            'requestType' =>
                $requestType,

            'signature' =>
                hash_hmac(
                    'sha256',
                    $rawHash,
                    $secretKey
                ),
        ];


        /*
        |--------------------------------------------------------------------------
        | LƯU REQUEST VÀO PAYMENT_TRANSACTIONS
        |--------------------------------------------------------------------------
        */

        $transaction->update([

            'gateway_order_id' =>
                $orderId,

            'request_payload' =>
                $data,
        ]);


        /*
        |--------------------------------------------------------------------------
        | GỬI REQUEST SANG MOMO
        |--------------------------------------------------------------------------
        */

        $response = Http::withOptions([

            'verify' => filter_var(
                config(
                    'services.momo.verify_ssl',
                    true
                ),
                FILTER_VALIDATE_BOOLEAN
            ),

        ])->post(
            $endpoint,
            $data
        );


        $result =
            $response->json() ?? [];


        /*
        |--------------------------------------------------------------------------
        | LƯU RESPONSE MOMO
        |--------------------------------------------------------------------------
        */

        $transaction->update([

            'response_payload' =>
                $result,

            'result_code' =>
                isset($result['resultCode'])
                    ? (int) $result['resultCode']
                    : null,

            'message' =>
                $result['message'] ?? null,

            'status' =>
                isset($result['payUrl'])
                    ? 'initiated'
                    : 'failed',
        ]);


        return $result;
    }


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA MOMO THANH TOÁN THÀNH CÔNG
    |--------------------------------------------------------------------------
    */

    public function isSuccessful(
        array $payload
    ): bool
    {
        return (string) (
            $payload['resultCode'] ?? ''
        ) === '0';
    }


    /*
    |--------------------------------------------------------------------------
    | ĐÁNH DẤU GIAO DỊCH ĐÃ THANH TOÁN
    |--------------------------------------------------------------------------
    */

    public function markPaid(
        PaymentTransaction $transaction,
        array $payload
    ): void
    {
        $transaction->update([

            'transaction_id' =>
                $payload['transId'] ?? null,

            'result_code' =>
                (int) (
                    $payload['resultCode'] ?? 0
                ),

            'message' =>
                $payload['message'] ?? null,

            'response_payload' =>
                $payload,

            'status' =>
                'paid',

            'paid_at' =>
                Carbon::now(),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | ĐÁNH DẤU THANH TOÁN THẤT BẠI
    |--------------------------------------------------------------------------
    */

    public function markFailed(
        PaymentTransaction $transaction,
        array $payload
    ): void
    {
        $transaction->update([

            'transaction_id' =>
                $payload['transId'] ?? null,

            'result_code' =>
                isset($payload['resultCode'])
                    ? (int) $payload['resultCode']
                    : null,

            'message' =>
                $payload['message'] ?? null,

            'response_payload' =>
                $payload,

            'status' =>
                'failed',
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA RESPONSE THÀNH CÔNG + CHỮ KÝ ĐÚNG
    |--------------------------------------------------------------------------
    */

    public function isValidSuccessfulResponse(
        array $payload
    ): bool
    {
        return
            $this->isValidResponse($payload)
            &&
            $this->isSuccessful($payload);
    }


    /*
    |--------------------------------------------------------------------------
    | KIỂM TRA CHỮ KÝ MOMO
    |--------------------------------------------------------------------------
    */

    public function isValidResponse(
        array $payload
    ): bool
    {
        if (!isset($payload['signature'])) {

            return false;
        }


        $accessKey =
            config(
                'services.momo.access_key',
                ''
            );

        $secretKey =
            config(
                'services.momo.secret_key',
                ''
            );


        $rawHash =
            'accessKey=' . $accessKey .
            '&amount=' . (
                $payload['amount'] ?? ''
            ) .
            '&extraData=' . (
                $payload['extraData'] ?? ''
            ) .
            '&message=' . (
                $payload['message'] ?? ''
            ) .
            '&orderId=' . (
                $payload['orderId'] ?? ''
            ) .
            '&orderInfo=' . (
                $payload['orderInfo'] ?? ''
            ) .
            '&orderType=' . (
                $payload['orderType'] ?? ''
            ) .
            '&partnerCode=' . (
                $payload['partnerCode'] ?? ''
            ) .
            '&payType=' . (
                $payload['payType'] ?? ''
            ) .
            '&requestId=' . (
                $payload['requestId'] ?? ''
            ) .
            '&responseTime=' . (
                $payload['responseTime'] ?? ''
            ) .
            '&resultCode=' . (
                $payload['resultCode'] ?? ''
            ) .
            '&transId=' . (
                $payload['transId'] ?? ''
            );


        return hash_equals(

            hash_hmac(
                'sha256',
                $rawHash,
                $secretKey
            ),

            (string) $payload['signature']
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LẤY ORDER ID CỦA HỆ THỐNG
    |--------------------------------------------------------------------------
    */

    public function orderId(
        array $payload
    ): ?int
    {
        $orderId =
            $payload['extraData'] ?? null;


        return is_numeric($orderId)
            ? (int) $orderId
            : null;
    }
}