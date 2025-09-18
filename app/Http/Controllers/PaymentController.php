<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;

class PaymentController extends Controller
{
    public function createOrder(Request $request)
    {
        try {
            $api = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));

            $order = $api->order->create([
                'amount' => $request->amount * 100, // amount in paise
                'currency' => $request->currency ?? 'INR',
                'payment_capture' => 1
            ]);

               return response()->json([
    'success' => true,
    'orderId' => $order['id'],
    'amount' => $order['amount'],       // in paise
    'currency' => $order['currency'],
    'key' => env('RAZORPAY_KEY_ID')
]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating Razorpay order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
