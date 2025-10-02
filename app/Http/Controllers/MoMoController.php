<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MoMoController extends Controller
{
    public function createPayment(Request $request)
    {
        $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
        $partnerCode = "MOMOOJOI20210710";
        $accessKey = "iPXneGmrJH0G8FOP";
        $secretKey = "sFcbSGRSJjwGxwhhcEktCHWYUuTuPNDB";

        $orderId = time() . "";
        $orderInfo = $request->orderInfo;
        $amount = (int)$request->amount; // MoMo requires integer amount in VND
        $redirectUrl = $request->returnUrl;
        $ipnUrl = env('MOMO_IPN_URL', 'http://localhost:8000/api/momo-ipn');
        $requestId = time() . "";
        $requestType = "captureWallet";
        $extraData = "";

        $rawHash = "accessKey=$accessKey&amount=$amount&extraData=$extraData&ipnUrl=$ipnUrl&orderId=$orderId&orderInfo=$orderInfo&partnerCode=$partnerCode&redirectUrl=$redirectUrl&requestId=$requestId&requestType=$requestType";
        $signature = hash_hmac("sha256", $rawHash, $secretKey);

        $data = [
            'partnerCode' => $partnerCode,
            'partnerName' => "Test",
            'storeId' => "TestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature,
        ];

        try {
            $response = \Illuminate\Support\Facades\Http::post($endpoint, $data);
            $result = $response->json();

            if (isset($result['payUrl'])) {
                return response()->json(['payUrl' => $result['payUrl']]);
            } else {
                Log::error('MoMo API error: ' . json_encode($result));
                return response()->json(['message' => 'Lỗi khi tạo URL thanh toán MoMo'], 400);
            }
        } catch (\Exception $e) {
            Log::error('MoMo payment error: ' . $e->getMessage());
            return response()->json(['message' => 'Không thể kết nối tới MoMo'], 500);
        }
    }

    public function paymentReturn(Request $request)
    {
        $secretKey = "sFcbSGRSJjwGxwhhcEktCHWYUuTuPNDB";
        $data = $request->all();
        $signature = $data['signature'] ?? '';
        unset($data['signature']);

        $rawHash = "accessKey=iPXneGmrJH0G8FOP&amount=" . $data['amount'] . "&extraData=" . ($data['extraData'] ?? '') . "&message=" . $data['message'] . "&orderId=" . $data['orderId'] . "&orderInfo=" . $data['orderInfo'] . "&orderType=" . $data['orderType'] . "&partnerCode=" . $data['partnerCode'] . "&payType=" . $data['payType'] . "&requestId=" . $data['requestId'] . "&responseTime=" . $data['responseTime'] . "&resultCode=" . $data['resultCode'] . "&transId=" . $data['transId'];
        $checkSignature = hash_hmac("sha256", $rawHash, $secretKey);

        if ($signature === $checkSignature) {
            if ($data['resultCode'] == 0) {
                return response()->json(['message' => 'Thanh toán thành công']);
            } else {
                return response()->json(['message' => 'Thanh toán thất bại'], 400);
            }
        } else {
            return response()->json(['message' => 'Chữ ký không hợp lệ'], 400);
        }
    }

    public function ipn(Request $request)
    {
        Log::info('MoMo IPN received: ' . json_encode($request->all()));
        // Handle IPN logic here (e.g., update order status)
        return response()->json(['message' => 'IPN received']);
    }
}