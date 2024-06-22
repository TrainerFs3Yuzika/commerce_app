<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Carts;
use App\Models\ShippingCharge;
use App\Models\DiscountCoupon;

use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Midtrans\{Config, Snap};


class CallbackController extends Controller
{
    public function __construct()
    {
        // Inital Midtrans
        Config::$serverKey = env("MIDTRANS_SERVER_KEY");
        Config::$clientKey = env("MIDTRANS_CLIENT_KEY");
        Config::$isSanitized = Config::$is3ds = true;
    }

    public function callback(Request $request)
    {
        $transactionStatus = $request->transaction_status;
        $orderId = $request->order_id;

        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashedKey = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        $order     = Order::where('order_id', $orderId)->first();

        if ($hashedKey !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature key'], 403);
        }

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            if (in_array($order->payment_status, ['not paid', 'pending'])) {
                Order::where('order_id', $orderId)->update(['payment_status' => 'paid', 'status' => 'paid']);
            }
        }

        // return response()->json(['message' => 'Callback received successfully']);
    }
}